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
        return VPage::create(VData::find($alias, $modelSlug, $fail), VSite::getSettings());
    }

    /*
     * Find a record
     */
    public function find($alias, string $modelSlug = null, bool $fail = true)
    {
        return VData::find($alias, $modelSlug, $fail);
    }

    /*
     * Render the Page
     */
    public function view($templateLayout = null, $data = null)
    {
        return VPage::view($templateLayout, $data);
    }

    /*
     * Set Master Template
     */
    public function setMasterTemplate($template)
    {
        return VPage::setMasterTemplate($template);
    }

    /*
     * Set Layout Template
     */
    public function setLayoutTemplate($template)
    {
        return VPage::setLayoutTemplate($template);
    }

    /*
     * Set Page Template
     */
    public function setPageTemplate($template)
    {
        return VPage::setPageTemplate($template);
    }

}

