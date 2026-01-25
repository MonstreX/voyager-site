<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\HasMedia;


class SystemPage extends Model
{
    use HasMedia;

    protected $guarded = [];

    protected $perPage = 50;

    public function seoMeta(): array
    {
        $seo = [
            'seo_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
        ];

        if (!empty($this->seo)) {
            $pageSeo = json_decode($this->seo);
            if ($pageSeo && isset($pageSeo->fields)) {
                $seo['seo_title'] = str_trans($pageSeo->fields->seo_title->value ?? '');
                $seo['meta_description'] = str_trans($pageSeo->fields->meta_description->value ?? '');
                $seo['meta_keywords'] = str_trans($pageSeo->fields->meta_keywords->value ?? '');
                return $seo;
            }
        }

        $seo['seo_title'] = $this->seo_title ?? '';
        $seo['meta_description'] = $this->meta_description ?? '';
        $seo['meta_keywords'] = $this->meta_keywords ?? '';

        return $seo;
    }
}
