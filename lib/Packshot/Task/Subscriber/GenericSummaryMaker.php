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
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;

class GenericSummaryMaker implements EventSubscriberInterface
{
    const OK = 'ok';
    const ERROR = 'error';

    protected $eventNames = null;
    protected $summary = null;

    public function __construct(
        EventNamesInterface $eventNames,
        Summary $summary
    )
    {
        $this->eventNames = $eventNames;
        $this->summary = $summary;
    }

    public function getSubscriptions()
    {
        return array(
            $this->eventNames->frontArtwork() => array(
                array('onFrontArtwork', 0)
            ),
            $this->eventNames->frontArtworkError() => array(
                array('onFrontArtworkError', 0)
            ),
            $this->eventNames->audio() => array(
                array('onAudio', 0)
            ),
            $this->eventNames->audioError() => array(
                array('onAudioError', 0)
            ),
            $this->eventNames->metadata() => array(
                array('onMetadata', 0)
            ),
            $this->eventNames->metadataError() => array(
                array('onMetadataError', 0)
            )
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