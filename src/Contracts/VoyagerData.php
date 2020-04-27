<?php
namespace MonstreX\VoyagerSite\Contracts;

/**
 * Interface VoyagerData
 * @package MonstreX\VoyagerSite\Contracts
 */
interface VoyagerData
{

    public function find($alias, string $modelSlug = null, bool $fail = true);

    public function where(string $field, string $value, string $modelSlug = null, bool $fail = true);

    public function getDataSources(object $data_sources):array;

    public function getDataSource(object $data_source):array;

}
