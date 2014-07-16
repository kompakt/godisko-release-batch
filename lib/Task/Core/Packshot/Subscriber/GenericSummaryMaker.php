<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber;

use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Event\MetadataEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\Share\Summary;

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