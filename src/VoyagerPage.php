<?php


namespace MonstreX\VoyagerSite;

use Illuminate\Database\Eloquent\Model;
use VSite, VData;

class VoyagerPage
{

    // Settings
    protected $settings;

    // MODEL Title if present
    protected $title;

    // Loaded MODEL Record
    protected $contentData;

    // Attached Models Records
    protected $dataSets;

    // Templates
    protected $template;
    protected $templateMaster;
    protected $templateLayout;
    protected $templatePage;

    // Breadcrumbs
    protected $breadcrumbs = [];

    // SEO Data
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
     * Get SEO Title
     */
    public function getSeoTitle()
    {
        $title = $this->seoTitle;
        if ($this->settings['seo_title_template']) {
            $title = str_replace('%site_title%', $this->settings['site_title'], $this->settings['seo_title_template']);
            $title = str_replace('%seo_title%', $this->seoTitle, $title);
        }
        return $title;
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
     * Create Page
     */
    public function create($contentData, array $settings)
    {
        // If we don't have related Data
        if(!$contentData) {
            if(!config('voyager-site.use_legacy_error_handler')) {
                throw new \MonstreX\VoyagerSite\Exceptions\VoyagerSiteException(__('voyager-site.errors.error_404_message'), 404);
            }
            abort(404);
        }

        // General settings
        $this->settings = $settings;

        // Model Content
        $this->contentData = $contentData;

        // Title
        $this->title = isset($contentData->title)? $contentData->title : '';

        // Templates
        $this->setTemplates($contentData, $settings);

        // SEO
        $this->setSeo($contentData, $settings);

        // Breadcrumbs
        $this->startBreadcrumbs();

        // Attach Data Sets if present
        $details = json_decode($this->contentData->details);
        if ($details && isset($details->data_sources)) {
            $this->dataSets = VData::getDataSources($details->data_sources);
        }

        return $this;
    }

    /*
     * Init Templates names
     */
    public function setTemplates(Model $contentData, array $settings)
    {
        $page_templates = json_decode($contentData->details);
        $this->template = isset($page_templates->template)? $page_templates->template : $settings['template'];
        $this->templateMaster = isset($page_templates->template_master)? $page_templates->template_master : $settings['template_master'];
        $this->templateLayout = isset($page_templates->template_layout)? $page_templates->template_layout : $settings['template_layout'];
        $this->templatePage = isset($page_templates->template_page)? $page_templates->template_page : $settings['template_page'];
    }

    /*
     * Init SEO Data
     */
    public function setSeo(Model $contentData, array $settings)
    {

        $page_seo = json_decode($contentData->seo);

        // TITLE
        $this->seoTitle = get_first_not_empty([
            isset($page_seo->fields->seo_title->value)? $page_seo->fields->seo_title->value : null,
            isset($contentData->title)? $contentData->title : null,
            $settings['seo_title'],
            $settings['site_title']
        ]);

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
     *  Returns rendered VIEW using PAGE Vars
     */
    public function view($template_layout = null)
    {

        // If layout template not present in params
        if (!$template_layout) {
            $template_layout = $this->settings['template'] . '.' . $this->settings['template_layout'];
        }

        return view($template_layout)->with([
            'template_master' => $this->settings['template'] . '.' . $this->settings['template_master'],
            'template_page' => $this->settings['template'] . '.' . $this->templatePage,
            'breadcrumbs' => $this->breadcrumbs,
            'title' => $this->title,
            'page' => $this->contentData,
            'page_data_sets' => $this->dataSets,
            'seo' => [
                'title' => $this->getSeoTitle(),
                'description' => $this->getSeoDescription(),
                'keywords' => $this->getSeoKeywords(),
            ]
        ])->render();
    }

}