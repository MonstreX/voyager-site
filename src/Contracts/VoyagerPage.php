<?php
namespace MonstreX\VoyagerSite\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface VoyagerPage
 * @package MonstreX\VoyagerSite\Contracts
 */
interface VoyagerPage
{

    /**
     * @return mixed
     */
    public function getSeoTitle();

    /**
     * @return mixed
     */
    public function getSeoDescription();

    /**
     * @return mixed
     */
    public function getSeoKeywords();

    /**
     * @param string $title
     * @return mixed
     */
    public function setTitle(string $title);

    /**
     * @param string $title
     * @return mixed
     */
    public function setSeoTitle(string $title);

    /**
     * @param string $description
     * @return mixed
     */
    public function setSeoDescription(string $description);

    /**
     * @param string $keywords
     * @return mixed
     */
    public function setSeoKeywords(string $keywords);

    /**
     * @param $contentData
     * @param array $settings
     * @return mixed
     */
    public function create($contentData, array $settings);

    /**
     * @param Model $contentData
     * @param array $settings
     * @return mixed
     */
    public function setTemplates(Model $contentData, array $settings);

    /**
     * @param Model $contentData
     * @param array $settings
     * @return mixed
     */
    public function setSeo(Model $contentData, array $settings);

    /**
     * @return mixed
     */
    public function startBreadcrumbs();

    /**
     * @param $label
     * @param string $url
     * @return mixed
     */
    public function addBreadcrumbs($label, $url = '#');

    /**
     * @param null $template_layout
     * @return mixed
     */
    public function view($template_layout = null);

}
