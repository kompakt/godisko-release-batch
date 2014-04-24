# Kompakt Godisko Release Batch

Godisko release batch implementation

## Install

+ `git clone https://github.com/kompakt/godisko-release-batch.git`
+ `cd godisko-release-batch`
+ `curl -sS https://getcomposer.org/installer | php`
+ `php composer.phar install`

## Tests

+ `cp tests/config.php.dist config.php`
+ Adjust `config.php` as needed
+ `vendor/bin/phpunit`

## Unit Tests

+ `vendor/bin/phpunit tests/Kompakt/GodiskoReleaseBatch`
+ `vendor/bin/phpunit tests/Kompakt/GodiskoReleaseBatch/Packshot/Artwork/Loader/LoaderTest`
+ `vendor/bin/phpunit tests/Kompakt/GodiskoReleaseBatch/Packshot/Audio/Loader/LoaderTest`
+ `vendor/bin/phpunit tests/Kompakt/GodiskoReleaseBatch/Packshot/Layout/LayoutTest`
+ `vendor/bin/phpunit tests/Kompakt/GodiskoReleaseBatch/Packshot/Metadata/Reader/XmlReaderTest`