<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Layout;

use Kompakt\MediaDeliveryFramework\Packshot\Layout\LayoutInterface as GenericLayoutInterface;

interface LayoutInterface extends GenericLayoutInterface
{
    public function getFrontArtworkFile();
    public function getOtherFrontArtworkFileNames();
    public function getAudioDir();
}