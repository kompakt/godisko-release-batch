<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event;

use Kompakt\Mediameister\Generic\EventDispatcher\Event;
use Kompakt\Mediameister\Packshot\PackshotInterface;

class ArtworkEvent extends Event
{
    protected $packshot = null;
    protected $pathname = null;

    public function __construct(PackshotInterface $packshot, $pathname)
    {
        $this->packshot = $packshot;
        $this->pathname = $pathname;
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