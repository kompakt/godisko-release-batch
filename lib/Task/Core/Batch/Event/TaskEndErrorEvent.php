<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event;

use Kompakt\Mediameister\Generic\EventDispatcher\Event;
use Kompakt\Mediameister\Util\Timer\Timer;

class TaskEndErrorEvent extends Event
{
    protected $exception = null;
    protected $timer = null;

    public function __construct(\Exception $exception, Timer $timer)
    {
        $this->exception = $exception;
        $this->timer = $timer;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getTimer()
    {
        return $this->timer;
    }
}