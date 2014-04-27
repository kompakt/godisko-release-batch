# Kompakt Godisko Release Batch

Concrete Godisko release batch representation

## Install

+ `git clone https://github.com/kompakt/godisko-release-batch.git`
+ `cd godisko-release-batch`
+ `curl -sS https://getcomposer.org/installer | php`
+ `php composer.phar install`

## Tests

+ `cp tests/config.php.dist config.php`
+ Adjust `config.php` as needed
+ `vendor/bin/phpunit`
+ `vendor/bin/phpunit tests/Kompakt/Tests/GodiskoReleaseBatch/Packshot/Artwork/Loader/LoaderTest`
+ `vendor/bin/phpunit tests/Kompakt/Tests/GodiskoReleaseBatch/Packshot/Audio/Loader/LoaderTest`
+ `vendor/bin/phpunit tests/Kompakt/Tests/GodiskoReleaseBatch/Packshot/Layout/LayoutTest`
+ `vendor/bin/phpunit tests/Kompakt/Tests/GodiskoReleaseBatch/Packshot/Metadata/Reader/XmlReaderTest`

## License

See LICENSE.