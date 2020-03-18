<?php


namespace MonstreX\VoyagerSite\Templates;

use Liquid\Template as liquidTemplate;

class Template
{
    protected $template;

    public function __construct($content)
    {
        $this->template = new liquidTemplate();

        // URL
        $this->template->registerFilter('url', function ($arg) {
            return url($arg);
        });

        // CROP IMAGE
        $this->template->registerFilter('crop', function ($image, $xsize = '', $ysize = '') {
            return get_image_or_create($image, $xsize, $ysize);
        });

        $this->template->parse($content);
    }

    public function render($vars)
    {
        return $this->template->render($vars);
    }

}