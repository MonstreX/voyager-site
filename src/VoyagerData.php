<?php


namespace MonstreX\VoyagerSite;

use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class VoyagerData
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
            abort(404);
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
        $data = app(config('voyager.models.namespace').$data_source->model);

        // Sorting
        if(isset($data_source->order)) {
            $data = $data->orderBy($data_source->order->field, $data_source->order->direction);
        }

        // Only with status = 1
        $data = $data->where("status",1);

        // Find By Field
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

        // Limitation
        if(isset($data_source->limit)) {
            $data_records = collect($data_records);
            $data_records = $data_records->take($data_source->limit)->toArray();
        } else {
            $data_records = $data_records->toArray();
        }

        return $data_records;
    }
}