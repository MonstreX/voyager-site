<?php
namespace MonstreX\VoyagerSite\Traits;

use VPage, VData, VSite;

trait PageTrait
{

    /*
     * Init Page Instance
     */
    public function create($alias, string $modelSlug = null, bool $fail = true)
    {
        return VPage::create(VData::findFirst($alias, $modelSlug, $fail), VSite::getSettings());
    }

    /*
     * Find the first record
     */
    public function findFirst($alias, string $modelSlug = null, bool $fail = true)
    {
        return VData::findFirst($alias, $modelSlug, $fail);
    }

    /*
     *  Fint multiple records
     */
    public function findByField(string $modelSlug, string $field, $value, string $order = 'order', string $direction = 'ASC')
    {
        return VData::findByField($modelSlug, $field, $value, $order, $direction);
    }

    /*
     * Render the Page
     */
    public function view(string $templateLayout = null, array $data = null)
    {
        return VPage::view($templateLayout, $data);
    }

    /*
     * Set Master Template
     */
    public function setMasterTemplate(string $template)
    {
        return VPage::setMasterTemplate($template);
    }

    /*
     * Set Layout Template
     */
    public function setLayoutTemplate(string $template)
    {
        return VPage::setLayoutTemplate($template);
    }

    /*
     * Set Page Template
     */
    public function setPageTemplate(string $template)
    {
        return VPage::setPageTemplate($template);
    }

    /*
     * Set Page Instance
     */
    public function setPage(Model $content)
    {
        return VPage::setPage($content);
    }

    /*
     * Get Page Instance
     */
    public function getPage()
    {
        return VPage::getPage();
    }

    /*
     * Set Page Instance
     */
    public function setContent(Model $content)
    {
        return VPage::setContent($content);
    }

    /*
     * Get Page Instance
     */
    public function getContent()
    {
        return VPage::getContent();
    }

    /*
     * Add breadcrumb element
     */
    public function addBreadcrumbs(string $label, $url = '#')
    {
        return VPage::addBreadcrumbs($label, $url);
    }

    /*
     * Build breadcrumbs
     */
    public function buildBreadcrumbs()
    {
        return VPage::buildBreadcrumbs();
    }

    /*
     * Get parents chain
     */
    public function getParents()
    {
        return VPage::getParents();
    }

    /*
     * Set Banner Image
     */
    public function setBanner(Model $page, array $parents, string $default_banner)
    {
        return VPage::setBanner($page, $parents, $default_banner);
    }

    /*
     * Set Children
     */
    public function setChildren(string $parent_field)
    {
        return VPage::setChildren($parent_field);
    }
}

