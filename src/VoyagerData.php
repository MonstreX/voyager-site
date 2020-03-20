<?php


namespace MonstreX\VoyagerSite;


class VoyagerData
{

    /*
     * Find model record by SLUG
     */
    public function findBySlugOrFail(string $slug, string $modelClass = null)
    {
        return $this->findByField('slug', $slug, $modelClass);
    }

    /*
     * Find model record by ID
     */
    public function findOrFail(string $id, string $modelClass = null)
    {
        return $this->findByField('id', $id, $modelClass);
    }

    /*
     * Find model record by Field
     */
    public function findByField(string $field, string $value, string $modelClass = null)
    {
        if (!$modelClass) {
            // Default mode is a Page
            $modelClass = 'Page';
        }

        $model = app(config('voyager.models.namespace').$modelClass);
        if (!$data = $model::where($field, $value)->first()) {
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