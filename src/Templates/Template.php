<?php


namespace MonstreX\VoyagerSite\Templates;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Liquid\Template as liquidTemplate;

class Template
{
    protected $template;

    public function __construct($content)
    {
        $this->template = new liquidTemplate();

        // FUNCTION
        $this->template->registerFilter('func', function ($func_name, $param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null) {
            $res = '';
            if ($param5 !== null) {
                $res = $func_name($param1, $param2, $param3, $param4, $param5);
            } elseif ($param4 !== null) {
                $res = $func_name($param1, $param2, $param3, $param4);
            } elseif ($param3 !== null) {
                $res = $func_name($param1, $param2, $param3);
            } elseif ($param2 !== null) {
                $res = $func_name($param1, $param2);
            } elseif ($param1 !== null) {
                $res = $func_name($param1);
            }
            return $res;
        });


        // SETTINGS
        $this->template->registerFilter('site_setting', function ($arg, $arg2 = null) {
            return site_setting($arg, $arg2);
        });

        // BLOCK
        $this->template->registerFilter('block', function ($arg) {
            return render_block($arg);
        });

        // FORM
        $this->template->registerFilter('form', function ($name, $subject = null, $suffix = null) {
            return render_form($name, $subject, $suffix);
        });

        // URL
        $this->template->registerFilter('url', function ($arg) {
            return url($arg);
        });

        // ROUTE
        $this->template->registerFilter('route', function ($route, $param = null) {
            if($param) {
                return route($route, $param);
            } else {
                return route($route);
            }
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