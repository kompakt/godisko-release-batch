<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader;

use Kompakt\Mediameister\Packshot\Artwork\Loader\LoaderInterface as MeisterLoaderInterface;

interface LoaderInterface extends MeisterLoaderInterface
{
    public function getFrontArtworkFile();
}