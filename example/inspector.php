<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

require sprintf('%s/vendor/autoload.php', dirname(__DIR__));

use Kompakt\Mediameister\Adapter\Console\Symfony\Output\ConsoleOutput;
use Kompakt\Mediameister\Adapter\EventDispatcher\Symfony\EventDispatcher;
use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\Mediameister\Task\Batch\BatchTask;
use Kompakt\Mediameister\Task\Batch\EventNames;
use Kompakt\Mediameister\Task\Batch\Subscriber\Share\Summary;
use Kompakt\Mediameister\Task\Batch\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Task\Batch\Subscriber\SummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\Factory\ArtworkFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Finder\Factory\AudioFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Finder\Factory\MetadataFinderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Runner\ConsoleTaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Subscriber\Inspector;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

$dropDirPathname = sprintf('%s/_files/drop-dir', __DIR__);

$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(),
    new MetadataFinderFactory(new XmlReaderFactory(new XmlParser(new Release(), new Track()))),
    new ArtworkFinderFactory(),
    new AudioFinderFactory()
);

$batchFactory = new BatchFactory($packshotFactory);
$dropDir = new DropDir($batchFactory, $dropDirPathname);

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

$taskRunner = new ConsoleTaskRunner(
    $subscriberManager,
    $output,
    $dropDir,
    $task
);

$taskRunner->run('example-batch');