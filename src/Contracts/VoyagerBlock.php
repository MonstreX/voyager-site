<?php
namespace MonstreX\VoyagerSite\Contracts;

/**
 * Interface VoyagerBlock
 * @package MonstreX\VoyagerSite\Contracts
 */
interface VoyagerBlock
{

    public function render($key);

    public function renderBlock($block);

    public function renderForm($key, $subject = null, $suffix = null);

    public function renderRegion($region_name, $path = null);

    public function renderLayout($layout, $page);

    public function getByID($id);

    public function getByKey($key);

    public function getByTitle($title);

    public function getFormByKey($key);

    public function getBlockField($block, $field);

}
