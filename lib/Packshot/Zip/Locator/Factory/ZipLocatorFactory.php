<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Zip\Locator\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Zip\Locator\ZipLocator;
use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Zip\Locator\Factory\ZipLocatorFactoryInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class ZipLocatorFactory implements ZipLocatorFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new ZipLocator($layout, $release);
    }
}