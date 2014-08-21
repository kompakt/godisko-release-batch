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
require sprintf('%s/_selection-factory.php', dirname(__DIR__));

use Kompakt\Mediameister\Task\SelectionSegregateMover\Console\TaskRunner;
use Kompakt\Mediameister\Task\SelectionSegregateMover\Task;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;

$task = new Task(
    $selectionFactory,
    new ChildFileNamerFactory(),
    $dropDir,
    $dropDir
);

$taskRunner = new TaskRunner(
    $task,
    $output
);

$taskRunner->run('example-batch');