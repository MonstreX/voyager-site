<?php

namespace MonstreX\VoyagerSite\Services;

use MonstreX\VoyagerSite\Contracts\VoyagerPage as VoyagerPageContract;
use Illuminate\Database\Eloquent\Model;
use Schema;
use VSite, VData;

class VoyagerPage implements VoyagerPageContract
{
    /* Conventions of using custom model properties and settings
     *
     * Settings:
     * site_setting('theme.theme_banner_image') - Page header image
     *
     * Model custom properties:
     * public $masterPageRouteName = 'page'; - Route name for master page (uses in breadcrumbs)
     * public $masterPageSlug = 'pages'; - table name for master page
     * public $masterPageId = 2; - Record ID of master page
     * public $PageRouteName = 'service';  - Route name for current page (uses in breadcrumbs)
     * public $bannerField = 'banner_image'; - Banner image Field name
     *
     */

    // Settings
    protected $settings;

    // MODEL Title if present
    protected $title;

    // Loaded MODEL Record
    protected $content;

    // Parents chain
    protected $parents;

    // Header page image
    protected $banner;

    // Attached Models Records
    protected $dataSources;

    // Children of current page
    protected $children;

    // Templates
    protected $template;
    protected $templateMaster;
    protected $templateLayout;
    protected $templatePage;

    // Breadcrumbs
    protected $breadcrumbs = [];

    // SEO Data 1
    protected $seoTitleTemplate;
    protected $seoTitle;
    protected $metaDescription;
    protected $metaKeywords;

    /*
     * Get Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /*
     * Get Current Page
     */
    public function getPage()
    {
        return $this;
    }

    /*
     * Get Current Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /*
     * Get Data Sources
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }

    /*
     * Get SEO Title
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /*
     * Get SEO Description
     */
    public function getSeoDescription()
    {
        return $this->metaDescription;
    }

    /*
     * Get SEO Keywords
     */
    public function getSeoKeywords()
    {
        return $this->metaKeywords;
    }


    /*
     * Set Title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /*
     * Set Current Page
     */
    public function setPage(Model $content)
    {
        return $this->create($content, VSite::getSettings());
    }

    /*
     * Set Current Content
     */
    public function setContent(Model $content)
    {
        $this->content = $page;
    }

    /*
     * Set Data Sources
     */
    public function setDataSources($dataSources)
    {
        $this->dataSources = $dataSources;
    }

    /*
     * Set SEO Title
     */
    public function setSeoTitle(string $title)
    {
        $this->seoTitle = $title;
    }

    /*
     * Set SEO Description
     */
    public function setSeoDescription(string $description)
    {
        $this->metaDescription = $description;
    }

    /*
     * Set SEO Keywords
     */
    public function setSeoKeywords(string $keywords)
    {
        $this->metaKeywords = $keywords;
    }

    /*
     * Init Templates names
     */
    public function setTemplates(Model $content, array $settings)
    {
        $page_templates = json_decode($content->details);
        $this->template = isset($page_templates->template)? $page_templates->template : $settings['template'];
        $this->templateMaster = isset($page_templates->template_master)? $page_templates->template_master : $settings['template_master'];
        $this->templateLayout = isset($page_templates->template_layout)? $page_templates->template_layout : $settings['template_layout'];
        $this->templatePage = isset($page_templates->template_page)? $page_templates->template_page : $settings['template_page'];
    }

    /*
     * Set Master Template
     */
    public function setMasterTemplate(string $template)
    {
        $this->templateMaster = $template;
    }

    /*
     * Set Layout Template
     */
    public function setLayoutTemplate(string $template)
    {
        $this->templateLayout = $template;
    }

    /*
     * Set Page Template
     */
    public function setPageTemplate(string $template)
    {
        $this->templatePage = $template;
    }

    /*
     * Init SEO Data
     */
    public function setSeo(Model $content, array $settings)
    {

        $page_seo = json_decode($content->seo);

        // TITLE
        $this->seoTitle = get_first_not_empty([
            isset($page_seo->fields->seo_title->value)? $page_seo->fields->seo_title->value : null,
            isset($content->title)? $content->title : null,
            $settings['seo_title'],
            $settings['site_title']
        ]);

        // Apply template if present
        if ($this->settings['seo_title_template']) {
            $title = str_replace('%site_title%', $this->settings['site_title'], $this->settings['seo_title_template']);
            $title = str_replace('%seo_title%', $this->seoTitle, $title);
            $this->seoTitle = $title;
        }

        // DESCRIPTION
        $this->metaDescription = get_first_not_empty([
            isset($page_seo->fields->meta_description->value)? $page_seo->fields->meta_description->value : null,
            $settings['meta_description'],
            $settings['site_description']
        ]);

        // KEYWORDS
        $this->metaKeywords = get_first_not_empty([
            isset($page_seo->fields->meta_keywords->value)? $page_seo->fields->meta_keywords->value : null,
            $settings['meta_keywords'],
        ]);

    }

