# Voyager Site

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

The package is an implementation of a simple but useful subsystem of a basic content organization for the [Voyager Admin Panel](https://github.com/the-control-group/voyager) package.
Using this package you can rapidly and easily build small and medium-size scale web-sites.   

## Features

- Basic Page management module, including systems pages.
- Basic SEO implementation - Seo title, Meta Description and Meta Keywords parameters.
- Breadcrumbs implementation.
- Flexible Block / Widget management module, to build parts of your site pages.
- Form management system, including AJAX sending.
- [Liquid template](https://packagist.org/packages/liquid/liquid) subsystem for blocks/widgets and forms.
- Page layout construction using flexible Blocks/Widgets. 
- [Shortcodes](https://github.com/webwizo/laravel-shortcodes) integrated.
- Localizations from DB table, where you can easily keep and manage your translations.
- Advanced site settings module, including mail smtp settings and a mail sending test out of the box.

## Installation

> #### Requirement
> You should fully install the package [Voyager Extension](https://github.com/MonstreX/voyager-extension) before.

Via Composer

``` bash
$ composer require monstrex/voyager-site
```

Then run install command:
``` bash
$ php artisan voyager-site:install
```

Publish config if you need:
```
$ php artisan vendor:publish --provider="MonstreX\VoyagerSite\VoyagerSiteServiceProvider" --tag="config"
```


## Usage

### Config file

- **route_home_page** - Route name for Home Page ('home' by default).
- **default_model_table** - Default model table name to find records ('pages' by default).
- **default_slug_field** - Default slug field name ('slug' by default).
- **use_legacy_error_handler** - If _false_ will be used voyager-site 404 error handler. 
- **template** - Root folder name where stored view template files ('template' by default).
- **template_master** - Master template name ('layouts.master' by default).
- **template_layout** - Main Layout Template name ('layouts.main' by default).
- **template_page** - Page template name ('pages.page' by default).
- **template_filters** - Class to add custom Liquid filters or Null. 

### Site Settings 

Site settings is a flexible and extendable easy to use settings subsystem integrated in the package. 

![Site settings](/docs/images/settings-1.png)

You can use and modify exist settings and group and add you own group and settings.
To retrieve certain setting you may use helper function:
```php
$mail = site_setting('mail.to_address');
```  
> Some of the site settings used by the internal package functions will override Laravel .env settings.

The settings have built-in SMTP mail parameters and the mail sending test function.
![Site settings - Mail](/docs/images/settings-mail.png)

You can easily modify settings or add new ones using JSON-like setting fields configuration by clicking on the Edit action.
```json
{
    "fields": {
        "to_address": {
            "label": "Default address for site emails",
            "type": "text",
            "value": "destination@example.com",
            "class": "col-md-12"
        },
        "section_smtp": {
            "type": "section",
            "icon": "voyager-mail",
            "label": "E-Mail transport"
        },
        "driver": {
            "label": "Mail driver",
            "type": "dropdown",
            "value": "smtp",
            "options": {
                "smtp": "SMTP",
                "mailgun": "MAILGUN",
                "log": "LOG"
            },
            "class": "col-md-12"
        }
    }
}
```
Built-in field types are

- text
- number
- textarea
- rich_text_box (tinyMCE editor)
- code_editor (ACE Code Editor)
- checkbox
- radio
- image (voyager type image) 
- media (image file, using laravel medialibrary package)
- route (returns URL using route name)
- section (don't has value, used just for visual separation of field groups)  
 
 
### Localizations

The package provides DB-like localization storage for all strings you need to localize. 
Just add language columns to the storage and it'll be used instead a localization in files.  

![Localizations](/docs/images/localizations.png) 


### Pages

The VPage module allows you manage basic pages on your site. 
You can easily find and prepare any page and its related attributes - like SEO data, breadcrumbs and data sources and then send this data collection to a view.
It can be used by two ways: implementing the **PageTrait** class into your controller or using the facade **VPage**.
Look for the examples:

Example #1:
```php
namespace App\Http\Controllers;

use MonstreX\VoyagerSite\Traits\PageTrait;

class PagesController extends Controller
{
    use PageTrait;

    public function home()
    {

        $this->create('home');

        return $this->view();

    }
    
    public function show($alias)
    {

        $this->create($alias);

        return $this->view();

    }
    
    public function showArticle($alias)
    {

        $this->create($alias,'articles');

        return $this->view('layout.article', ['vars' => $some_vars]);

    }
}
```

Example #2:
```php
namespace App\Http\Controllers;

use MonstreX\VoyagerSite\Traits\PageTrait;

use VSite, VPage, VData;

class PagesController extends Controller
{
    public function home()
    {

        VPage::create(VData::find('home'), VSite::getSettings());

        return VPage::view(); 

    }
}
```

What the example does:

- Finds in default (or given) model the certain record (using 'slug' field by default).
If you use (int) value then it'll be found by ID field. If a record is not found or the record has empty (null) **status** field then it'll throw an exception 404.
- Creates a VPage instance and initialize the instance with a Page Data: 
  - Founded model object.
  - Site settings.
  - Initialized breadcrumbs.
  - Initialized SEO parameters (seo title, meta description, meta keywords).
  - Attached data sources, if **details** field has data sources descriptions.
- Renders page using default (or given) View.      


Available variables in the View:

```php
$template            // Root template folder 
$template_master     // Master template name (to extend View)
$template_page       // Internal page template name
$breadcrumbs         // Breadcrumbs array
$title               // Page title
$banner              // Page banner image (if present)
$page                // Page instance
$parents             // Chain of parents of current page  
$children            // Children pages if present for current page
$data_sources        // Extra Data sources attached to the Page
$seo['title']        // Seo Title
$seo['description']  // Meta Description
$seo['keywords']     // Meta Keywords
$data                // Additional given data
```

### Page Data Sets

It is a subsystem to retrieve additional data necessary to use on a certain page. 
The data are different record collections from different models.  
To use it you need having **details** field in your model, the record of that filled up with a certain JSON data structure:

> Note: the attached data defined in JSON structure will load automatically during a call the **create** method. 

```json
{
    "data_sources": {
        "articles": {
            "model": "Article",
            "where": {
              "status": 1
            }
        },
        "services": {
            "model": "Service",
            "where": {
                "status": 1,
                "featured": 0,
                "type": "main"
            },
            "with": [
              "category",
              "country"
            ],
            "order" : {
                "field" : "order",
                "direction" : "asc"
            }                 
        }
    }
}
``` 
And in your View template you can easily use it like this:
```blade
@foreach($data_sources['articles'] as $article)
    <h2>{{ $article['title'] }}</h2>
@endforeach
```

> Note: this type of the JSON data structure also uses in Block/Widget subsystems.


### Override default templates on the page

You may want to override default template names on a certain page. 
To do this define new template names in **details** field:
```json
{
  "template": "my-theme",
  "template_master": "layout.master",
  "template_layout": "layout.main",
  "template_page": "pages.contacts"
}
```

Overriding in controllers (using page trait):
```php
public function service($alias)
{
    $pageContent = $this->create($alias,'services')->getContent();
    $this->setPageTemplate('pages.service');
    $this->buildBreadcrumbs();
    $this->setChildren('parent_id');
    return $this->view();
}
``` 

### Page SEO parameters

SEO parameters are generated inside the **create** Page method. By default:

**SEO Title** - from the page seo fields group if an appropriate field is not empty, 
otherwise checks field **Title**,  if the field is not present then  checks a 
site setting **seo.seo_title**, if the setting is empty then takes value from 
site setting **general.site_title**. And at last the given value passed through seo title template site setting -
**seo.seo_title_template**. 
> You can override the **SEO Title** value with method **VPage::setSeoTitle('new title')**.

**META Description** - from the page seo fields group if an appropriate field is not empty, otherwise checks a 
site setting **seo.meta_description**, if the setting is empty then takes value from site setting **general.site_description**.
> You can override the **META Description** value with method **VPage::setMetaDescription('new description')**.

**META Keywords** - from the page seo fields group if an appropriate field is not empty, otherwise checks a 
site setting **seo.meta_keywords**.
> You can override the **META Keywords** value with method **VPage::setMetaKeywords('new keywords')**.


### Page Breadcrumbs

When you call the Page **create** method it adds the first element to the **Breadcrumbs** array.

How sets the first breadcrumb element:
```php
addBreadcrumbs(__('site.breadcrumb_home'), route(config('voyager-site.route_home_page')));
``` 
To add other elements to the **Breadcrumbs** array just use the method **VPage::addBreadcrumbs($title, $path)** 

Also you can use the method **buildBreadcrumbs()** to build a chain of nested pages which hold in the Parents property.
This property sets automatically when you load and create page instance using the method **setParents($page, $parent_field_name)**
Where $page is a current page instance and $parent_field_name is a field name where holds ID with a parent of the current page. 

> Note: additional necessary setup for models - to build **parents chain**, **breadcrumbs** and get **banner**
```php
// Example (Additional Model properties)
public $masterPageRouteName = 'page';
public $masterPageSlug = 'pages';
public $masterPageId = 2;
public $pageRouteName = 'service';
public $bannerField = 'banner_image';
```

### Blocks / Widgets

Blocks / Widgets is a flexible subsystem provides your a way to implement and organize additional content parts and include them into your page.  
Each block has the follow structure:

**Status** - If disabled the block will be ignored.

**Title** - Visible only in admin panel.

**Block key** - A key (slug) using to find and render this block.

**Region position** - Used for grouping blocks and then render them as a group in certain order. 
Available positions can be edited in the **Regions** menu.

**Block content** - Main block content. You can use as pure HTML code or as a [Liquid template](https://packagist.org/packages/liquid/liquid).

Available variables:
- **this.images** - holds images items listed bellow. 
- **this.field_name** - holds additional fields values, where field_name is additional field name in the block table.
- **data** - Data sources if defined in the **Options** field.

Example #1 (usage of image list):
```html
<!-- Block liquid template -->
<h3>My images</h3>
<div class="images-list">
{% for image in this.images %}
  <img src="{{ image.url | crop: 300,200 | url }}" alt=""/>
{% endfor %}
</div>
```
Where **image.url** represent relative URL of image. **Crop** filter - crop and store (if not stored yet) the image with given sizes.
-**url** filter - makes full url from the relative one. 
**image.full_url** - Full url of the image.
**image.props.*** - Custom image properties.

Example #2 (usage data sources):
```html
<!-- Block liquid template -->
<h3>Articles Block</h3>
<ul>
{% for article in data.articles %}
  <li>{{ article.title }}</li>
{% endfor %}
</ul>
```
For this kind of template you need to define **data sources** JSON-like structure in the **options** field:
```json
{
    "data_sources": {
        "articles": {
            "model": "Article",
            "where": {
                "status": 1,
                "news": 1
            },
            "order" : {
                "field" : "order",
                "direction" : "asc"
            }                 
        }
    }
}
```
Where "model" is a model name equal to registered model name in the Voyager Bread system. 

Example #3 (usage internal block's fields):
```html
<!-- Block liquid template -->
{{ this.title }} <!-- Get TITLE block field -->
{{ this.images[0].url }} <!-- Get Image URL with index 0 -->
{{ this.images[0].props.title }} <!-- Get Image custom property -->
{{ this.additional_field }} <!-- Get any additional block field value -->
{{ options.data_section }} <!-- Get JSON parameter stored in the parameters field -->

```

Also you can get any field of the certain block using helper:
```blade
<h1>{{ get_block_field('top-line', 'title') }}</h1>
``` 


> Block render

Just use helper render_block('key'). Instead of key you can use block Title or ID (numeric).

```blade
{!! render_block('top-line') !!}
{!! render_block('Our services') !!}
{!! render_block(3) !!}
```

> Region (group) render and URL path rules.

It can be used to organize and render a group of blocks whit the same region (group) name whenever you need it in your template.
For instance:
```blade
{!! render_region('content-before') !!}
```

Will render all blocks where this region (group) 'content-before' has been set.
And the package will check all blocks if they can be rendered - what depends on the setting "URL paths Rules":

![URL path rules](/docs/images/regions-path-rules.png) 

It's a drupal-like block system rendering. That can depend on the current URL or not.


### Forms 

Similar to block. But you can use only limited sets of internal variables. 
The 'send.form' is implemented inside the form subsystem with the sendForm($request) method.
It sends any form data including attached files. Also you can use Google ReCaptcha 2 form protection.
Just add the line where you need inside your form:

```blade
<div class="g-recaptcha" data-sitekey="{{ 'general.site_captcha_site_key' | site_setting }}"></div>
```
And add recaptcha validation rules to the form options:
```json
{
    "validator": {
        "g-recaptcha-response": "required|recaptcha"
    }
}
```

Template part:
```html
<form class="form{{ form_alias }}" action="{{ 'send.form' | route }}" method="post" enctype="multipart/form-data">    

    <input type="hidden" name="_token" value="{{ csrf_token }}">
    <input type="hidden" name="_form_alias" value="{{ form_alias }}">
    <input type="hidden" name="subject" value="Send us a message!">
    
    <input class="form-control" type="text" name="name" value="" required>
    <input class="form-control" type="email" name="email" value="" required>
    <input class="form-control phone" type="text" name="phone" value="" required>
    <textarea class="form-control" name="message" required=""></textarea>
    
    <div class="g-recaptcha" data-sitekey="{{ 'site_setting' | func: 'general.site_captcha_site_key'   }}"></div>

    <button type="submit" class="btn-send-form ">Send</button>
    
</form>
```

Parameters part:
```json
{
    "to_address": "info@site.com,support@site.com",    
    "validator": {
        "name":"required",
        "email":"required",
        "phone":"required",
        "g-recaptcha-response": "required|recaptcha"
    },
    "messages": {
        "name.required": "The field NAME shouldn't be empty.",
        "email.required": "The field EMAIL shouldn't be empty.",
        "phone.required": "The field PHONE shouldn't be empty."
    }
}
```

If parameter 'to_address' is not present will be used config setting: mail.to_address.


Inside form template you can use internal vars:
```html
{{ old }} <!-- Previous values saved in session = session()->getOldInput() -->
{{ errors_messages}} <!-- = $errors->all() -->
{{ errors }} <!-- = $errors->toArray() -->
{{ form_alias }} <!-- Form Key -->
{{ form_suffix }} <!-- Additional form suffix if you need -->
{{ form_subject }}} <!-- Form Subject -->
{{ csrf_token }} <!-- = csrf_token() -->

```

To render form from a view use helper renderForm($key, $subject = null, $suffix = null):
```blade
{!! render_form('callback-form', 'Send us a message!', '-second-form') !!}
```

To render inside a block:
```html
{{ 'callback-form' | form: 'Send us a message!','-second-form' }}
```

More examples:
```blade
<div id="{{ form_alias }}-{{ form_suffix }}" class="default-form contact-form">
    <form data-holder-id="{{ form_alias }}-{{ form_suffix }}" class="{{ form_alias }}" action="{{ "send.form" | route }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token }}">
        <input type="hidden" name="_form_alias" value="{{ form_alias }}">
        <input type="hidden" name="subject" value="Callback form">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Your name" value="{{ old.name }}">
            {% if errors.name %}
              <span class="help-block">
                {% for error in errors.name %}
                  <div>{{ error }}</div>
                {% endfor %}
              </span>
            {% endif %}            
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Your phone number" value="{{ old.phone }}">
            {% if errors.phone %}
              <span class="help-block">
                {% for error in errors.phone %}
                  <div>{{ error }}</div>
                {% endfor %}
              </span>
            {% endif %}                        
        </div>
        <div class="form-group">
            <label for="email">E-Mail address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Your E-Mail address" value="{{ old.email }}">
            {% if errors.email %}
              <span class="help-block">
                {% for error in errors.email %}
                  <div>{{ error }}</div>
                {% endfor %}
              </span>
            {% endif %}                        
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" placeholder="Your message">{{ old.message }}</textarea>
            {% if errors.message %}
              <span class="help-block">
                {% for error in errors.message %}
                  <div>{{ error }}</div>
                {% endfor %}
              </span>
            {% endif %}                        
        </div>
        <div class="form-group form-group-default">
            <label>Attachment #1</label>
            <input type="file" name="images[]" accept="file_extension|image/*|media_type" multiple>
        </div>
        <div class="g-recaptcha" data-sitekey="{{ 'site_setting' | func: 'general.site_captcha_site_key'   }}"></div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>

<h2>Form Messages</h2>
{% for message in errors_messages %}
  <div>{{ message }}</div>
{% endfor %}
```

Also you can use AJAX form sending. The package implements JS part for AJAX sending, just include jQuery library and this line in your view (or you can use your own JS function):

```blade
@include('voyager-site::mail.send_form_ajax_js')
```

AJAX Example:
```blade
<div id="{{ form_alias }}-{{ form_suffix }}" class="default-form contact-form">
    <form data-holder-id="{{ form_alias }}-{{ form_suffix }}" class="{{ form_alias }}" action="{{ "send.form" | route }}" method="post" enctype="multipart/form-data" onsubmit="formSendAJAX(event, this)">
        <input type="hidden" name="_token" value="{{ csrf_token }}">
        <input type="hidden" name="_form_alias" value="{{ form_alias }}">
        <input type="hidden" name="subject" value="Callback form AJAX">
        <div class="row clearfix">
            <div class="form-group">
                <input type="text" name="name" placeholder="Ваше имя" value="">
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Ваш Email адрес"  value="">
            </div>
            <div class="form-group">
                <input class="phone" type="text" name="phone" placeholder="Ваш телефон"  value="">
            </div>
            <div class="form-group">
                <textarea rows="7" name="message" placeholder="Ваше сообщение"></textarea>
            </div>
            <div class="form-group form-group-default">
                <label>Attachment</label>
                <input type="file" name="images[]" accept="file_extension|image/*|media_type" multiple>
            </div>
            <div class="col-sm-12">
                <div class="g-recaptcha" data-sitekey="{{ 'general.site_captcha_site_key' | site_setting }}"></div>
            </div>                          
            <div class="form-button">
                <button type="submit" class="btn btn-primary btn-send-form">
                    <span class="btn-loader hidden">...loading icon...</span>
                    <span class="btn-title-normal">Send Form</span>
                    <span class="btn-title-sending hidden">Sending...</span>
                </button>
            </div>
        </div>
    </form>
    <div class="form-message"></div>
</div>
```

### Page layout render 

The special system allows you to manage group of the blocks or forms and fields on a certain page.
You can easily add/remove blocks, forms and fields (and sort them) in the edit page mode and then render using helper:
```blade
{{ render_layout($page->layout, $page) }}
``` 
Where $page is a page instance.

Or manually:
```blade
@if ($layoutFields = json_decode($page->layout))
    @if ($layoutFields)
        @foreach($layoutFields as $field)
            @if($field->type === 'Block')
                {!! render_block($field->key) !!}
            @elseif ($field->type === 'Form')
                {!! render_form($field->key) !!}
            @elseif ($field->type === 'Field')
                <div class="page-content">
                    {!! $page->{$field->key} !!}
                </div>
            @endif
        @endforeach
    @endif
@endif
```

## Built-in Shortcodes

**block** - Render a block. Ex: [block name="top-line"]  

**form** - Render a form. Ex: [form name="top-line" subject="Form subject" suffix="-callback-form"]  

**div** - Render div wrapper. Nested elements not allowed. Ex: [div class="wrapper"] CONTENT [/div]   

**image** - Render html code an image (and crop, convert an image) using media files attached to the current content instance.

Available options:  
**field** - Media file field related with attached media files   
**index** - Index of the file in the collection, begins with 1.  
**url** - Path to the image (if not used field and index)  
**class** - Classes for the image tag. Ex: class="lazy responsive"    
**lightbox** - Make a lightbox wrapper. Ex: lightbox="true"    
**lightbox_class** - Classes for the lightbox wrapper  
**width** - Width attribute fot the image tag.  
**height** - Height attribute fot the image tag.  
**picture** - Add picture wrapper.    
**crop** - Crop the image.  
**format** - Convert the image to webp, jpg, png. 

Examples:  
[image field="image" index="1"]  
[image url="/images/banners/banner-1.jpg"]  
[image field="images" index="3" picture="true" format="webp" width="100" height="200" crop="400,400" class="lazy-class" lazy="true" lightbox_class="image-gallery"]  


## Built-in Liquid filters

**url** - Return full url for a given path. Ex: {{ '/images/file.jpg' | url }}      
**route** - Return laravel route path. Ex: {{ 'page' | route: 'slug' }}  
**crop** - Crop (and/or convert) image. Ex: {{ this.images[0].url | crop: 300,300,'webp',75 }}  
**webp** - Convert an image into webp format. Ex: {{ this.images[0].url | webp }}  
**site_setting** - Get site settings parameters. Ex: {{ 'mail' | site_setting: 'to_address' }}
**menu** - Render a menu. Ex: {{ 'main-menu' | menu: 'template.menus.main' }}  
**block** - Render a block. Ex: {{ 'top-line' | block }}  
**form** - Render a form. Ex: {{ 'callback-form' | form: 'Form subject','-callback' }}    
**trans** - Translate string using current locale. Ex: {{ '[[en]]English string[[ru]]Русская строка' | trans }}   
**lang** -- Translate string using language files.  Ex: {{ 'auth.password' | lang }}  
**view** -- Render blade view using Data vars. Ex: {{ data | view: 'template.partials.portfolio-list'  }}     
**service** -- Get data using service method (and pass it to the View). Ex: {{ 'getPortfolio' | service: '\App\Services\DataService'  | view: 'template.partials.portfolio-list' }}     

## Custom Liquid filters

You can add your own liquid filters using a custom class. You should add this class to the configuration:  
**'template_filters' => 'App\\Template\\TemplateFilters'**

```php
<?php

namespace App\Template;

class TemplateFilters
{
    public function handle($instance, $content)
    {
        $instance->registerFilter('div', function ($arg) {
            return '<div>' . $arg . '</div';
        });
    }
}
```



## Helpers

**translit_cyrillic($string)** - Transliteration given cyrillic string into latin symbols string.  
**get_file($file_path)** - Return URL of the given file_path, can unpack JSON voyager file coded format.  
**store_post_files(Request $request, $slug, $field, $public = 'public')** - Store files given in $request $field and return JSON Voyager array of files links and their original names.  
**generate_filename($file, $path)** - Return new file name if $file exists on given $path.  
**site_setting($key, $default = null)** - Return a site setting (or default value).  
**site_settings_group($group_key)** - Return a group of site settings.  
**get_image_or_create($image_path, $width, $height, $format, $quality)** - Return link to the requested image with a given width and/or height, format and quality. If it not exists it'll be created.  

Where:   
**$image_path** - Full URL with domain or relative (to domain).  
**$width** - new image width or Null,   
**$height** - new image height or Null (if used both - width and height - the image will be cropped),   
**$format** - 'webp', 'png', 'jpg': the image will be converted to webp, png, jpg,   
**$quality** - quality level of the new image.   

**get_first_not_empty(array $values)** - Returns the first not empty element in a given array.   
**render_block($key)** - Render block.  
**render_region($key, $path = null)** - Render region (group of blocks with the same region/group key).  
**render_form($key, $subject = null, $suffix = null)** - Render form with a given key. Accept also Subject field and Suffix for using in a form.  
**render_layout($layout, $page)** - Render Page Layout (page group of blocks, forms and page fields).    
**get_block_field($block_key, $field_name)** - Return field value of a given block key.  
    


## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/monstrex/testpackage.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/monstrex/testpackage.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/monstrex/testpackage/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/monstrex/testpackage
[link-downloads]: https://packagist.org/packages/monstrex/testpackage
[link-travis]: https://travis-ci.org/monstrex/testpackage
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/monstrex
[link-contributors]: ../../contributors
