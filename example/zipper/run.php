<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

require sprintf('%s/bootstrap.php', dirname(__DIR__));

use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\DirectoryFactory;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\Factory\ArtworkFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Finder\Factory\AudioFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder\Factory\MetadataFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;

// source dir
$dropDirPathname = sprintf('%s/_files/drop-dir', dirname(__DIR__));

// target dir
$tmpDir = getTmpDir();
$targetDropDirPathname = $tmpDir->replaceSubDir('zipper/drop-dir');

// drop dir
$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(),
    new MetadataFinderFactory(new XmlReaderFactory(new XmlParser(new Release(), new Track()))),
    new ArtworkFinderFactory(),
    new AudioFinderFactory()
);

$directoryFactory = new DirectoryFactory();
$batchFactory = new BatchFactory($packshotFactory, $directoryFactory);
$dropDir = new DropDir($batchFactory, $directoryFactory, $dropDirPathname);
$childFileNamerFactory = new ChildFileNamerFactory();
$fileAdderFactory = new FileAdderFactory();


$childFileNamer = $childFileNamerFactory->getInstance($targetDropDirPathname);
$batch = $dropDir->getBatch('example-batch');

$name = $childFileNamer->make($batch->getName(), '', '.zip');
$zipPathname = sprintf('%s/%s', $targetDropDirPathname, $name);
$zip = new \ZipArchive();
$zip->open($zipPathname, ZIPARCHIVE::CREATE);

$fileAdder = $fileAdderFactory->getInstance($zip);
$fileAdder->addChildren($batch->getDir());

$zip->close();