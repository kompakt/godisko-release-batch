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

use Kompakt\Mediameister\Task\PackshotLister\Console\TaskRunner;

$taskRunner = new TaskRunner($dropDir, $output);
$taskRunner->run('example-batch');