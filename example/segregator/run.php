<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

require sprintf('%s/bootstrap.php', dirname(__DIR__));

use Kompakt\Mediameister\Batch\Batch;
use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\Batch\Selection\Factory\FileFactory;
use Kompakt\Mediameister\Batch\Selection\Factory\SelectionFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\DirectoryFactory;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\Factory\ArtworkFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Finder\Factory\AudioFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Loader\Factory\MetadataLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;

// target dir
$tmpDir = getTmpDir();
$targetDropDirPathname = $tmpDir->replaceSubDir('segregator/drop-dir');

// source dir
$dropDirPathname = sprintf('%s/_files/drop-dir', dirname(__DIR__));

// drop dir
$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(),
    new MetadataLoaderFactory(new XmlReaderFactory(new XmlParser(new Release(), new Track()))),
    new ArtworkFinderFactory(),
    new AudioFinderFactory()
);

$directoryFactory = new DirectoryFactory();
$batchFactory = new BatchFactory($packshotFactory, $directoryFactory);
$dropDir = new DropDir($batchFactory, $directoryFactory, $dropDirPathname);
$targetDropDir = new DropDir($batchFactory, $directoryFactory, $targetDropDirPathname);
$selectionFactory = new SelectionFactory(new FileFactory(), $directoryFactory, new ChildFileNamerFactory());

# run
$batch = $dropDir->getBatch('example-batch');
$selection = $selectionFactory->getInstance($batch);
$selection->clear();
$selection->addPackshot($batch->getPackshot('packshot-complete'));
$selection->addPackshot($batch->getPackshot('packshot-no-artwork'));
$selection->copy($targetDropDir);
#$selection->move($targetDropDir);
#$selection->delete($targetDropDir);