<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader;

use Kompakt\GodiskoReleaseBatch\Entity\ReleaseInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Artwork\Loader\LoaderInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\LayoutInterface;

class Loader implements LoaderInterface
{
    protected $layout = null;
    protected $frontArtworkFile = null;
    protected $loaded = false;

    public function __construct(LayoutInterface $layout, ReleaseInterface $release)
    {
        $this->layout = $layout;
    }

    public function getFrontArtworkFile()
    {
        $this->load();
        return $this->frontArtworkFile;
    }

    protected function load()
    {
        if ($this->loaded)
        {
            return $this;
        }

        $this->loaded = true;
        $this->frontArtworkFile = $this->loadFrontArtworkFile();
        return $this;
    }

    protected function loadFrontArtworkFile()
    {
        $pathname = $this->layout->getFrontArtworkFile();
        $fileInfo = new \SplFileInfo($pathname);

        if ($fileInfo->isFile())
        {
            return $pathname;
        }

        foreach ($this->layout->getOtherFrontArtworkFileNames() as $name)
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