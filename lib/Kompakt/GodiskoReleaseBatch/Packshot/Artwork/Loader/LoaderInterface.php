<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader;

#use Kompakt\GenericReleaseBatch\Packshot\Artwork\Loader\LoaderInterface as GenericLoaderInterface;

#interface LoaderInterface extends GenericLoaderInterface
interface LoaderInterface
{
    public function getFrontArtworkFile();
}