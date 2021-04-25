<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\Factory\ArtworkLocatorFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\Factory\AudioLocatorFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Factory\PackshotFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Factory\XmlLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\XmlBuilder;
use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Util\Filesystem\Factory\DirectoryFactory;

// config
$dropDirPathname = sprintf('%s/_files/drop-dir', __DIR__);

// compose
$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(new XmlBuilder()),
    new XmlLoaderFactory(new XmlParser(new Release(), new Track())),
    new ArtworkLocatorFactory(),
    new AudioLocatorFactory()
);

$directoryFactory = new DirectoryFactory();
$batchFactory = new BatchFactory($packshotFactory, $directoryFactory);
$dropDir = new DropDir($batchFactory, $directoryFactory, $dropDirPathname);