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

use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Inspector\Console\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Inspector\Console\Runner\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Inspector\Console\Subscriber\Inspector;
use Kompakt\Mediameister\Task\Core\Batch\BatchTask;
use Kompakt\Mediameister\Task\Core\Batch\EventNames;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\Share\Summary;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\SummaryPrinter;
use Kompakt\Mediameister\Util\Counter;

// compose
$eventNames = new EventNames('batch_inspector_task');
$summary = new Summary(new Counter());

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