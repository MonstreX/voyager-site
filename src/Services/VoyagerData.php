<?php


namespace MonstreX\VoyagerSite\Services;

use MonstreX\VoyagerSite\Contracts\VoyagerData as VoyagerDataContract;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use Schema;


class VoyagerData implements VoyagerDataContract
{

    /*
     * Find model record by SLUG
     */
    public function find($alias, string $modelSlug = null, bool $fail = true)
    {
        if (is_int($alias)) {
            return $this->where('id', $alias, $modelSlug, $fail);
        }

        return $this->where('slug', $alias, $modelSlug, $fail);
    }


    /*
     * Find model record by Field
     */
    public function where(string $field, string $value, string $modelSlug = null, bool $fail = true)
    {
        if (!$modelSlug) {
            // Default mode is a Page
            $modelSlug = config('voyager-site.default_model_table');
        }

        $dataType = Voyager::model('DataType')->where('slug', '=', $modelSlug)->first();

        if ($dataType) {

            $model = app($dataType->model_name);
            $data = $model::where($field, $value)->first();

        } else {

            $model = app(config('voyager.models.namespace').$modelSlug);
            $data = $model::where($field, $value)->first();

            if (!$data) {
                $data = DB::table($modelSlug)->where($field, $value)->first();
            }

        }

        // Drop 404 Error if not found or published (status = 0)
        if ((!$data && $fail) || (isset($data->status) && (int) $data->status !== 1 && $fail)) {

            if(!config('voyager-site.use_legacy_error_handler')) {
                throw new \MonstreX\VoyagerSite\Exceptions\VoyagerSiteException(__('voyager-site.errors.error_404_message'), 404);
            }

            abort(404);
        }

        if (Voyager::translatable($data)) {
            $data->load('translations');
        }

        return $data;
    }


    public function getDataSources(object $data_sources):array
    {
        if (isset($data_sources)) {
            $data = [];
            foreach ($data_sources as $key => $data_source) {
                $data[$key] = $this->getDataSource($data_source);
            }
            return $data;
        } else {
            return [];
        }
    }


    public function getDataSource(object $data_source):array
    {

        // Source
        $model_class = config('voyager.models.namespace').$data_source->model;
        $data = app($model_class);

        // Sorting
        if(isset($data_source->order)) {
            $data = $data->orderBy($data_source->order->field, $data_source->order->direction);
        }

        // Preload relations
        if(isset($data_source->with)) {
            $data = $data->with($data_source->with);
        }

        // Where
        if(isset($data_source->where)) {
            $data = $data->where($data_source->where->field, $data_source->where->value);
        }

        // Multiple Where Array
        if(isset($data_source->where_array)) {
            foreach ($data_source->where_array as $key => $value) {
                $data = $data->where($key, $value);
            }
        }

        // Make Collection
        $data_records = $data->get();

        // Load related Media Data if needed and convert it to an array
        // It is necessary Only for accessing in Liquid templates
        $dataType = Voyager::model('DataType')->where('model_name', '=', $model_class)->first();
        $rows = $dataType->rows()->get();

        foreach ($data_records as $record) {
            if ($rows) {
                foreach ($rows as $row) {
                    // Check for only Medialibrary types
                    if ($row->type === 'adv_image' || $row->type === 'adv_media_files') {

                        $images = $record->getMedia($row->field);

                        foreach ($images as $key => $image) {
                            $images[$key]->url = $image->getUrl();
                            $images[$key]->full_url = $image->getFullUrl();
                            $images[$key]->path = $image->getPath();
                        }
                        $record->{$row->field} = $images->toArray();
                    }
                }
            }
        }

        // Translate if required
        if (Voyager::translatable($data)) {
            $data->load('translations');
        }

        // Limitation
        if(isset($data_source->limit)) {
            $data_records = collect($data_records);
            $data_records = $data_records->take($data_source->limit)->toArray();
        } else {
            $data_records = $data_records->toArray();
        }

        return $data_records;
    }


    /*
     * Get Tree Menu Items
     */
    public function getMenu(string $modelSlug = null, array $parent = null)
    {

        if (!$modelSlug) {
            // Default mode is a Page
            $modelSlug = config('voyager-site.default_model_table');
        }

        $dataType = Voyager::model('DataType')->where('slug', '=', $modelSlug)->first();

        if ($dataType) {
            $model = app($dataType->model_name);
        } else {
            $model = app(config('voyager.models.namespace').$modelSlug);
        }

        // Check if it tree model
        if (!Schema::hasColumn($model->getTable(), 'parent_id')) {
            return null;
        }

        $menu_items = flat_to_tree($model::all()->toArray());

        if ($parent) {
            $menu_items = [$this->getChildren($menu_items, $parent)];
        }

        return $menu_items;
    }

    /*
     * Get Children Tree Menu Items
     */
    private function getChildren($items, $parent)
    {
        foreach ($items as $key => $item) {
            if ($item[$parent['field']] === $parent['value']) {
                return $item;
            }

            if (isset($item['children'])) {
                if ($result = $this->getChildren($item['children'], $parent)) {
                    return $result;
                }
            }
        }
    }



}