<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Factory;

use Kompakt\MediaDeliveryFramework\Entity\ReleaseInterface;
use Kompakt\MediaDeliveryFramework\Packshot\Audio\Loader\Factory\LoaderFactoryInterface;
use Kompakt\MediaDeliveryFramework\Packshot\Layout\LayoutInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\Loader;

class LoaderFactory implements LoaderFactoryInterface
{
    public function getInstance(LayoutInterface $layout, ReleaseInterface $release)
    {
        return new Loader($layout, $release);
    }
}