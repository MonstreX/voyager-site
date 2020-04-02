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

### Site Settings 

Site settings is a flexible and extendable easy to use settings subsystem integrated in the package. 

![Site settings](/docs/images/settings-1.png)

You can use and modify exist settings and group and add you own group and settings.
To retrieve certain setting you may use helper function:
```php
$mail = site_setting('mail.to_address');
```  
> Some of the site settings used by internal package functions and override Laravel .env settings.

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
                "mailgun": "MAILGUN"
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
$template_master     // Master template name (to extend View)
$template_page       // Internal page template name
$breadcrumbs         // Breadcrumbs array
$title               // Page title
$page                // Page record 
$page_data_sets      // Data sources attached to the Page
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
@foreach($page_data_sets['articles'] as $article)
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
 

### Page SEO parameters

SEO parameters are generated inside the **create** Page method. By default:

**SEO Title** - from the page seo fields group if an appropriate field is not empty, 
otherwise checks field **Title**,  if the field is not present then  checks a 
site setting **seo.seo_title**, if the setting is empty then takes value from 
site setting **general.site_title**. And at last the given value passed through seo title template -
**seo.seo_title_template**. You can override the **SEO Title** value with method **VPage::setSeoTitle('new title')**.






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
