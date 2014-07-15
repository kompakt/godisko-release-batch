<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event;

use Kompakt\Mediameister\Entity\TrackInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\Event;

class TrackErrorEvent extends Event
{
    protected $track = null;
    protected $exception = null;

    public function __construct(TrackInterface $track, \Exception $exception)
    {
        $this->track = $track;
        $this->exception = $exception;
    }

    public function getTrack()
    {
        return $this->track;
    }

    public function getException()
    {
        return $this->exception;
    }
}