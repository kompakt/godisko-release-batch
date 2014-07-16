<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\PackshotTaskEngine;
use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Packshot\PackshotInterface;

class PackshotTaskEngineFactory
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
        return new PackshotTaskEngine(
            $this->dispatcher,
            $this->eventNames,
            $packshot
        );
    }
}