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

        $custom_filters = config('voyager-site.template_filters');
        if ($custom_filters) {
            app($custom_filters)->handle($this->template, $content);
        }

        // SETTINGS
        $this->template->registerFilter('site_setting', function ($arg, $arg2 = null) {
            return site_setting($arg, $arg2);
        });

        // MENU
        $this->template->registerFilter('menu', function ($name, $template = null) {
            return menu($name, $template);
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

        // CONVERT TO WEBP IMAGE
        $this->template->registerFilter('webp', function ($image, $quality = null) {
            return get_image_or_create($image, null, null, 'webp', $quality);
        });

        // CROP IMAGE
        $this->template->registerFilter('crop', function ($image, $xsize = '', $ysize = '', $format = null, $quality = null) {
            return get_image_or_create($image, $xsize, $ysize, $format, $quality);
        });

        // TRANSLATE using tags
        $this->template->registerFilter('trans', function ($arg) {
            return str_trans($arg);
        });

        // TRANSLATE using lang files
        $this->template->registerFilter('lang', function ($arg) {
            return __($arg);
        });

        // Render blade VIEW using passed Data
        $this->template->registerFilter('view', function ($data, $template) {
            return view($template, ['data' => $data]);
        });

        // Call the service method using the given class and method
        $this->template->registerFilter('service', function ($method, $class) {

            $service = app($class);

            if (method_exists($service, $method)) {
                return $service->$method();
            }

            return null;
        });

        $this->template->parse($content);
    }

    public function render($vars)
    {
        return $this->template->render($vars);
    }

}
