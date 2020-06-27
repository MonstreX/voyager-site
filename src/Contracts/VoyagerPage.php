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
    public function getTitle();

    /**
     * @return mixed
     */
    public function getPage();

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @return mixed
     */
    public function getDataSources();

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
     * @param Model $content
     * @return mixed
     */
    public function setPage(Model $content);

    /**
     * @param Model $content
     * @return mixed
     */
    public function setContent(Model $content);

    /**
     * @param $dataSources
     * @return mixed
     */
    public function setDataSources($dataSources);

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
     * @param Model $content
     * @param array $settings
     * @return mixed
     */
    public function setTemplates(Model $content, array $settings);

    /**
     * @param string $template
     * @return mixed
     */
    public function setMasterTemplate(string $template);

    /**
     * @param string $template
     * @return mixed
     */
    public function setLayoutTemplate(string $template);

    /**
     * @param string $template
     * @return mixed
     */
    public function setPageTemplate(string $template);

    /**
     * @param Model $content
     * @param array $settings
     * @return mixed
     */
    public function setSeo(Model $content, array $settings);

    /**
     * @return mixed
     */
    public function startBreadcrumbs();

    /**
     * @param string $label
     * @param string $url
     * @return mixed
     */
    public function addBreadcrumbs(string $label, $url = '#');

    /**
     * @return mixed
     */
    public function getBreadcrumbs();

    /**
     * @return mixed
     */
    public function buildBreadcrumbs();

    /**
     * @param Model|null $page
     * @return mixed
     */
    public function setParents(Model $page = null);

    /**
     * @return mixed
     */
    public function getParents();

    /**
     * @param Model $content
     * @param array $settings
     * @return mixed
     */
    public function create(Model $content, array $settings);

    /**
     * @param string|null $template_layout
     * @param array|null $data
     * @return mixed
     */
    public function view(string $template_layout = null, array $data = null);

    /**
     * @param Model $page
     * @param array $parents
     * @param string $default_banner
     * @return mixed
     */
    public function setBanner(Model $page, array $parents, string $default_banner);

    /**
     * @param string $parent_field
     * @return mixed
     */
    public function setChildren(string $parent_field);

    /**
     * @return mixed
     */
    public function getChildren();

}
