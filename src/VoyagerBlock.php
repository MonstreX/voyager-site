<?php


namespace MonstreX\VoyagerSite;

use MonstreX\VoyagerSite\Models\Block;
use MonstreX\VoyagerSite\Models\Form;
use MonstreX\VoyagerSite\Models\BlockRegion;
use MonstreX\VoyagerSite\Templates\Template;
use VSite;
use VData;
use Shortcode;

class VoyagerBlock
{

    protected CONST EXCEPT = 0;
    protected CONST ONLY = 1;

    public function renderRegion($region_name, $path = null)
    {
        $region = BlockRegion::where('key', $region_name)->first();
        if ($region) {
            $blocks = Block::where(['region_id' => $region->id, 'status' => 1])->get();

            $current_path =  $path? $path: VSite::currentPath();

            $html = '';
            foreach ($blocks as $block) {

                $blockShow = false;

                $urls = explode(PHP_EOL, str_replace('<front>','/', $block->urls));
                foreach ($urls as $key => $url) {
                    if(empty($url)) {
                        unset($urls[$key]);
                    }
                }

                // Set visibility ON EVERY PAGE
                if($block->rules === self::EXCEPT && empty($urls)) {
                    $blockShow = true;
                    // Set visibility ON EVERY PAGE EXCEPT SELECTED
                } elseif ($block->rules == self::EXCEPT) {
                    $blockShow = in_array($current_path, $urls);
                    // Set visibility ONLY ON SPECIFIC PAGES
                } elseif ($block->rules == self::ONLY && !empty($urls)){
                    $blockShow = in_array($current_path, $urls);
                }

                if ($blockShow) {
                    $html .= $this->renderBlock($block);
                }
            }

            return $html;

        } else {
            return '';
        }
    }

    public function render($key)
    {
        $block = $this->getByKey($key);
        if (!$block) {
            $block = $this->getByTitle($key);
        }
        return $this->renderBlock($block);
    }

    public function renderBlock($block)
    {
        if($block) {
            // Prepare Images Vars
            $images = [];
            foreach ($block->getMedia('images') as $key => $image) {
                $images[] = [
                    'url' => $image->getUrl(),
                    'full_url' => $image->getFullUrl(),
                    'props' => $image->toArray()['custom_properties'],
                ];
            }
            // Prepare Data Sources Vars if present
            $data = [];
            $details = json_decode($block->details);
            if ($details && isset($details->data_sources)) {
                $data = VData::getDataSources($details->data_sources);
            }

            $template = new Template(Shortcode::compile($block->content));
            return $template->render(['images' => $images, 'data' => $data]);
        } else {
            return '';
        }
    }

    public function getByKey($key)
    {
        $block = Block::where(['key' => $key, 'status' => 1])->first();
        return $block;
    }

    public function getByTitle($title)
    {
        $block = Block::where(['title' => trim($title), 'status' => 1])->first();
        return $block;
    }

    public function renderForm($key, $suffix = '')
    {
        $form = Form::where(['key' => $key, 'status' => 1])->first();
        if($form) {

            $options = json_decode($form->details);
            $vars = [];
            // Prepare Vars for template
            $vars['form_alias'] = $key . $suffix;
            $vars['form_route'] = route('form.send');
            $vars['csrf_token'] = csrf_token();
            $vars['validator'] = isset($options->validator)? htmlentities(serialize($options->validator)) : '';

            $template = new Template(Shortcode::compile($form->content));
            return $template->render($vars);
        } else {
            return '';
        }
    }


}