# Provide a basic API for User Management

Basic Rest API for User Management. Build the UI with React/Angular/Vue to consume/use the api.

## Installation

You can install the package via composer:

```bash
composer require AfzalH/UserApi
```

## Development Installation

Enter your laravel root directory. Then...

```bash
mkdir afzalh && cd afzalh
git clone https://github.com/AfzalH/user-api.git
```
edit `composer.json` (from laravel root) and add

```
"AfzalH\\UserApi\\": "afzalh/user-api/src"
```
inside `autoload -> psr-4` block

and add

```
"AfzalH\\UserApi\\Tests\\": "afzalh/user-api/tests/"
```
inside `autoload-dev -> psr-4` block

edit `phpunit.xml` (from laravel root) and add

```
<directory suffix="Test.php">./afzalh/user-api/tests</directory>
```
inside `<testsuite name="Feature">` block

edit `config/app.php` and add

```
AfzalH\UserApi\UserApiServiceProvider::class,
```
inside `providers` array

and

```
'UserApi' => AfzalH\UserApi\UserApiFacade::class,
```
inside `aliases` array

and finally
```
composer dump-autoload
```
## Usage

TBD

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Afzal Hossain](https://github.com/AfzalH)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
