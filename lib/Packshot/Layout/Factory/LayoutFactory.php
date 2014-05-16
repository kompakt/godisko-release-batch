<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Layout\Factory;

use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
use Kompakt\Mediameister\Packshot\Layout\Factory\LayoutFactoryInterface;

class LayoutFactory implements LayoutFactoryInterface
{
    public function getInstance($dir)
    {
        return new Layout($dir);
    }
}