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

class PackshotLoadEvent extends Event
{
    protected $packshot = null;

    public function __construct(PackshotInterface $packshot)
    {
        $this->packshot = $packshot;
    }

    public function getPackshot()
    {
        return $this->packshot;
    }
}