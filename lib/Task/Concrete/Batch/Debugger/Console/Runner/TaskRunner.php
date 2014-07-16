<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Debugger\Console\Runner;

use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Task\Core\Batch\Factory\BatchTaskEngineFactory;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Debugger\Console\Runner\SubscriberManager;

class TaskRunner
{
    protected $subscriberManager = null;
    protected $output = null;
    protected $dropDir = null;
    protected $batchTaskEngineFactory = null;

    public function __construct(
        SubscriberManager $subscriberManager,
        ConsoleOutputInterface $output,
        DropDir $dropDir,
        BatchTaskEngineFactory $batchTaskEngineFactory
    )
    {
        $this->subscriberManager = $subscriberManager;
        $this->output = $output;
        $this->dropDir = $dropDir;
        $this->batchTaskEngineFactory = $batchTaskEngineFactory;
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
        $this->batchTaskEngineFactory->getInstance($batch)->start();
        $this->subscriberManager->end();
    }
}