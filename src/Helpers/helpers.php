<?php

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

if (!function_exists('translit_cyrillic')) {
    function translit_cyrillic($string)
    {
        $charlist = [
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Д' => 'D', 'Е' => 'E', 'Ж' => 'J', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS', 'Ч' => 'CH',
            'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'YI', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a', 'б' => 'b',
            'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'j',
            'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h',
            'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => 'y',
            'ы' => 'yi', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '_',
        ];
        return strtr($string, $charlist);
    }
}

if (!function_exists('get_file')) {
    function get_file($file_path)
    {
        $ops = json_decode($file_path);
        if ($ops) {
            return Storage::url(str_replace('\\', '/', $ops[0]->download_link));
        }
        return Storage::url(str_replace('\\', '/', $file_path));
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
            $path = $slug.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR;
            $filename = generate_filename($file, $path);

            $file->storeAs(
                $path,
                $filename.'.'.$file->getClientOriginalExtension(),
                config('voyager.storage.disk', $public)
            );

            $filesPath[] = [
                'download_link' => $path.$filename.'.'.$file->getClientOriginalExtension(),
                'original_name' => $file->getClientOriginalName(),
            ];
        }

        return json_encode($filesPath);
    }
}

if (!function_exists('generate_filename')) {
    function generate_filename($file, $path)
    {
        $filename = basename(translit_cyrillic($file->getClientOriginalName()), '.'.$file->getClientOriginalExtension());
        $filename_counter = 1;
        while (Storage::disk(config('voyager.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
            $filename = basename(translit_cyrillic($file->getClientOriginalName()), '.'.$file->getClientOriginalExtension()).(string)($filename_counter++);
        }
        return $filename;
    }
}

if (!function_exists('site_setting')) {
    function site_setting($key, $default = null)
    {
        try {
            if (class_exists('VSite')) {
                return VSite::setting($key, $default);
            }
        } catch (\Throwable $e) {
        }

        if (function_exists('setting')) {
            return setting($key, $default);
        }

        return $default;
    }
}

if (!function_exists('site_setting_group')) {
    function site_settings_group($key)
    {
        if (\Schema::hasTable('site_settings')) {
            $settings = DB::table('site_settings')->where('key', $key)->first();
            $values = [];
            foreach (json_decode($settings->details)->fields as $fieldKey => $value) {
                if ($value->type !== 'section') {
                    $values[$fieldKey] = $value->value;
                }
            }
            return $values;
        }
        return null;
    }
}

if (!function_exists('get_image_or_create_webp')) {
    function get_image_or_create_webp($image_path, $width = null, $height = null, $quality = null)
    {
        return VData::getImageOrCreate($image_path, $width, $height, 'webp', $quality);
    }
}

if (!function_exists('get_image_webp')) {
    function get_image_webp($image_path)
    {
        return VData::getImageOrCreate($image_path, null, null, 'webp');
    }
}

if (!function_exists('get_image_or_create')) {
    function get_image_or_create($image_path, $width = null, $height = null, $format = null, $quality = null)
    {
        return VData::getImageOrCreate($image_path, $width, $height, $format, $quality);
    }
}

if (!function_exists('responsive_image')) {
    function responsive_image(string $src, int $width, ?int $height = null, array $options = [])
    {
        return app(\MonstreX\VoyagerSite\Services\ResponsiveImageService::class)
            ->image($src, $width, $height, $options);
    }
}

if (!function_exists('responsive_picture')) {
    function responsive_picture(string $src, int $width, ?int $height = null, array $options = [])
    {
        return app(\MonstreX\VoyagerSite\Services\ResponsiveImageService::class)
            ->picture($src, $width, $height, $options);
    }
}

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

if (!function_exists('str_trans')) {
    function str_trans(?string $string, $lang = null)
    {
        if (!$lang) {
            $lang = \App::getLocale();
        }

        $openBracket = '{{';
        $closeBracket = '}}';

        if (strpos($string ?? '', '[[') !== false) {
            $openBracket = '[[';
            $closeBracket = ']]';
        }

        $locStrings = explode($openBracket, $string ?? '');

        foreach ($locStrings as $line) {
            if (substr($line, 0, 4) === $lang.$closeBracket) {
                return substr($line, 4, strlen($line) - 4);
            }
        }

        if (!empty($locStrings)) {
            return $locStrings[0];
        }

        return !empty($string) ? $string : '';
    }
}

if (!function_exists('seo_meta')) {
    function seo_meta($model = null): array
    {
        $modelSeo = [];
        if (is_object($model) && method_exists($model, 'seoMeta')) {
            $modelSeo = $model->seoMeta();
        } elseif (is_array($model)) {
            $modelSeo = $model;
        }

        $globalSeo = site_settings_group('seo') ?? [];
        $generalSettings = site_settings_group('general') ?? [];

        $normalizeUrl = static function ($value): string {
            $value = trim((string)$value);
            if ($value === '') {
                return '';
            }
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            return url($value);
        };

        $resolveMediaUrl = static function ($value): string {
            if (is_numeric($value) && class_exists(\TCG\Voyager\Models\Media::class)) {
                $media = \TCG\Voyager\Models\Media::find((int)$value);
                if ($media) {
                    return $media->fullUrl();
                }
            }
            return (string)$value;
        };

        $modelTitle = '';
        if (is_object($model) && isset($model->title)) {
            $modelTitle = $model->title;
        } elseif (is_array($model) && isset($model['title'])) {
            $modelTitle = $model['title'];
        }

        $modelImage = '';
        if (is_object($model) && isset($model->image)) {
            $modelImage = $model->image;
        } elseif (is_array($model) && isset($model['image'])) {
            $modelImage = $model['image'];
        }

        $modelUrl = '';
        if (is_object($model)) {
            if (method_exists($model, 'getUrl')) {
                $modelUrl = $model->getUrl();
            } elseif (isset($model->url)) {
                $modelUrl = $model->url;
            } elseif (isset($model->link)) {
                $modelUrl = $model->link;
            }
        } elseif (is_array($model)) {
            $modelUrl = $model['url'] ?? $model['link'] ?? '';
        }

        $canonicalUrl = $modelSeo['canonical_url'] ?? '';
        $canonicalUrl = get_first_not_empty([
            $canonicalUrl,
            $modelUrl,
            url()->current(),
        ]);
        $canonicalUrl = $normalizeUrl($canonicalUrl);

        $ogImage = $modelSeo['og_image'] ?? '';
        if (empty($ogImage) && !empty($modelImage)) {
            $ogImage = $modelImage;
        }
        if (empty($ogImage)) {
            $ogImage = $globalSeo['og_image'] ?? '';
        }
        $ogImage = $resolveMediaUrl($ogImage);
        if (!empty($ogImage) && !str_starts_with($ogImage, 'http')) {
            $ogImage = url($ogImage);
        }

        $twitterImage = get_first_not_empty([
            $modelSeo['twitter_image'] ?? '',
            $globalSeo['twitter_image'] ?? '',
            $ogImage,
        ]);
        $twitterImage = $resolveMediaUrl($twitterImage);
        if (!empty($twitterImage) && !str_starts_with($twitterImage, 'http')) {
            $twitterImage = url($twitterImage);
        }

        $seoTitle = get_first_not_empty([
            $modelSeo['seo_title'] ?? '',
            $modelTitle,
            $globalSeo['seo_title'] ?? '',
            $generalSettings['site_title'] ?? config('app.name'),
        ]);

        $metaDescription = get_first_not_empty([
            $modelSeo['meta_description'] ?? '',
            $globalSeo['meta_description'] ?? '',
            $generalSettings['site_description'] ?? '',
        ]);

        return [
            'seo_title' => $seoTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => get_first_not_empty([
                $modelSeo['meta_keywords'] ?? '',
                $globalSeo['meta_keywords'] ?? '',
            ]),
            'canonical_url' => $canonicalUrl,
            'og_title' => get_first_not_empty([
                $modelSeo['og_title'] ?? '',
                $seoTitle,
            ]),
            'og_description' => get_first_not_empty([
                $modelSeo['og_description'] ?? '',
                $metaDescription,
            ]),
            'og_image' => $ogImage,
            'og_type' => get_first_not_empty([
                $modelSeo['og_type'] ?? '',
                $globalSeo['og_type'] ?? '',
                'website',
            ]),
            'og_site_name' => get_first_not_empty([
                $globalSeo['og_site_name'] ?? '',
                $generalSettings['site_title'] ?? config('app.name'),
            ]),
            'og_url' => $canonicalUrl,
            'twitter_card' => get_first_not_empty([
                $modelSeo['twitter_card'] ?? '',
                $globalSeo['twitter_card'] ?? '',
                'summary_large_image',
            ]),
            'twitter_site' => $globalSeo['twitter_site'] ?? '',
            'twitter_title' => get_first_not_empty([
                $modelSeo['og_title'] ?? '',
                $seoTitle,
            ]),
            'twitter_description' => get_first_not_empty([
                $modelSeo['og_description'] ?? '',
                $metaDescription,
            ]),
            'twitter_image' => $twitterImage,
        ];
    }
}

if (!function_exists('seo_tags')) {
    function seo_tags($model = null): \Illuminate\Support\HtmlString
    {
        $seo = seo_meta($model);
        $settings = site_settings_group('seo') ?? [];
        $isEnabled = static function ($value): bool {
            if (is_bool($value)) {
                return $value;
            }
            $value = strtolower(trim((string)$value));
            return in_array($value, ['1', 'true', 'yes', 'on'], true);
        };

        $enableCanonical = $isEnabled($settings['enable_canonical'] ?? false);
        $enableOg = $isEnabled($settings['enable_og'] ?? false);
        $enableTwitter = $isEnabled($settings['enable_twitter'] ?? false);

        $html = '';

        if (!empty($seo['meta_description'])) {
            $html .= '<meta name="description" content="'.e($seo['meta_description']).'">'."\n";
        }
        if (!empty($seo['meta_keywords'])) {
            $html .= '<meta name="keywords" content="'.e($seo['meta_keywords']).'">'."\n";
        }
        if ($enableCanonical && !empty($seo['canonical_url'])) {
            $html .= '<link rel="canonical" href="'.e($seo['canonical_url']).'">'."\n";
        }

        if ($enableOg) {
            if (!empty($seo['og_title'])) {
                $html .= '<meta property="og:title" content="'.e($seo['og_title']).'">'."\n";
            }
            if (!empty($seo['og_description'])) {
                $html .= '<meta property="og:description" content="'.e($seo['og_description']).'">'."\n";
            }
            if (!empty($seo['og_image'])) {
                $html .= '<meta property="og:image" content="'.e($seo['og_image']).'">'."\n";
            }
            if (!empty($seo['og_type'])) {
                $html .= '<meta property="og:type" content="'.e($seo['og_type']).'">'."\n";
            }
            if (!empty($seo['og_url'])) {
                $html .= '<meta property="og:url" content="'.e($seo['og_url']).'">'."\n";
            }
            if (!empty($seo['og_site_name'])) {
                $html .= '<meta property="og:site_name" content="'.e($seo['og_site_name']).'">'."\n";
            }
        }

        if ($enableTwitter) {
            if (!empty($seo['twitter_card'])) {
                $html .= '<meta name="twitter:card" content="'.e($seo['twitter_card']).'">'."\n";
            }
            if (!empty($seo['twitter_site'])) {
                $html .= '<meta name="twitter:site" content="'.e($seo['twitter_site']).'">'."\n";
            }
            if (!empty($seo['twitter_title'])) {
                $html .= '<meta name="twitter:title" content="'.e($seo['twitter_title']).'">'."\n";
            }
            if (!empty($seo['twitter_description'])) {
                $html .= '<meta name="twitter:description" content="'.e($seo['twitter_description']).'">'."\n";
            }
            if (!empty($seo['twitter_image'])) {
                $html .= '<meta name="twitter:image" content="'.e($seo['twitter_image']).'">'."\n";
            }
        }

        return new \Illuminate\Support\HtmlString($html);
    }
}

if (!function_exists('scripts')) {
    function scripts(string $position): string
    {
        try {
            if (!\Schema::hasTable('site_scripts')) {
                return '';
            }

            $scripts = \MonstreX\VoyagerSite\Models\Script::active()
                ->byPosition($position)
                ->ordered()
                ->get();

            if ($scripts->isEmpty()) {
                return '';
            }

            $output = [];
            foreach ($scripts as $script) {
                $content = trim($script->content);
                if ($content !== '') {
                    $output[] = $content;
                }
            }

            return implode("\n", $output);
        } catch (\Throwable $e) {
            return '';
        }
    }
}
