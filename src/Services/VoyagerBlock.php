<?php


namespace MonstreX\VoyagerSite\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

use TCG\Voyager\Facades\Voyager;
use MonstreX\VoyagerSite\Contracts\VoyagerBlock as VoyagerBlockContract;
use MonstreX\VoyagerSite\Models\Block;
use MonstreX\VoyagerSite\Models\Form;
use MonstreX\VoyagerSite\Models\BlockRegion;
use MonstreX\VoyagerSite\Templates\Template;
use VSite;
use VData;
use Shortcode;

class VoyagerBlock implements VoyagerBlockContract
{

    protected CONST EXCEPT = 0;
    protected CONST ONLY = 1;

    public function renderRegion($region_name, $path = null)
    {
        $region = BlockRegion::where('key', $region_name)->first();
        if ($region) {
            $blocks = Block::where(['region_id' => $region->id, 'status' => 1])->get();

            $current_path = $path ? $path : VSite::currentPath();

            $html = '';
            foreach ($blocks as $block) {

                $blockShow = false;

                $urls = explode(PHP_EOL, str_replace('<front>', '/', $block->urls));
                foreach ($urls as $key => $url) {
                    if (empty($url)) {
                        unset($urls[$key]);
                    }
                }

                // Set visibility ON EVERY PAGE
                if ($block->rules === self::EXCEPT && empty($urls)) {
                    $blockShow = true;
                    // Set visibility ON EVERY PAGE EXCEPT SELECTED
                } elseif ($block->rules == self::EXCEPT) {
                    $blockShow = in_array($current_path, $urls);
                    // Set visibility ONLY ON SPECIFIC PAGES
                } elseif ($block->rules == self::ONLY && !empty($urls)) {
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
        if ($block) {
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

            // Load related Media Data if needed and convert it to an array
            // It is necessary Only for accessing in Liquid templates
            $dataType = Voyager::model('DataType')->where('slug', '=', 'blocks')->first();
            $rows = $dataType->rows()->get();

            $fields_data = [];
            if ($rows) {
                foreach ($rows as $row) {
                    // Check for only Media library types
                    if ($row->type === 'adv_image' || $row->type === 'adv_media_files') {

                        $images = $block->getMedia($row->field);

                        foreach ($images as $key => $image) {
                            $fields_data[$row->field][$key]['url'] = $image->getUrl();
                            $fields_data[$row->field][$key]['full_url'] = $image->getFullUrl();
                            $fields_data[$row->field][$key]['path'] = $image->getPath();
                            $fields_data[$row->field][$key]['props'] = $image->toArray()['custom_properties'];
                        }

                    } else {
                        $fields_data[$row->field] = $block->{$row->field};
                    }
                }
            }

            $template = new Template(Shortcode::compile($block->content));

            return $template->render(['images' => $images, 'data' => $data, 'this' => $fields_data, 'options' => $details]);
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




    public function renderForm($key, $subject = null, $suffix = null)
    {
        $form = $this->getFormByKey($key);
        if ($form) {
            $errors = Session::get('errors', new MessageBag);

            $vars = [];
            $vars['old'] = session()->getOldInput();
            $vars['errors_messages'] = $errors->all();
            $vars['errors'] = $errors->toArray();
            $vars['form_alias'] = $key;
            $vars['form_suffix'] = $suffix;
            $vars['form_subject'] = $subject;
            $vars['csrf_token'] = csrf_token();

            $template = new Template(Shortcode::compile($form->content));
            return $template->render($vars);
        } else {
            return '';
        }
    }

    public function getFormByKey($key)
    {
        return Form::where(['key' => $key, 'status' => 1])->first();
    }


    public function renderLayout($layout, $page)
    {
        $layoutFields = json_decode($layout);
        if($layoutFields) {
            $html = "";
            foreach ($layoutFields as $field) {
                if ($field->type === 'Block') {
                    $html .= $this->render($field->key);
                } elseif ($field->type === 'Form') {
                    $html .= $this->renderForm($field->key);
                } elseif ($field->type === 'Field') {
                    $html .= $page->{$field->key};
                }
            }
            return $html;
        } else {
            return "";
        }
    }

}