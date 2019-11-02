<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataEvent;
use Kompakt\Mediameister\Batch\BatchInterface;
use Kompakt\Mediameister\Entity\TrackInterface;
use Kompakt\Mediameister\Packshot\PackshotInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Task
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

    public function handleFrontArtwork()
    {
        $pathname = $this->packshot->getArtworkLocator()->getFrontArtworkFile();

        try {
            $this->dispatcher->dispatch(
                new ArtworkEvent($this->packshot, $pathname),
                $this->eventNames->frontArtwork()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new ArtworkErrorEvent($e, $this->packshot, $pathname),
                $this->eventNames->frontArtworkError()
            );

            return false;
        }
    }

    public function handleAudio(TrackInterface $track)
    {
        $pathname = $this->packshot->getAudioLocator()->getAudioFile($track->getIsrc());

        try {
            $this->dispatcher->dispatch(
                new AudioEvent($this->packshot, $track, $pathname),
                $this->eventNames->audio()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new AudioErrorEvent($e, $this->packshot, $track, $pathname),
                $this->eventNames->audioError()
            );

            return false;
        }
    }

    public function handlePreMetadata()
    {
        $pathname = $this->packshot->getMetadataLoader()->getFile();

        try {
            $this->dispatcher->dispatch(
                new MetadataEvent($this->packshot, $pathname),
                $this->eventNames->preMetadata()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new MetadataErrorEvent($e, $this->packshot, $pathname),
                $this->eventNames->preMetadataError()
            );

            return false;
        }
    }

    public function handleMetadata()
    {
        $pathname = $this->packshot->getMetadataLoader()->getFile();

        try {
            $this->dispatcher->dispatch(
                new MetadataEvent($this->packshot, $pathname),
                $this->eventNames->metadata()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new MetadataErrorEvent($e, $this->packshot, $pathname),
                $this->eventNames->metadataError()
            );

            return false;
        }
    }
}