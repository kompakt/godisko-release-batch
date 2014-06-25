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

use Kompakt\Mediameister\Task\Selection\Copier\Console\Runner\TaskRunner;
use Kompakt\Mediameister\Task\Selection\Copier\Manager\TaskManager;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;

$taskManager = new TaskManager(
    $selectionFactory,
    new ChildFileNamerFactory(),
    $dropDir,
    $dropDir
);

$taskRunner = new TaskRunner(
    $taskManager,
    $output
);

$taskRunner->run('example-batch');