# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/:package_name.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)
[![Build Status](https://travis-ci.org/MyMediaMagnet/multi-tenant-laravel.svg?branch=master)](https://travis-ci.org/MyMediaMagnet/multi-tenant-laravel)
[![Quality Score](https://img.shields.io/scrutinizer/g/MyMediaMagnet/multi-tenant-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/MyMediaMagnet/multi-tenant-laravel)
[![Coverage Status](https://coveralls.io/repos/github/MyMediaMagnet/multi-tenant-laravel/badge.svg?branch=master)](https://coveralls.io/github/MyMediaMagnet/multi-tenant-laravel?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/:package_name.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)

This package is intended to be a flexible starting point for new multi-tenant projects in Laravel.  Still a WIP

## Installation

You can install the package via composer:

```bash
composer require mymediamagnet/multi-tenant-laravel
```

## Setup

* Make sure all Auth routes are removed from your routes to start off
* Run `php artisan vendor:publish` and selecting multi-tenant-laravel as the package
* Run `php artisan migrate`.  This will create all the tables required in this package
* In the file `app\Http\Middleware\RedirectIfAuthenticated` you might see `return redirect('/home');`.  Change this to `return redirect('/');`.

## Setting Up Your Models

Create the following models for the primary tables in the packages migrations.  Make sure to extend the base classes from the package

* Role
* Permission
* Tenant
* Feature
* User (likely already created)

``` php
use MultiTenantLaravel\App\Models\BaseUser;

class User extends BaseUser
{
    ...
}
```

``` php
use MultiTenantLaravel\App\Models\BaseTenant;

class Tenant extends BaseTenant
{
    ...
}
```

Follow the same pattern in making your Role, Permission & Feature models

## Usage

By default your project should now be setup to automatically redirect you to the login page on visit.  To create a new user, you can use the helper command.

`php artisan tenant:create-user` - you will be asked a series of questions to setup your new user, or you can quickly create a fake user using `php artisan tenant:create-user --fake`

To create a new tenant: 

`php artisan tenant:create-tenant` - you will be asked a series of questions to setup your new tenant, or you can quickly create a fake tenant using `php artisan tenant:create-tenant --fake`

When creating a new tenant, you will be provided the option to add an existing user to the tenant, or create a new user.

Once you login, if your user is only a member of 1 tenant you will be immediatly redirected to that tenants dashboard.  Otherwise, you'll land on a dashboard that allows you to select which dashboard you would like to manage

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email troy@mymediamagnet.com instead of using the issue tracker.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
