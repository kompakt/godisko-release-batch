<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Share\Summary;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GenericSummaryMaker
{
    const OK = 'ok';
    const ERROR = 'error';

    protected $dispatcher = null;
    protected $eventNames = null;
    protected $summary = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        Summary $summary
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->summary = $summary;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->frontArtwork(),
            [$this, 'onFrontArtwork']
        );

        $this->dispatcher->$method(
            $this->eventNames->frontArtworkError(),
            [$this, 'onFrontArtworkError']
        );

        $this->dispatcher->$method(
            $this->eventNames->audio(),
            [$this, 'onAudio']
        );

        $this->dispatcher->$method(
            $this->eventNames->audioError(),
            [$this, 'onAudioError']
        );

        $this->dispatcher->$method(
            $this->eventNames->metadata(),
            [$this, 'onMetadata']
        );

        $this->dispatcher->$method(
            $this->eventNames->metadataError(),
            [$this, 'onMetadataError']
        );
    }

    public function onFrontArtwork(ArtworkEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getFrontArtworkCounter()->add(self::OK, $id);
    }

    public function onFrontArtworkError(ArtworkErrorEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getFrontArtworkCounter()->add(self::ERROR, $id);
    }

    public function onAudio(AudioEvent $event)
    {
        $id = sprintf('%s/%s', $event->getPackshot()->getName(), $event->getTrack()->getIsrc());
        $this->summary->getAudioCounter()->add(self::OK, $id);
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $id = sprintf('%s/%s', $event->getPackshot()->getName(), $event->getTrack()->getIsrc());
        $this->summary->getAudioCounter()->add(self::ERROR, $id);
    }

    public function onMetadata(MetadataEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getMetadataCounter()->add(self::OK, $id);
    }

    public function onMetadataError(MetadataErrorEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getMetadataCounter()->add(self::ERROR, $id);
    }
}