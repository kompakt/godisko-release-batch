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
use Kompakt\Mediameister\Task\Batch\BatchTask;
use Kompakt\Mediameister\Task\Batch\EventNames;
use Kompakt\Mediameister\Task\Batch\Subscriber\Share\Summary;
use Kompakt\Mediameister\Task\Batch\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Task\Batch\Console\Subscriber\SummaryPrinter;
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
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Console\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Console\Runner\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Console\Subscriber\Inspector;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

$dropDirPathname = sprintf('%s/_files/drop-dir', dirname(__DIR__));

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

$output = new ConsoleOutput(new SymfonyConsoleOutput());
$dispatcher = new EventDispatcher(new SymfonyEventDispatcher());
$eventNames = new EventNames('my_batch_inspector_task');
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

$inspector = new Inspector(
    $eventNames,
    $output
);

$task = new BatchTask(
    $dispatcher,
    $eventNames
);

$subscriberManager = new SubscriberManager(
    $dispatcher,
    $inspector,
    $summaryMaker,
    $summaryPrinter
);

$taskRunner = new TaskRunner(
    $subscriberManager,
    $output,
    $dropDir,
    $task
);

$taskRunner->run('example-batch');