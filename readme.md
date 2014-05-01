# Kompakt Godisko Release Batch

Concrete Godisko release batch representation

## Description

This package implements a batch structure as exported by Godisko. This structure is specific to Kompakt but might serve as an example to implement your own batch definition on top the [Kompakt Media Delivery Framework](http://github.com/kompakt/media-delivery-framework). This package implements the following structure:

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

## Tests

+ `cp tests/config.php.dist config.php`
+ Adjust `config.php` as needed
+ `vendor/bin/phpunit`
+ `vendor/bin/phpunit --coverage-html tests/_coverage`

## License

See LICENSE.