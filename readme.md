# Godisko Release Batch

Concrete Godisko release batch representation

## Description

This package represents a batch structure as exported by Godisko. Godisko is a legacy system for metadata and media management. This structure might serve as an example to implement your own packshot definition on top of [Mediameister](http://github.com/kompakt/mediameister). This package implements the following structure:

    + packshot-dir
        + meta.XML
        + cover.jpg
        + GB6HK1200063.wav
        + GB6HK1200067.wav

Audio files are named by ISRC code and referenced in meta.XML

## Install

+ `git clone https://github.com/kompakt/godisko-release-batch.git`
+ `cd godisko-release-batch`
+ `curl -sS https://getcomposer.org/installer | php`
+ `php composer.phar install`

## Example

Example of a full task composition to list the packshot contents of a batch

+ `php example/reporter.php`

Example of a full task composition with all supported events

+ `php example/debugger.php`

## Tests

+ `cp tests/config.php.dist config.php`
+ Adjust `config.php` as needed
+ `vendor/bin/phpunit`
+ `vendor/bin/phpunit --coverage-html tests/_coverage`

## License

See LICENSE.