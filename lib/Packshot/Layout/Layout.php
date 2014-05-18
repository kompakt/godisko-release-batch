<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Layout;

use Kompakt\GodiskoReleaseBatch\Packshot\Exception\InvalidArgumentException;
use Kompakt\Mediameister\Packshot\Layout\LayoutInterface;

class Layout implements LayoutInterface
{
    protected $dir = null;

    public function __construct($dir)
    {
        $info = new \SplFileInfo($dir);

        if (!$info->isDir())
        {
            throw new InvalidArgumentException(sprintf('Layout dir not found'));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Layout dir not readable'));
        }

        if (!$info->isWritable())
        {
            throw new InvalidArgumentException(sprintf('Layout dir not writable'));
        }
        
        $this->dir = $dir;
    }

    public function getMetadataFile()
    {
        return sprintf('%s/meta.xml', $this->dir);
    }

    public function getFrontArtworkFile()
    {
        return sprintf('%s/cover.jpg', $this->dir);
    }

    public function getAudioDir()
    {
        return $this->dir;
    }
}