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

use Kompakt\Mediameister\Task\Batch\Core\BatchTask;
use Kompakt\Mediameister\Task\Batch\Core\EventNames;
use Kompakt\Mediameister\Task\Batch\Core\Subscriber\Share\Summary;
use Kompakt\Mediameister\Task\Batch\Core\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Task\Batch\Core\Console\Subscriber\SummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Task\Batch\Inspector\Console\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Batch\Inspector\Console\Runner\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Batch\Inspector\Console\Subscriber\Inspector;

// compose
$eventNames = new EventNames('batch_inspector_task');
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

// run
$taskRunner->run('example-batch');