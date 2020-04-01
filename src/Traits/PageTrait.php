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

}

