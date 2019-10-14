# Filesystem

[![Travis Build Status][icon-status]][link-status]
[![Packagist Downloads][icon-downloads]][link-downloads]
[![License][icon-license]](LICENSE.md)

Utilities for working with the filesystem in PHP.

- [Install](#install)
- [Usage](#usage)
  - [Each](#each)
  - [Inject](#inject)
  - [Dump](#dump)
  - [getContents](#getcontents)
- [Static Access](#static-access)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Install

You may install this package using [composer][link-composer].

```shell
$ composer require bhittani/filesystem --prefer-dist
```

## Usage

This packages offers some helpful utilities when working with the filesystem. It extends symfony/filesystem.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$fs = new \Bhittani\Filesystem\Filesystem;

// Use the API calls as demonstrated below.
```

### Each

Traverse over all the files (recursive) in a directory.

```php
echo $fs->each('/path/to/a/directory', function (\SplFileInfo $splFileInfo) {
    // Do something...
});
```

### Inject

Inject a payload into a file or every file in a directory.

```php
echo $fs->inject('/path/to/a/file/or/directory', [
    'foo' => 'bar',
]);
```

> This will lazily find `[foo]` in each file contents and replace it with `bar`.

> A callback is also accepted as the payload which will receive the path to the current file.

### Dump

Dump a file or directory with an optional payload.

```php
echo $fs->dump('/path/to/a/dest/directory', '/path/to/a/src/directory', [
    'foo' => 'bar',
]);
```

> If a callback is provided as the payload, it will receive the path to the current destination file.

### getContents

Get the contents of a file with an optional payload.

```php
echo $fs->getContents('/path/to/file', [
    'foo' => 'bar',
]);
```

## Static Access

A `StaticFilesystem` class is available.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bhittani\Filesystem\StaticFilesystem;

echo StaticFilesystem::getContents('/path/to/file');
```

> Any of the public methods may be invoked by static access.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed.

## Testing

```shell
git clone https://github.com/kamalkhan/filesystem

cd filesystem

composer install

composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email `shout@bhittani.com` instead of using the issue tracker.

## Credits

- [Kamal Khan](http://bhittani.com)
- [All Contributors](https://github.com/kamalkhan/filesystem/contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

<!--Status-->
[icon-status]: https://img.shields.io/travis/kamalkhan/filesystem.svg?style=flat-square
[link-status]: https://travis-ci.org/kamalkhan/filesystem
<!--Downloads-->
[icon-downloads]: https://img.shields.io/packagist/dt/bhittani/filesystem.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/bhittani/filesystem
<!--License-->
[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
<!--composer-->
[link-composer]: https://getcomposer.org
