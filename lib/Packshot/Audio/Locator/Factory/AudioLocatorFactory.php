<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Locator\AudioLocator;
use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Audio\Locator\Factory\AudioLocatorFactoryInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class AudioLocatorFactory implements AudioLocatorFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new AudioLocator($layout, $release);
    }
}