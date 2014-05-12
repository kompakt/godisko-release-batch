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
use Kompakt\Mediameister\DropDir\Registry\Registry;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\LoaderFactory as MetadataLoaderFactory;
use Kompakt\Mediameister\Task\Batch\BatchTask;
use Kompakt\Mediameister\Task\Batch\EventNames;
use Kompakt\Mediameister\Task\Batch\Subscriber\Debugger;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\Factory\LoaderFactory as ArtworkLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Factory\LoaderFactory as AudioLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

$dropDirPathname = sprintf('%s/_files/drop-dir', __DIR__);

$packshotFactory = new PackshotFactory(
    new LayoutFactory(),
    new MetadataLoaderFactory(new XmlReaderFactory(new Release(), new Track())),
    new XmlWriterFactory(),
    new ArtworkLoaderFactory(),
    new AudioLoaderFactory()
);

$batchFactory = new BatchFactory($packshotFactory);
$dropDir = new DropDir($batchFactory, $dropDirPathname);

$dropDirRegistry = new Registry();
$dropDirRegistry->add('my-godisko-drop-dir-name', $dropDir);

$dispatcher = new EventDispatcher(new SymfonyEventDispatcher());
$eventNames = new EventNames('my_batch_debugger_task');

$debugger = new Debugger(
    $eventNames,
    new ConsoleOutput(new SymfonyConsoleOutput())
);

$task = new BatchTask(
    $dispatcher,
    $eventNames,
    $dropDirRegistry,
    false
);

$dispatcher->addSubscriber($debugger);
$task->run('my-godisko-drop-dir-name', 'example-batch');


