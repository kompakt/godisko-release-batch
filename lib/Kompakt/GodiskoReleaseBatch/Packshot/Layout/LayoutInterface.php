<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Layout;

use Kompakt\Mediameister\Packshot\Layout\LayoutInterface as MeisterLayoutInterface;

interface LayoutInterface extends MeisterLayoutInterface
{
    public function getFrontArtworkFile();
    public function getOtherFrontArtworkFileNames();
    public function getAudioDir();
}