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

use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Runner\SubscriberManager;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Runner\TaskRunner;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Subscriber\Zipper;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\BatchTask;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\EventNames;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\Share\Summary;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\SummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Console\Subscriber\SummaryPrinter;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;
use Kompakt\Mediameister\Util\Counter;

$eventNames = new EventNames('batch_zipper_task');
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

$zipper = new Zipper(
    $eventNames,
    new ChildFileNamerFactory(),
    new FileAdderFactory(),
    $dropDir->getDir()
);

$task = new BatchTask(
    $dispatcher,
    $eventNames
);

$subscriberManager = new SubscriberManager(
    $dispatcher,
    $zipper,
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
$taskRunner->skipMetadata(false);
$taskRunner->skipArtwork(false);
$taskRunner->skipAudio(false);
$taskRunner->run('example-batch');