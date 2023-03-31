# Wordpress Starter Kit

![Current Branch](https://img.shields.io/badge/version-2.x.x-green?style=flat-square) [![Latest Version on Packagist](https://img.shields.io/packagist/v/piggly/wordpress-starter-kit.svg?style=flat-square)](https://packagist.org/packages/piggly/wordpress-starter-kit) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) ![PHP](https://img.shields.io/packagist/php-v/piggly/wordpress-starter-kit?style=flat-square)

The Wordpress Starter Kit was developed to be used at wordpress plugins development. It contains all basic classes to better organize code and aggregate all common operations.

The main goal is develop a boilerplate to **Piggly Lab**. But, it can be used to any other projects across web. The documentation is self-explanatory at code.

## Installation

### Composer

1. At you console, in your project folder, type `composer require piggly/wordpress-starter-kit`;
2. Don't forget to add Composer's autoload file at your code base `require_once('vendor/autoload.php);`.

### Manual install

1. Download or clone with repository with `git clone https://github.com/piggly-dev/wordpress-starter-kit.git`;
2. After, goes to `cd /path/to/piggly/wordpress-starter-kit`;
3. Install all Composer's dependencies with `composer install`;
4. Add project's autoload file at your code base `require_once('/path/to/piggly/wordpress-starter-kit/vendor/autoload.php);`.

## Dependencies

The library has the following external dependencies:

* PHP 7.2+.

## Changelog

See the [CHANGELOG](CHANGELOG.md) file for information about all code changes.

## Testing the code

This library uses the PHPUnit. We carry out tests of all the main classes of this application.

```bash
vendor/bin/phpunit
```

> You must always run tests with PHP 7.2 and greater.

## Contributions

See the [CONTRIBUTING](CONTRIBUTING.md) file for information before submitting your contribution.

## Credits

- [Caique Araujo](https://github.com/caiquearaujo)
- [All contributors](../../contributors)

## Support the project

Piggly Studio is an agency located in Rio de Janeiro, Brazil. If you like this library and want to support this job, be free to donate any value to BTC wallet `3DNssbspq7dURaVQH6yBoYwW3PhsNs8dnK` ❤.

## License

MIT License (MIT). See [LICENSE](LICENSE).