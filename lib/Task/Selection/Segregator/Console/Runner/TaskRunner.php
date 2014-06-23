<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Selection\Segregator\Console\Runner;

use Kompakt\GodiskoReleaseBatch\Task\Selection\Segregator\Manager\TaskManager;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;

class TaskRunner
{
    protected $taskManager = null;
    protected $output = null;

    public function __construct(
        TaskManager $taskManager,
        ConsoleOutputInterface $output
    )
    {
        $this->taskManager = $taskManager;
        $this->output = $output;
    }

    public function copyPackshots($batchName)
    {
        try {
            $this->taskManager->copyPackshots($batchName);
        }
        catch (\Exception $e)
        {
            $this->output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }

    public function movePackshots($batchName)
    {
        try {
            $this->taskManager->movePackshots($batchName);
        }
        catch (\Exception $e)
        {
            $this->output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}