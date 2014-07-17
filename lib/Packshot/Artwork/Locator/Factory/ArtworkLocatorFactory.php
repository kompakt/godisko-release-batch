<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator\ArtworkLocator;
use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Artwork\Locator\Factory\ArtworkLocatorFactoryInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class ArtworkLocatorFactory implements ArtworkLocatorFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new ArtworkLocator($layout, $release);
    }
}