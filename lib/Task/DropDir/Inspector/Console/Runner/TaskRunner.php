<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\DropDir\Inspector\Console\Runner;

use Kompakt\Mediameister\DropDir\DropDir;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;

class TaskRunner
{
    protected $dropDir = null;
    protected $output = null;

    public function __construct(
        DropDir $dropDir,
        ConsoleOutputInterface $output
    )
    {
        $this->dropDir = $dropDir;
        $this->output = $output;
    }

    public function inspect()
    {
        foreach ($this->dropDir->getBatches() as $batch)
        {
            $this->output->writeln(sprintf('<info>%s</info>', $batch->getName()));
        }
    }
}