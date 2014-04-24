<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory;

use Kompakt\ReleaseBatch\Packshot\Layout\Factory\LayoutFactoryInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;

class LayoutFactory implements LayoutFactoryInterface
{
    public function getInstance($dir)
    {
        return new Layout($dir);
    }
}