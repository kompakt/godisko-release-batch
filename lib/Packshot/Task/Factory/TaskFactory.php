<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Task;
use Kompakt\Mediameister\Packshot\PackshotInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaskFactory
{
    protected $dispatcher = null;
    protected $eventNames = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function getInstance(PackshotInterface $packshot)
    {
        return new Task(
            $this->dispatcher,
            $this->eventNames,
            $packshot
        );
    }
}