<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

$autoload = sprintf('%s/vendor/autoload.php', dirname(__DIR__));

if (!is_file($autoload))
{
    die(sprintf("Autoload file not found: '%s'\n", $autoload));
}

require $autoload;

use Kompakt\Mediameister\Batch\Factory\BatchFactory;
use Kompakt\Mediameister\Batch\Tracer\BatchTracer;
use Kompakt\Mediameister\Batch\Tracer\EventNames as BatchEventNames;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\DropDir\Registry\Registry;
use Kompakt\Mediameister\EventDispatcher\Adapter\Symfony\SymfonyEventDispatcherAdapter;
use Kompakt\Mediameister\Packshot\Factory\PackshotFactory;
use Kompakt\Mediameister\Packshot\Metadata\Loader\Factory\LoaderFactory as MetadataLoaderFactory;
use Kompakt\Mediameister\Packshot\Tracer\EventNames as PackshotEventNames;
use Kompakt\Mediameister\Packshot\Tracer\PackshotTracer;
use Kompakt\Mediameister\Task\Tracer\EventNames as TaskEventNames;
use Kompakt\Mediameister\Task\Tracer\Subscriber\Debugger;
use Kompakt\Mediameister\Task\Tracer\Subscriber\TracerStarter;
use Kompakt\Mediameister\Task\Task;
use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Entity\Track;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\Factory\LoaderFactory as ArtworkLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Factory\LoaderFactory as AudioLoaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory\LayoutFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Reader\Factory\XmlReaderFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Metadata\Writer\Factory\XmlWriterFactory;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

$dropDirPathname = sprintf('%s/_files/godisko-drop-dir', __DIR__);

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

$dispatcher = new SymfonyEventDispatcherAdapter(new SymfonyEventDispatcher());
$taskEventNames = new TaskEventNames('my_task_tracer');
$batchEventNames = new BatchEventNames('my_batch_tracer');
$packshotEventNames = new PackshotEventNames('my_packshot_tracer');
$batchTracer = new BatchTracer($dispatcher, $batchEventNames);
$packshotTracer = new PackshotTracer($dispatcher, $packshotEventNames);

$tracerStarter = new TracerStarter(
    $dispatcher,
    $taskEventNames,
    $batchEventNames,
    $batchTracer,
    $packshotTracer
);

$debugger = new Debugger(
    $taskEventNames,
    $batchEventNames,
    $packshotEventNames
);

$task = new Task(
    $dispatcher,
    $taskEventNames,
    $dropDirRegistry,
    false
);

$dispatcher->addSubscriber($debugger);
$dispatcher->addSubscriber($tracerStarter); // must be the last one
$task->run('my-godisko-drop-dir-name', 'example-batch');




