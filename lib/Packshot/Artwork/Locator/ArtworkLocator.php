<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Locator;

use Kompakt\GodiskoReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\Layout;
#use Kompakt\Mediameister\Packshot\Artwork\Locator\ArtworkLocatorInterface;

class ArtworkLocator# implements ArtworkLocatorInterface
{
    protected $layout = null;

    public function __construct(Layout $layout, Release $release)
    {
        $this->layout = $layout;
    }

    public function getFrontArtworkFile()
    {
        $pathname = $this->layout->getFrontArtworkFile();
        $fileInfo = new \SplFileInfo($pathname);

        if ($fileInfo->isFile())
        {
            return $pathname;
        }

        $otherNames = array(
            'cover.gif',
            'cover.psd'
        );

        foreach ($otherNames as $name)
        {
            $pathname = sprintf('%s/%s', dirname($pathname), $name);
            $fileInfo = new \SplFileInfo($pathname);

            if ($fileInfo->isFile())
            {
                return $pathname;
            }
        }

        return null;
    }
}