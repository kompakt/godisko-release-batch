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
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\LoaderFactory as MetadataLoaderFactory;
use Kompakt\Mediameister\Task\Batch\BatchTask;
use Kompakt\Mediameister\Task\Batch\EventNames;
use Kompakt\Mediameister\Task\Batch\Subscriber\Share\Summary;
use Kompakt\Mediameister\Task\Batch\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Task\Batch\Subscriber\SummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\Factory\LoaderFactory as ArtworkLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Factory\LoaderFactory as AudioLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\XmlParser;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;
use Kompakt\GodiskoReleaseBatch\Task\Batch\Subscriber\Inspector;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

$dropDirPathname = sprintf('%s/_files/drop-dir', __DIR__);

$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new XmlWriterFactory(),
    new MetadataLoaderFactory(new XmlReaderFactory(new XmlParser(new Release(), new Track()))),
    new ArtworkLoaderFactory(),
    new AudioLoaderFactory()
);

$batchFactory = new BatchFactory($packshotFactory);
$dropDir = new DropDir($batchFactory, $dropDirPathname);

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
    new ConsoleOutput(new SymfonyConsoleOutput())
);

$inspector = new Inspector(
    $eventNames,
    new ConsoleOutput(new SymfonyConsoleOutput())
);

$task = new BatchTask(
    $dispatcher,
    $eventNames
);

$dispatcher->addSubscriber($inspector);
$dispatcher->addSubscriber($summaryMaker);
$dispatcher->addSubscriber($summaryPrinter);

$batch = $dropDir->getBatch('example-batch');
$task->run($batch);


