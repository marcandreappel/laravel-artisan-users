# Artisan command collection for user management

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-artisan-users/laravel-artisan-users.svg?style=for-the-badge&logo=composer)](https://packagist.org/packages/marcandreappel/laravel-artisan-users)
[![Build Status](https://img.shields.io/travis/laravel-artisan-users/laravel-artisan-users/master.svg?style=for-the-badge&logo=travis)](https://travis-ci.org/marcandreappel/laravel-artisan-users)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-artisan-users/laravel-artisan-users.svg?style=for-the-badge&logo=scrutinizerci)](https://scrutinizer-ci.com/g/marcandreappel/laravel-artisan-users)

<!--[![Total Downloads](https://img.shields.io/packagist/dt/laravel-artisan-users/laravel-artisan-users.svg?style=flat-square)](https://packagist.org/packages/marcandreappel/laravel-artisan-users)-->

A collection of Laravel `artisan` commands to manage users on the cli.

## Installation

You can install the package via composer:

```bash
composer require marcandreappel/laravel-artisan-users
```

## Usage

First publish the config and adjust your User model. If you want to use the simple role system, see below. 

```bash
artisan publish vendor:publish --provider="MarcAndreAppel\ArtisanUsers\ArtisanUsersServiceProvider" --tag="config"
```

Then you're good to go for the first command, just answer the questions:

```sh
artisan user:add
```

### Roles

The role system is very opinionated and rudimentary. To use it add a migration against your users table and add the `string` type column `role`. Then set the config fir `with_roles` to true.

You can then trigger the roles question with the `--role` or `-t` flag after the command.


### Testing

The test can be run with:

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<small>Some parts are from the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com) generator.</small>
