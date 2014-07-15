<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber;

use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\MetadataEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\PackshotLoadErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\PackshotLoadEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TrackErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TrackEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\Share\Summary;

class SummaryMaker implements EventSubscriberInterface
{
    const COUNTER_OK = 'ok';
    const COUNTER_ERROR = 'error';

    protected $eventNames = null;
    protected $summary = null;
    protected $currentPackshot = null;
    protected $currentTrack = null;

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
            // batch events
            $this->eventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            $this->eventNames->packshotLoadError() => array(
                array('onPackshotLoadError', 0)
            ),
            // packshot events
            $this->eventNames->artwork() => array(
                array('onArtwork', 0)
            ),
            $this->eventNames->artworkError() => array(
                array('onArtworkError', 0)
            ),
            $this->eventNames->track() => array(
                array('onTrack', 0)
            ),
            $this->eventNames->trackError() => array(
                array('onTrackError', 0)
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

    public function onPackshotLoad(PackshotLoadEvent $event)
    {
        $this->currentPackshot = $event->getPackshot();
        $id = $this->currentPackshot->getName();
        $this->summary->getPackshotCounter()->add(self::COUNTER_OK, $id);
    }

    public function onPackshotLoadError(PackshotLoadErrorEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->summary->getPackshotCounter()->add(self::COUNTER_ERROR, $id);
    }

    public function onArtwork(ArtworkEvent $event)
    {
        $id = $this->currentPackshot->getName();
        $this->summary->getArtworkCounter()->add(self::COUNTER_OK, $id);
    }

    public function onArtworkError(ArtworkErrorEvent $event)
    {
        $id = $this->currentPackshot->getName();
        $this->summary->getArtworkCounter()->add(self::COUNTER_ERROR, $id);
    }

    public function onFrontArtwork(FrontArtworkEvent $event)
    {
        $id = $this->currentPackshot->getName();
        $this->summary->getFrontArtworkCounter()->add(self::COUNTER_OK, $id);
    }

    public function onFrontArtworkError(FrontArtworkErrorEvent $event)
    {
        $id = $this->currentPackshot->getName();
        $this->summary->getFrontArtworkCounter()->add(self::COUNTER_ERROR, $id);
    }

    public function onTrack(TrackEvent $event)
    {
        $this->currentTrack = $event->getTrack();
        $id = $this->currentPackshot->getName() . spl_object_hash($this->currentTrack);
        $this->summary->getTrackCounter()->add(self::COUNTER_OK, $id);
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $this->currentTrack = $event->getTrack();
        $id = $this->currentPackshot->getName() . spl_object_hash($this->currentTrack);
        $this->summary->getTrackCounter()->add(self::COUNTER_ERROR, $id);
    }

    public function onAudio(AudioEvent $event)
    {
        $id = $this->currentPackshot->getName() . spl_object_hash($this->currentTrack);
        $this->summary->getAudioCounter()->add(self::COUNTER_OK, $id);
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $id = $this->currentPackshot->getName() . spl_object_hash($this->currentTrack);
        $this->summary->getAudioCounter()->add(self::COUNTER_ERROR, $id);
    }

    public function onMetadata(MetadataEvent $event)
    {
        $id = $this->currentPackshot->getName();
        $this->summary->getMetadataCounter()->add(self::COUNTER_OK, $id);
    }

    public function onMetadataError(MetadataErrorEvent $event)
    {
        $id = $this->currentPackshot->getName();
        $this->summary->getMetadataCounter()->add(self::COUNTER_ERROR, $id);
    }
}