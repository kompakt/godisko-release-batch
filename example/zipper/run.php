<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

require sprintf('%s/bootstrap.php', dirname(__DIR__));
require sprintf('%s/_dropdir.php', dirname(__DIR__));
require sprintf('%s/_output.php', dirname(__DIR__));
require sprintf('%s/_dispatcher.php', dirname(__DIR__));

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Debugger as PackshotDebugger;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\SummaryPrinter as PackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNames as PackshotEventNames;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Factory\PackshotTaskEngineFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console\Subscriber\Zipper;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;
use Kompakt\Mediameister\Batch\Task\EventNames as BatchEventNames;
use Kompakt\Mediameister\Batch\Task\Factory\BatchTaskEngineFactory;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
use Kompakt\Mediameister\Util\Counter;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;
use Kompakt\Mediameister\Util\Timer\Timer;

// batch event stuff
$batchEventNames = new BatchEventNames('batch_task');

$batchDebugger = new BatchDebugger(
    $dispatcher,
    $batchEventNames,
    $output
);

#$batchDebugger->activate();

$batchTaskEngineFactory = new BatchTaskEngineFactory(
    $dispatcher,
    $batchEventNames,
    new Timer()
);

$batchSummaryPrinter = new BatchSummaryPrinter(
    $dispatcher,
    $batchEventNames,
    $output,
    new Counter()
);

// packshot event stuff
$packshotEventNames = new PackshotEventNames('packshot_task');

$packshotDebugger = new PackshotDebugger(
    $dispatcher,
    $packshotEventNames,
    $output
);

#$packshotDebugger->activate();

$packshotTaskEngineFactory = new PackshotTaskEngineFactory(
    $dispatcher,
    $packshotEventNames
);

$packshotTaskEngineStarter = new PackshotTaskEngineStarter(
    $dispatcher,
    $batchEventNames,
    $packshotTaskEngineFactory
);

$packshotSummaryPrinter = new PackshotSummaryPrinter(
    $dispatcher,
    $batchEventNames,
    $packshotEventNames,
    $output,
    new Counter()
);

$zipper = new Zipper(
    $dispatcher,
    $batchEventNames,
    $packshotEventNames,
    new ChildFileNamerFactory(),
    new FileAdderFactory(),
    $dropDir->getDir()
);

$subscriberManager = new SubscriberManager(
    $batchSummaryPrinter,
    $packshotTaskEngineStarter,
    $zipper,
    $packshotSummaryPrinter
);

$taskRunner = new TaskRunner(
    $subscriberManager,
    $output,
    $dropDir,
    $batchTaskEngineFactory
);

// run
$taskRunner->run('example-batch');