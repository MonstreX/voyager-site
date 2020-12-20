<?php


namespace MonstreX\VoyagerSite\Templates;

use VBlock;

class CustomShortcodes
{

    // [block name="top-line"]
    public function block($shortcode, $content, $compiler, $name, $viewData)
    {
        return VBlock::render($shortcode->name);
    }

    // [form name="callback-form" subject="Callback form submission" suffix="callback-frm"]
    public function form($shortcode, $content, $compiler, $name, $viewData)
    {
        return VBlock::renderForm($shortcode->name, $shortcode->subject, $shortcode->suffix);
    }

    // [div class="wrapper"] CONTENT [/div]
    public function div($shortcode, $content, $compiler, $name, $viewData)
    {
        return '<div '. $shortcode->get('class', 'default') .'>' . $content . '</div>';
    }

    //[image field="images" index="3" width="50%" height="50%" lightbox="true" picture="true" format="webp" crop="400,400" class="responsive" lightbox_class="image-gallery"]
    //[image url="/storage/images/file.jpg" width="50%" height="50%" lightbox="true" picture="true" format="webp" crop="400,400" class="responsive" lightbox_class="image-gallery"]
    public function image($shortcode, $content, $compiler, $name, $viewData)
    {
        $images = $viewData['page']->getMedia($shortcode->field?? 'images');
        $index = $shortcode->index? (int)$shortcode->index - 1 : 0;

        if ((count($images) > 0 && $index >= 0 && $index < count($images)) || $shortcode->url) {
            // Process images
            $image_origin = $image_src = $shortcode->url?? $images[$index]->getUrl();

            $class = $shortcode->class;
            $lightbox_class = $shortcode->lightbox_class;
            $width = $shortcode->width?? '100%';
            $height = $shortcode->height?? 'auto';
            $style = $shortcode->width || $shortcode->height? 'style="width: ' . $width . '; height: ' . $height . ';"' : '';
            $format  =  $shortcode->format?? null;
            $lazy  =  $shortcode->lazy?? null;
            $picture  =  $shortcode->picture?? null;

            // Crop if required
            if ($shortcode->crop) {
                $size = explode(',', $shortcode->crop);
                if (count($size) === 2 && is_numeric($size[0]) && is_numeric($size[0])) {
                    $image_format = $format? get_image_or_create($image_origin, $size[0], $size[1], $format) : null;
                    $image_src = get_image_or_create($image_origin, $size[0], $size[1]);
                }
            }

            $image_src  =  $shortcode->url?? $image_src;

            return view('voyager-site::shortcodes.image')->with([
                'image_origin' => $image_origin,
                'image_format' => $image_format,
                'image_src' => $image_src,
                'image_title' => $images[$index]->getCustomProperty('title'),
                'image_alt' => $images[$index]->getCustomProperty('alt'),
                'format' => $format,
                'picture' => $picture,
                'style' => $style,
                'lazy' => $lazy,
                'class' => $class,
                'lightbox_class' => $lightbox_class,
                'lightbox' => $shortcode->lightbox,
            ]);

        } else {
            return '';
        }
    }



}
