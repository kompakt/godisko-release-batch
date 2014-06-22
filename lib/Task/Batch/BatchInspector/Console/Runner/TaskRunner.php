<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Console\Runner;

use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Task\Batch\BatchTask;
use Kompakt\GodiskoReleaseBatch\Task\Batch\BatchInspector\Console\Runner\SubscriberManager;

class TaskRunner
{
    protected $subscriberManager = null;
    protected $output = null;
    protected $godiskoDropDir = null;
    protected $task = null;

    public function __construct(
        SubscriberManager $subscriberManager,
        ConsoleOutputInterface $output,
        DropDir $godiskoDropDir,
        BatchTask $task
    )
    {
        $this->subscriberManager = $subscriberManager;
        $this->output = $output;
        $this->godiskoDropDir = $godiskoDropDir;
        $this->task = $task;
    }

    public function run($batchName)
    {
        $batch = $this->godiskoDropDir->getBatch($batchName);

        if (!$batch)
        {
            $this->output->writeln(
                sprintf(
                    '<error>Batch does not exist: %s</error>',
                    $batchName
                )
            );

            return;
        }

        $this->subscriberManager->begin();
        $this->task->run($batch);
        $this->subscriberManager->end();
    }
}