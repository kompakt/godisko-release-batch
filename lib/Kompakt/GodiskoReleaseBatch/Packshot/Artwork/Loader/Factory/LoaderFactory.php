<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\Factory;

use Kompakt\ReleaseBatchModel\ReleaseInterface;
use Kompakt\GenericReleaseBatch\Packshot\Artwork\Loader\Factory\LoaderFactoryInterface;
use Kompakt\GenericReleaseBatch\Packshot\Layout\LayoutInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\Loader;

class LoaderFactory implements LoaderFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new Loader($layout, $release);
    }
}