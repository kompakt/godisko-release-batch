<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot;

interface EventNamesInterface
{
    public function frontArtwork();
    public function frontArtworkError();
    public function audio();
    public function audioError();
    public function metadata();
    public function metadataError();
}