<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot;

use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\EventNamesInterface;

class EventNames implements EventNamesInterface
{
    protected $namespace = null;

    public function __construct($namespace = 'packshot_task_engine')
    {
        $this->namespace = $namespace;
    }

    public function frontArtwork()
    {
        return sprintf('%s.front_artwork', $this->namespace);
    }

    public function frontArtworkError()
    {
        return sprintf('%s.front_artwork_error', $this->namespace);
    }

    public function audio()
    {
        return sprintf('%s.audio', $this->namespace);
    }

    public function audioError()
    {
        return sprintf('%s.audio_error', $this->namespace);
    }

    public function metadata()
    {
        return sprintf('%s.metadata', $this->namespace);
    }

    public function metadataError()
    {
        return sprintf('%s.metadata_error', $this->namespace);
    }
}