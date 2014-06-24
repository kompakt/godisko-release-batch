<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

require sprintf('%s/bootstrap.php', dirname(__DIR__));

use Kompakt\Mediameister\Adapter\Console\Symfony\Output\ConsoleOutput;
use Kompakt\Mediameister\Adapter\EventDispatcher\Symfony\EventDispatcher;
use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\Mediameister\Task\Batch\Core\BatchTask;
use Kompakt\Mediameister\Task\Batch\Core\EventNames;
use Kompakt\Mediameister\Task\Batch\Core\Subscriber\Share\Summary;
use Kompakt\Mediameister\Task\Batch\Core\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Task\Batch\Core\Console\Subscriber\SummaryPrinter;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
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
use Kompakt\GodiskoReleaseBatch\Task\Batch\Zipper\Console\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Batch\Zipper\Console\Runner\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Batch\Zipper\Console\Subscriber\Zipper;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

// config
$dropDirPathname = sprintf('%s/_files/drop-dir', dirname(__DIR__));

// prepare
$tmpDir = getTmpDir();
$zipDropDirPathname = $tmpDir->replaceSubDir('zipper/drop-dir');

// compose
$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(),
    new MetadataLoaderFactory(new XmlReaderFactory(new XmlParser(new Release(), new Track()))),
    new ArtworkFinderFactory(),
    new AudioFinderFactory()
);

$directoryFactory = new DirectoryFactory();
$fileAdderFactory = new FileAdderFactory();
$childFileNamerFactory = new ChildFileNamerFactory();
$batchFactory = new BatchFactory($packshotFactory, $directoryFactory);
$dropDir = new DropDir($batchFactory, $directoryFactory, $dropDirPathname);

$output = new ConsoleOutput(new SymfonyConsoleOutput());
$dispatcher = new EventDispatcher(new SymfonyEventDispatcher());
$eventNames = new EventNames('batch_zipper_task');
$summary = new Summary();

$summaryMaker = new SummaryMaker(
    $eventNames,
    $summary
);

$summaryPrinter = new SummaryPrinter(
    $eventNames,
    $summary,
    $output
);

$zipper = new Zipper(
    $eventNames,
    $childFileNamerFactory,
    $fileAdderFactory,
    $zipDropDirPathname
);

$task = new BatchTask(
    $dispatcher,
    $eventNames
);

$subscriberManager = new SubscriberManager(
    $dispatcher,
    $zipper,
    $summaryMaker,
    $summaryPrinter
);

$taskRunner = new TaskRunner(
    $subscriberManager,
    $output,
    $dropDir,
    $task
);

// run
$taskRunner->skipMetadata(false);
$taskRunner->skipArtwork(true);
$taskRunner->skipAudio(true);
$taskRunner->run('example-batch');