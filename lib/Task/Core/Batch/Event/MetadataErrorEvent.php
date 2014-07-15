<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event;

use Kompakt\Mediameister\Generic\EventDispatcher\Event;

class MetadataErrorEvent extends Event
{
    protected $exception = null;
    protected $pathname = null;

    public function __construct(\Exception $exception, $pathname)
    {
        $this->exception = $exception;
        $this->pathname = $pathname;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getPathname()
    {
        return $this->pathname;
    }
}