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

use Kompakt\Mediameister\Task\Core\Batch\BatchTask;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\Debugger;
use Kompakt\Mediameister\Task\Core\Batch\EventNames;

// compose
$eventNames = new EventNames('batch_debugger_task');

$debugger = new Debugger(
    $eventNames,
    $output
);

$task = new BatchTask(
    $dispatcher,
    $eventNames
);

// run
$dispatcher->addSubscriber($debugger);
$batch = $dropDir->getBatch('example-batch');
$task->run($batch);