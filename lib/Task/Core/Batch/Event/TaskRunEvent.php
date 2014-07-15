<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event;

use Kompakt\Mediameister\Batch\BatchInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\Event;

class TaskRunEvent extends Event
{
    protected $batch = null;

    public function __construct(BatchInterface $batch)
    {
        $this->batch = $batch;
    }

    public function getBatch()
    {
        return $this->batch;
    }
}