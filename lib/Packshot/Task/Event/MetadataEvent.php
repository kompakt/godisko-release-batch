<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Event;

use Kompakt\Mediameister\Packshot\PackshotInterface;
use Symfony\Component\EventDispatcher\Event;

class MetadataEvent extends Event
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