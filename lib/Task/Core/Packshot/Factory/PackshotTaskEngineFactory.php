<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Factory;

use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Packshot\PackshotInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\PackshotTaskEngine;

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