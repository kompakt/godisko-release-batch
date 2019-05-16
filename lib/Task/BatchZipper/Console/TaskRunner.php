<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console;

use Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console\SubscriberManager;
use Kompakt\Mediameister\Batch\Task\Factory\TaskFactory as BatchTaskFactory;
use Kompakt\Mediameister\DropDir\DropDir;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class TaskRunner
{
    protected $subscriberManager = null;
    protected $output = null;
    protected $dropDir = null;
    protected $batchTaskFactory = null;

    public function __construct(
        SubscriberManager $subscriberManager,
        ConsoleOutputInterface $output,
        DropDir $dropDir,
        BatchTaskFactory $batchTaskFactory
    )
    {
        $this->subscriberManager = $subscriberManager;
        $this->output = $output;
        $this->dropDir = $dropDir;
        $this->batchTaskFactory = $batchTaskFactory;
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
        $batch = $this->dropDir->getBatch($batchName);

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
        $this->batchTaskFactory->getInstance($batch)->start();
        $this->subscriberManager->end();
    }
}