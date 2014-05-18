<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Finder\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Finder\AudioFinder;
use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Audio\Finder\Factory\AudioFinderFactoryInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class AudioFinderFactory implements AudioFinderFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new AudioFinder($layout, $release);
    }
}