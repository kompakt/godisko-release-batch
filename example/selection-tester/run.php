<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

require sprintf('%s/vendor/autoload.php', dirname(dirname(__DIR__)));

use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\Batch\Selection\Factory\FileFactory;
use Kompakt\Mediameister\Batch\Selection\Factory\SelectionFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\Factory\ArtworkFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Finder\Factory\AudioFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder\Factory\MetadataFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;

// source dir
$dropDirPathname = sprintf('%s/_files/drop-dir', dirname(__DIR__));

// drop dir
$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(),
    new MetadataFinderFactory(new XmlReaderFactory(new XmlParser(new Release(), new Track()))),
    new ArtworkFinderFactory(),
    new AudioFinderFactory()
);

$batchFactory = new BatchFactory($packshotFactory);
$dropDir = new DropDir($batchFactory, $dropDirPathname);
$selectionFactory = new SelectionFactory(new FileFactory());

# run
$batch = $dropDir->getBatch('example-batch');
$selection = $selectionFactory->getInstance($batch);
$selection->addPackshot($batch->getPackshot('packshot-complete'));
$selection->addPackshot($batch->getPackshot('packshot-no-artwork'));
#$selection->removePackshot($batch->getPackshot('packshot-no-artwork'));

foreach($selection->getPackshots() as $packshot)
{
    echo sprintf("%s > %s\n", $packshot->getName(), get_class($packshot));
}