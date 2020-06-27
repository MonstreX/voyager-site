<?php
namespace MonstreX\VoyagerSite\Contracts;

/**
 * Interface VoyagerData
 * @package MonstreX\VoyagerSite\Contracts
 */
interface VoyagerData
{

    public function findFirst($alias, string $modelSlug = null, bool $fail = true);

    public function findByField(string $modelSlug, string $field, $value, string $order = 'order', string $direction = 'ASC');

    public function where(string $field, string $value, string $modelSlug = null, bool $fail = true);

    public function getDataSources(object $data_sources):array;

    public function getDataSource(object $data_source):array;

}
