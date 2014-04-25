<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader;

use Kompakt\GenericReleaseBatch\Packshot\Audio\Loader\LoaderInterface as GenericLoaderInterface;

interface LoaderInterface extends GenericLoaderInterface
{
    public function getAudioFile($isrc);
}