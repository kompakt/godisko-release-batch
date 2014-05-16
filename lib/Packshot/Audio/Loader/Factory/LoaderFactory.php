<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Loader;
use Kompakt\Mediameister\Entity\ReleaseInterface;
use Kompakt\Mediameister\Packshot\Audio\Loader\Factory\LoaderFactoryInterface;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class LoaderFactory implements LoaderFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new Loader($layout, $release);
    }
}