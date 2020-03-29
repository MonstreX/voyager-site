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
- Flexible Block / Widget management module, to build parts of your pages.
- Page layout construction using flexible Blocks/Widgets. 
- Form management system, including AJAX sending.
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

To be published.

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

Not implemented yet

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

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
