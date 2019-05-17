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
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Inspector as PackshotInspector;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNames as PackshotEventNames;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Factory\TaskFactory as PackshotTaskFactory;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Starter as PackshotTaskStarter;
use Kompakt\GodiskoReleaseBatch\Task\BatchInspector\Console\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\BatchInspector\Console\TaskRunner;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;
use Kompakt\Mediameister\Batch\Task\EventNames as BatchEventNames;
use Kompakt\Mediameister\Batch\Task\Factory\TaskFactory as BatchTaskFactory;
use Kompakt\Mediameister\Util\Counter;
use Kompakt\Mediameister\Util\Timer\Timer;

// batch event stuff
$batchEventNames = new BatchEventNames();

$batchDebugger = new BatchDebugger(
    $dispatcher,
    $batchEventNames,
    $output
);

$batchDebugger->activate();

$batchTaskFactory = new BatchTaskFactory(
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
$packshotEventNames = new PackshotEventNames();

$packshotDebugger = new PackshotDebugger(
    $dispatcher,
    $packshotEventNames,
    $output
);

$packshotDebugger->activate();

$packshotTaskFactory = new PackshotTaskFactory(
    $dispatcher,
    $packshotEventNames
);

$packshotTaskStarter = new PackshotTaskStarter(
    $dispatcher,
    $batchEventNames,
    $packshotTaskFactory
);

$packshotSummaryPrinter = new PackshotSummaryPrinter(
    $dispatcher,
    $batchEventNames,
    $packshotEventNames,
    $output,
    new Counter()
);

$packshotInspector = new PackshotInspector(
    $dispatcher,
    $batchEventNames,
    $packshotEventNames,
    $output
);

$subscriberManager = new SubscriberManager(
    $batchSummaryPrinter,
    $packshotTaskStarter,
    $packshotInspector,
    $packshotSummaryPrinter
);

$taskRunner = new TaskRunner(
    $subscriberManager,
    $output,
    $dropDir,
    $batchTaskFactory
);

// run
$taskRunner->run('example-batch');