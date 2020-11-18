# Artisan command collection for user management

![Packagist Version](https://img.shields.io/packagist/v/marcandreappel/laravel-artisan-users?logo=composer&style=for-the-badge)
![Travis build status](https://img.shields.io/travis/marcandreappel/laravel-artisan-users?logo=travis-ci&logoColor=%23fff&style=for-the-badge)
![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/marcandreappel/laravel-artisan-users/main?logo=scrutinizer&style=for-the-badge)
![Scrutinizer coverage](https://img.shields.io/scrutinizer/coverage/g/marcandreappel/laravel-artisan-users/main?logo=scrutinizer&style=for-the-badge)
![Packagist Downloads](https://img.shields.io/packagist/dt/marcandreappel/laravel-artisan-users?logo=laravel&logoColor=%23fff&style=for-the-badge)

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
