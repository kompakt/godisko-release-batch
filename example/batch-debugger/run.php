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

use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Debugger\Console\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Debugger\Console\Runner\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\Debugger as PackshotDebugger;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\EventNames as PackshotEventNames;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Factory\PackshotTaskEngineFactory;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\PackshotTaskEngineStarter;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\Share\Summary as PackshotSummary;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Task\Core\Batch\EventNames as BatchEventNames;
use Kompakt\Mediameister\Task\Core\Batch\Factory\BatchTaskEngineFactory;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\Share\Summary as BatchSummary;
use Kompakt\Mediameister\Util\Counter;
use Kompakt\Mediameister\Util\Timer\Timer;

// batch event stuff
$batchEventNames = new BatchEventNames('batch_task');

$batchDebugger = new BatchDebugger(
    $batchEventNames,
    $output
);

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

$subscriberManager = new SubscriberManager(
    $dispatcher,
    $batchDebugger,
    $genericBatchSummaryMaker,
    $genericBatchSummaryPrinter,
    $packshotTaskEngineStarter,
    $packshotDebugger,
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