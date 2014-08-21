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
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Inspector as PackshotInspector;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNames as PackshotEventNames;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Factory\PackshotTaskEngineFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Share\Summary as PackshotSummary;
use Kompakt\GodiskoReleaseBatch\Task\BatchInspector\Console\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\BatchInspector\Console\TaskRunner;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Batch\Task\EventNames as BatchEventNames;
use Kompakt\Mediameister\Batch\Task\Factory\BatchTaskEngineFactory;
use Kompakt\Mediameister\Batch\Task\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;
use Kompakt\Mediameister\Batch\Task\Subscriber\Share\Summary as BatchSummary;
use Kompakt\Mediameister\Util\Counter;
use Kompakt\Mediameister\Util\Timer\Timer;

// batch event stuff
$batchEventNames = new BatchEventNames('batch_task');

$batchDebugger = new BatchDebugger(
    $batchEventNames,
    $output
);

$dispatcher->addSubscriber($batchDebugger);

$batchTaskEngineFactory = new BatchTaskEngineFactory(
    $dispatcher,
    $batchEventNames,
    new Timer()
);

$batchSummary = new BatchSummary(new Counter());

$genericBatchSummaryMaker = new GenericBatchSummaryMaker(
    $batchEventNames,
    $batchSummary
);

$genericBatchSummaryPrinter = new GenericBatchSummaryPrinter(
    $batchEventNames,
    $batchSummary,
    $output
);

// packshot event stuff
$packshotEventNames = new PackshotEventNames('packshot_task');

$packshotDebugger = new PackshotDebugger(
    $packshotEventNames,
    $output
);

$dispatcher->addSubscriber($packshotDebugger);

$packshotTaskEngineFactory = new PackshotTaskEngineFactory(
    $dispatcher,
    $packshotEventNames
);

$packshotTaskEngineStarter = new PackshotTaskEngineStarter(
    $batchEventNames,
    $packshotTaskEngineFactory
);

$packshotSummary = new PackshotSummary(new Counter());

$genericPackshotSummaryMaker = new GenericPackshotSummaryMaker(
    $packshotEventNames,
    $packshotSummary
);

$genericPackshotSummaryPrinter = new GenericPackshotSummaryPrinter(
    $batchEventNames,
    $packshotSummary,
    $output
);

$packshotInspector = new PackshotInspector(
    $batchEventNames,
    $packshotEventNames,
    $output
);

$subscriberManager = new SubscriberManager(
    $dispatcher,
    $genericBatchSummaryMaker,
    $genericBatchSummaryPrinter,
    $packshotTaskEngineStarter,
    $packshotInspector,
    $genericPackshotSummaryMaker,
    $genericPackshotSummaryPrinter
);

$taskRunner = new TaskRunner(
    $subscriberManager,
    $output,
    $dropDir,
    $batchTaskEngineFactory
);

// run
$taskRunner->run('example-batch');