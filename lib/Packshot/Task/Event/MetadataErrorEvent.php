<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Event;

use Kompakt\Mediameister\Packshot\PackshotInterface;
use Symfony\Contracts\EventDispatcher\Event;

class MetadataErrorEvent extends Event
{
    protected $exception = null;
    protected $packshot = null;
    protected $pathname = null;

    public function __construct(\Exception $exception, PackshotInterface $packshot, $pathname)
    {
        $this->exception = $exception;
        $this->packshot = $packshot;
        $this->pathname = $pathname;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getPackshot()
    {
        return $this->packshot;
    }

    public function getPathname()
    {
        return $this->pathname;
    }
}