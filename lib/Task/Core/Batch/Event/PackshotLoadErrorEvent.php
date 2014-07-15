<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event;

use Kompakt\Mediameister\Generic\EventDispatcher\Event;
use Kompakt\Mediameister\Packshot\PackshotInterface;

class PackshotLoadErrorEvent extends Event
{
    protected $packshot = null;
    protected $exception = null;

    public function __construct(PackshotInterface $packshot, \Exception $exception)
    {
        $this->packshot = $packshot;
        $this->exception = $exception;
    }

    public function getPackshot()
    {
        return $this->packshot;
    }

    public function getException()
    {
        return $this->exception;
    }
}