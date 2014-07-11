<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Runner;

use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Runner\SubscriberManager;
use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Task\Core\Batch\BatchTask;

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

    public function skipMetadata($flag)
    {
        $this->subscriberManager->getZipper()->skipMetadata($flag);
    }

    public function skipArtwork($flag)
    {
        $this->subscriberManager->getZipper()->skipArtwork($flag);
    }

    public function skipAudio($flag)
    {
        $this->subscriberManager->getZipper()->skipAudio($flag);
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