<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader;

use Kompakt\ReleaseBatch\Entity\Release;
use Kompakt\GodiskoReleaseBatch\Packshot\Audio\Loader\LoaderInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Layout\LayoutInterface;

class Loader implements LoaderInterface
{
    protected $layout = null;
    protected $release = null;
    protected $audioFiles = array();
    protected $loaded = false;

    public function __construct(LayoutInterface $layout, Release $release)
    {
        $this->layout = $layout;
        $this->release = $release;
    }

    public function getAudioFile($isrc)
    {
        $this->load();
        return (array_key_exists($isrc, $this->audioFiles)) ? $this->audioFiles[$isrc] : null;
    }

    protected function load()
    {
        if ($this->loaded)
        {
            return $this;
        }

        $this->loaded = true;
        $this->audioFiles = $this->loadAudioFiles();
        return $this;
    }

    protected function loadAudioFiles()
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

        $files = array();

        foreach ($this->release->getTracks() as $track)
        {
            $file = $findAudioFile($this->layout->getAudioDir(), $track->getIsrc());

            if (!$file)
            {
                continue;
            }

            $files[$track->getIsrc()] = $file;
        }

        return $files;
    }
}