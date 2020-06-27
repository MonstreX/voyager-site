<?php


namespace MonstreX\VoyagerSite\Services;

use MonstreX\VoyagerSite\Contracts\VoyagerData as VoyagerDataContract;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use Schema;
use Str;

class VoyagerData implements VoyagerDataContract
{

    /*
     * Find model record by SLUG
     */
    public function findFirst($alias, string $modelSlug = null, bool $fail = true)
    {
        if (is_int($alias)) {
            return $this->where('id', $alias, $modelSlug, $fail);
        }

        return $this->where('slug', $alias, $modelSlug, $fail);
    }

    /*
     * Find (first) model record by Field and Value
     */
    public function where(string $field, string $value, string $modelSlug = null, bool $fail = true)
    {

        $model = $this->getModel($modelSlug);
        $data = $model::where($field, $value)->first();

        if (!$model) {
            $data = DB::table($modelSlug)->where($field, $value)->first();
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

    /*
     * Find records using given field and value (only with status = 1)
     */
    public function findByField(string $modelSlug, string $field, $value, string $order = 'order', string $direction = 'ASC')
    {
        $model = $this->getModel($modelSlug);

        $data = $model::where([$field => $value, 'status' => 1])->orderBy($order, $direction)->get();

        return $data;
    }

    /*
     *  Get Model using given model name (like 'Page')
     */
    private function getModel($modelSlug):object
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

        return $model;
    }

    /*
     *  Get models records using JSON model scheme
     */
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

    /*
     *  Get one model using JSON
     */
    public function getDataSource(object $data_source):array
    {

        $model_slug = Str::plural(mb_strtolower($data_source->model));

        $data = $this->getModel($model_slug);

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
        $dataType = Voyager::model('DataType')->where('slug', '=', $model_slug)->first();
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
            $menu_items = [$this->getMenuChildren($menu_items, $parent)];
        }

        return $menu_items;
    }

    /*
     * Get Children Tree Menu Items
     */
    private function getMenuChildren($items, $parent)
    {
        foreach ($items as $key => $item) {
            if ($item[$parent['field']] === $parent['value']) {
                return $item;
            }

            if (isset($item['children'])) {
                if ($result = $this->getMenuChildren($item['children'], $parent)) {
                    return $result;
                }
            }
        }
    }

}