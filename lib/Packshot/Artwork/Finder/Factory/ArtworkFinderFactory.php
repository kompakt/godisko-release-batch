<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Finder\ArtworkFinder;
use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Artwork\Finder\Factory\ArtworkFinderFactoryInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class ArtworkFinderFactory implements ArtworkFinderFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new ArtworkFinder($layout, $release);
    }
}