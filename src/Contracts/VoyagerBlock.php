<?php
namespace MonstreX\VoyagerSite\Contracts;

/**
 * Interface VoyagerBlock
 * @package MonstreX\VoyagerSite\Contracts
 */
interface VoyagerBlock
{
    public function renderRegion($region_name, $path = null);

    public function render($key, $id = null);

    public function renderBlock($block, $id = null);

    public function getByKey($key);

    public function getByTitle($title);

    public function renderForm($key, $subject = null, $suffix = null);

    public function getFormByKey($key);

    public function renderLayout($layout, $page);
}
