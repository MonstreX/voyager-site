<?php
namespace MonstreX\VoyagerSite\Traits;

use VPage, VData, VSite;

trait PageTrait
{

    protected $page;

    /*
     * Init Page Instance
     */
    public function create($alias, string $modelSlug = null, bool $fail = true)
    {
        $this->page = VPage::create(VData::find($alias, $modelSlug, $fail), VSite::getSettings());
    }


    public function view($templateLayout = null)
    {
        return VPage::view($templateLayout);
    }

}

