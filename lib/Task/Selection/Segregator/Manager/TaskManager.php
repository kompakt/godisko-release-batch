<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Selection\Segregator\Manager;

use Kompakt\GodiskoReleaseBatch\Task\Selection\Segregator\Manager\Exception\InvalidArgumentException;
use Kompakt\Mediameister\Batch\Selection\Factory\SelectionFactory;
use Kompakt\Mediameister\DropDir\DropDir;

class TaskManager
{
    protected $selectionFactory = null;
    protected $dropDir = null;
    protected $targetDropDir = null;

    public function __construct(
        SelectionFactory $selectionFactory,
        DropDir $dropDir,
        DropDir $targetDropDir
    )
    {
        $this->selectionFactory = $selectionFactory;
        $this->dropDir = $dropDir;
        $this->targetDropDir = $targetDropDir;
    }

    public function copyPackshots($batchName)
    {
        $batch = $this->dropDir->getBatch($batchName);

        if (!$batch)
        {
            throw new InvalidArgumentException(sprintf('Batch does not exist: "%s"', $batchName));
        }

        $selection = $this->selectionFactory->getInstance($batch);
        $selection->copy($this->targetDropDir);
    }

    public function movePackshots($batchName)
    {
        $batch = $this->dropDir->getBatch($batchName);

        if (!$batch)
        {
            throw new InvalidArgumentException(sprintf('Batch does not exist: "%s"', $batchName));
        }

        $selection = $this->selectionFactory->getInstance($batch);
        $selection->move($this->targetDropDir);
    }
}