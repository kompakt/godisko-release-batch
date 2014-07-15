<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event;

use Kompakt\Mediameister\Generic\EventDispatcher\Event;

class AudioEvent extends Event
{
    protected $pathname = null;

    public function __construct($pathname)
    {
        $this->pathname = $pathname;
    }

    public function getPathname()
    {
        return $this->pathname;
    }
}