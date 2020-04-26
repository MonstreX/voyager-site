<?php
namespace MonstreX\VoyagerSite\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface VoyagerSite
 * @package MonstreX\VoyagerSite\Contracts
 */
interface VoyagerSite
{

    /**
     * Returns Given setting value
     * @param $key
     * @param null $default
     * @return Mixed
     */
    public function setting($key, $default = null);

    /**
     * @return mixed
     */
    public function getSettings();

    /**
     * @param Model $class
     * @param $field
     * @return string
     */
    public function storeMediaFile(Model $class, $field):string;

    /**
     * @return string
     */
    public function currentPath(): string;

}
