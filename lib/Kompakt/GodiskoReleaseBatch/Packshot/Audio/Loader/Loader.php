<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader;

use Kompakt\GodiskoReleaseBatch\Entity\ReleaseInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\LoaderInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\LayoutInterface;

class Loader implements LoaderInterface
{
    protected $layout = null;
    protected $release = null;

    public function __construct(LayoutInterface $layout, ReleaseInterface $release)
    {
        $this->layout = $layout;
        $this->release = $release;
    }

    public function getAudioFile($isrc)
    {
        $findAudioFile = function($dir, $isrc)
        {
            $suffices = array('wav', 'WAV', 'aiff', 'AIFF', 'mp3', 'MP3');

            foreach ($suffices as $suffix)
            {
                $pathname = sprintf('%s/%s.%s', $dir, $isrc, $suffix);
                $fileInfo = new \SplFileInfo($pathname);

                if ($fileInfo->isFile())
                {
                    return $pathname;
                }
            }

            return null;
        };

        foreach ($this->release->getTracks() as $track)
        {
            if ($isrc !== $track->getIsrc())
            {
                continue;
            }

            return $findAudioFile($this->layout->getAudioDir(), $isrc);
        }

        return null;
    }
}