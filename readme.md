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

+ `php example/inspector.php`

This will output something like this:

    Processing batch: 2014-05-05
    
    ---------------------------------------
    + Packshot: 880319658433
      Name: ----
      Label: PNN
      Ean: 880319658433
      Release date: 2014-04-28
      + Front artwork: ok
        + Track (DEU671401365): Open (Audio ok)
        + Track (DEU671401366): Sinkhole (Audio ok)
        + Track (DEU671401367): It's Rough (Audio ok)
        + Track (DEU671401368): Live The Dream (Audio ok)
        + Track (DEU671401369): Frankrike (Float) (Audio ok)
        + Track (DEU671401370): Holding (Audio ok)
        ! Track (DEU671401371): A Lot To Share (Album) (Audio missing)
        ! Track (DEU671401372): Wow (And Flutter) (Audio missing)
    ---------------------------------------
    + Packshot: 880319665011
      Name: Kiloton EP
      Label: Correspondant
      Ean: 880319665011
      Release date: 2014-04-14
      ! Front artwork: missing
        + Track (DEU671401337): Kiloton (Audio ok)
        + Track (DEU671401338): Parenthesis (Audio ok)
        + Track (DEU671401339): Kiloton (Hardway Bros Remix) (Audio ok)
        + Track (DEU671401340): Parenthesis (Raudive Remix) (Audio ok)
        
    = Packshots: 2 total, 2 ok
    = Artwork: 2 total, 1 ok (1 errors)
    = Audio: 12 total, 10 ok (2 errors)
    = Time: 0.1425 seconds

Example of a full task composition with all supported events

+ `php example/debugger.php`

This simply outputs the events along the way:

    + Task run
      + Batch start
        + Packshot load
          + Artwork
            + Track
            + Track
          + Metadata
      + Batch end
    + Task end
    + Task final

## Tests

+ `cp tests/config.php.dist config.php`
+ Adjust `config.php` as needed
+ `vendor/bin/phpunit`

## License

See LICENSE.