<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

if (!function_exists('translit_cyrillic')) {
    function translit_cyrillic($string) {
        $charlist = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_"
        );
        return strtr($string,$charlist);
    }
}

if (!function_exists('get_file'))
{
    function get_file($file_path)
    {
        $ops = json_decode($file_path);
        if($ops) {
            return Storage::url(str_replace('\\', '/', $ops[0]->download_link));
        } else {
            return  Storage::url(str_replace('\\', '/', $file_path));
        }
    }

}


if (!function_exists('store_post_files')) {
    function store_post_files(Request $request, $slug, $field, $public = 'public')
    {

        if (!$request->has($field)) {
            return json_encode([]);
        }

        $files = Arr::wrap($request->file($field));

        $filesPath = [];

        foreach ($files as $file) {
            $path = $slug . DIRECTORY_SEPARATOR . date('FY') . DIRECTORY_SEPARATOR;
            $filename = generate_filename($file, $path);

            $file->storeAs(
                $path,
                $filename . '.' . $file->getClientOriginalExtension(),
                config('voyager.storage.disk', $public)
            );

            array_push($filesPath, [
                'download_link' => $path . $filename . '.' . $file->getClientOriginalExtension(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return json_encode($filesPath);
    }
}

if (!function_exists('generate_filename')) {
    function generate_filename($file, $path)
    {
        $filename = basename(translit_cyrillic($file->getClientOriginalName()), '.' . $file->getClientOriginalExtension());
        $filename_counter = 1;
        // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
        while (Storage::disk(config('voyager.storage.disk'))->exists($path . $filename . '.' . $file->getClientOriginalExtension())) {
            $filename = basename(translit_cyrillic($file->getClientOriginalName()), '.' . $file->getClientOriginalExtension()) . (string)($filename_counter++);
        }
        return $filename;
    }
}

if (!function_exists('site_setting')) {
    function site_setting($key, $default = null)
    {
        return VSite::setting($key, $default);
    }
}


if (!function_exists('site_setting_group')) {
    function site_settings_group($key)
    {
        if (\Schema::hasTable('site_settings')) {
            $settings = DB::table('site_settings')->where('key', $key)->first();
            $values = [];
            foreach (json_decode($settings->details)->fields as $key => $value) {
                if ($value->type !== 'section') {
                    $values[$key] = $value->value;
                }
            }
            return $values;
        }
        return null;
    }
}


// <img src="{{ get_image_or_create_webp($image_url, 400, 500) }}" alt="">
if (!function_exists('get_image_or_create_webp'))
{
    function get_image_or_create_webp($image_path, $width = null, $height = null, $quality = null)
    {
        return VData::getImageOrCreate($image_path, $width, $height, 'webp', $quality);
    }
}

if (!function_exists('get_image_webp'))
{
    function get_image_webp($image_path)
    {
        return VData::getImageOrCreate($image_path, null, null, 'webp');
    }
}

// <img src="{{ get_image_or_create($image_url, 400, 500) }}" alt="">
if (!function_exists('get_image_or_create'))
{
    function get_image_or_create($image_path, $width = null, $height  = null, $format  = null, $quality  = null)
    {
        return VData::getImageOrCreate($image_path, $width, $height, $format, $quality);
    }

}

/*
 * Returns first NOT empty element in given array
 */
if (!function_exists('get_first_not_empty')) {
    function get_first_not_empty(array $values)
    {
        foreach ($values as $value) {
            if (!empty($value)) {
                return $value;
            }
        }
        return null;
    }
}


/*
 * Blade templates helpers
 */
if (!function_exists('render_block')) {
    function render_block($key)
    {
        return VBlock::render($key);
    }
}

if (!function_exists('render_region')) {
    function render_region($key, $path = null)
    {
        return VBlock::renderRegion($key, $path);
    }
}

if (!function_exists('render_form')) {
    function render_form($key, $subject = null, $suffix = null)
    {
        return VBlock::renderForm($key, $subject, $suffix);
    }
}

if (!function_exists('render_layout')) {
    function render_layout($layout, $page)
    {
        return VBlock::renderLayout($layout, $page);
    }
}

if (!function_exists('get_block_field')) {
    function get_block_field($block, $field)
    {
        return VBlock::getBlockField($block, $field);
    }
}