    /*
     * Clear and Add the First breadcrumb with Home Page Route
     */
    public function startBreadcrumbs()
    {
        $this->breadcrumbs = [];
        $this->addBreadcrumbs(__('site.breadcrumb_home'), route(config('voyager-site.route_home_page')));
    }

    /*
     * Add breadcrumb element
     */
    public function addBreadcrumbs($label, $url = '#')
    {
        $this->breadcrumbs[] = [
            'label' => $label,
            'url' => $url
        ];
    }

    /*
     * Returns Breadcrumbs array
     */
    public function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }

    public function buildBreadcrumbs()
    {
        foreach ($this->parents as $key => $parent) {
            $route_name = isset($parent->PageRouteName)? $parent->PageRouteName : $this->content->masterPageRouteName;
            $this->addBreadcrumbs($parent->title, route($route_name, $parent->slug));
        }
    }

    /*
     * Get parents chain (except current page)
     */
    public function setParents(Model $page = null, $parent_field = 'parent_id')
    {
        if (!$page) {
            $page = $this->content;
        }

        $parents = [];
        $current = $page;

        //Collect all parents in current model
        while (!empty($current->{$parent_field})) {
            $current = VData::findFirst((int)$current->{$parent_field}, $page->getTable());
            $parents[] = $current;
        }
        // Get Master Page
        if (isset($page->masterPageId)) {
            $parents[] = VData::findFirst((int)$page->masterPageId, $page->masterPageSlug);
        }

        $this->parents = array_reverse($parents);

        return $this->parents;
    }

    /*
     * Get Parents Chain
     */
    public function getParents()
    {
        return $this->parents;
    }

    /*
     * Set Children for the given content. Parent field should consists parent ID
     */
    public function setChildren(string $parent_field)
    {

        if (Schema::hasColumn($this->content->getTable(), $parent_field)) {
            $this->children = VData::findByField($this->content->getTable(), $parent_field, (int)$this->content->id);
        }

        return $this->children;
    }

    /*
     * Get Children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /*
     * Create Page
     */
    public function create(Model $content, array $settings)
    {
        // If we don't have related Data
        if(!$content) {
            if(!config('voyager-site.use_legacy_error_handler')) {
                throw new \MonstreX\VoyagerSite\Exceptions\VoyagerSiteException(__('voyager-site.errors.error_404_message'), 404);
            }
            abort(404);
        }

        // General settings
        $this->settings = $settings;

        // Model Content
        $this->content = $content;

        // Title
        $this->title = isset($content->title)? $content->title : '';

        // Templates
        $this->setTemplates($content, $settings);

        // SEO
        $this->setSeo($content, $settings);

        // Breadcrumbs
        $this->startBreadcrumbs();

        // Set parents chain
        $this->setParents();

        // Set page header banner
        $this->setBanner($content, $this->parents, site_setting('theme.theme_banner_image'));

        // Attach Data Sets if present
        $details = json_decode($this->content->details);
        if ($details && isset($details->data_sources)) {
            $this->dataSources = VData::getDataSources($details->data_sources);
        }

        return $this;
    }


    /*
     *  Returns rendered VIEW using PAGE Vars
     */
    public function view(string $template_layout = null, array $data = null)
    {

        $this->addBreadcrumbs($this->title);

        return view($template_layout?? $this->settings['template'] . '.' . $this->templateLayout)->with([
            'template' => $this->settings['template'],
            'template_master' => $this->settings['template'] . '.' . $this->settings['template_master'],
            'template_page' => $this->settings['template'] . '.' . $this->templatePage,
            'breadcrumbs' => $this->breadcrumbs,
            'banner' => $this->banner,
            'title' => $this->title,
            'page' => $this->content,
            'parents' => $this->parents,
            'children' => $this->children,
            'data_sources' => $this->dataSources,
            'seo' => [
                'title' => $this->getSeoTitle(),
                'description' => $this->getSeoDescription(),
                'keywords' => $this->getSeoKeywords(),
            ],
            'data' => $data,
        ])->render();
    }

    /*
     * Set Banner Image (header page image)
     */
    public function setBanner(Model $page, array $parents, string $default_banner)
    {
        $banner_field = isset($page->bannerField)? $page->bannerField : 'banner_image';

        $banner = $page->getFirstMediaUrl($banner_field);

        if (empty($banner)) {
            foreach (array_reverse($parents) as $parent) {
                $banner = $parent->getFirstMediaUrl($banner_field);
                if (!empty($banner)) {
                    break;
                }
            }

            // If we don't have any attached banner in our models we use global settings banner
            if (empty($banner)) {
                $banner = $default_banner;
            }
        }

        $this->banner = $banner;

        return $banner;
    }

}