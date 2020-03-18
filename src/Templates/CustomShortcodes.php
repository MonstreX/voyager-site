<?php


namespace MonstreX\VoyagerSite\Templates;

use VBlock;

class CustomShortcodes
{

    public function block($shortcode, $content, $compiler, $name, $viewData)
    {
        if($shortcode->name) {
            return VBlock::render($shortcode->name);
        } elseif ($shortcode->title) {
            return VBlock::render($shortcode->title);
        }
        return '';
    }

    public function form($shortcode, $content, $compiler, $name, $viewData)
    {
        if($shortcode->name && $shortcode->id) {
            return VBlock::renderForm($shortcode->name, $shortcode->id);
        }
        if($shortcode->name) {
            return VBlock::renderForm($shortcode->name);
        }
    }

}
