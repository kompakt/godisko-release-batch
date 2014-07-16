<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event;

use Kompakt\Mediameister\Entity\TrackInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\Event;
use Kompakt\Mediameister\Packshot\PackshotInterface;

class AudioEvent extends Event
{
    protected $packshot = null;
    protected $track = null;
    protected $pathname = null;

    public function __construct(PackshotInterface $packshot, TrackInterface $track, $pathname)
    {
        $this->packshot = $packshot;
        $this->track = $track;
        $this->pathname = $pathname;
    }

    public function getPackshot()
    {
        return $this->packshot;
    }

    public function getTrack()
    {
        return $this->track;
    }

    public function getPathname()
    {
        return $this->pathname;
    }
}