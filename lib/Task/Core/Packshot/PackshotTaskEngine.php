<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot;

use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\MetadataEvent;
use Kompakt\Mediameister\Batch\BatchInterface;
use Kompakt\Mediameister\Entity\TrackInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Packshot\PackshotInterface;

class PackshotTaskEngine
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $packshot = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        PackshotInterface $packshot
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->packshot = $packshot;
    }

    public function startArtwork()
    {
        $this->handleFrontArtwork();
    }

    public function startAudio(TrackInterface $track)
    {
        $this->handleAudio($track);
    }

    public function startMetadata()
    {
        $this->handleMetadata();
    }

    protected function handleFrontArtwork()
    {
        $pathname = $this->packshot->getArtworkFinder()->getFrontArtworkFile();

        try {
            $this->dispatcher->dispatch(
                $this->eventNames->frontArtwork(),
                new ArtworkEvent($this->packshot, $pathname)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->frontArtworkError(),
                new ArtworkErrorEvent($e, $this->packshot, $pathname)
            );

            return false;
        }
    }

    protected function handleAudio(TrackInterface $track)
    {
        $pathname = $this->packshot->getAudioFinder()->getAudioFile($track->getIsrc());

        try {
            $this->dispatcher->dispatch(
                $this->eventNames->audio(),
                new AudioEvent($this->packshot, $track, $pathname)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->audioError(),
                new AudioErrorEvent($e, $this->packshot, $track, $pathname)
            );

            return false;
        }
    }

    protected function handleMetadata()
    {
        $pathname = $this->packshot->getMetadataLoader()->getFile();

        try {
            $this->dispatcher->dispatch(
                $this->eventNames->metadata(),
                new MetadataEvent($this->packshot, $pathname)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->metadataError(),
                new MetadataErrorEvent($e, $this->packshot, $pathname)
            );

            return false;
        }
    }
}