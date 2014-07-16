<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Factory\PackshotTaskEngineFactory;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\PackshotEvent;
use Kompakt\Mediameister\Batch\Task\Event\TrackEvent;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;

class PackshotTaskEngineStarter implements EventSubscriberInterface
{
    protected $eventNames = null;
    protected $packshotTaskEngineFactory = null;
    protected $packshotTaskEngine = null;

    public function __construct(EventNamesInterface $eventNames, PackshotTaskEngineFactory $packshotTaskEngineFactory)
    {
        $this->eventNames = $eventNames;
        $this->packshotTaskEngineFactory = $packshotTaskEngineFactory;
    }

    public function getSubscriptions()
    {
        return array(
            $this->eventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            $this->eventNames->packshotUnload() => array(
                array('onPackshotUnload', 0)
            ),
            $this->eventNames->track() => array(
                array('onTrack', 0)
            )
        );
    }

    public function onPackshotLoad(PackshotEvent $event)
    {
        $this->packshotTaskEngine = $this->packshotTaskEngineFactory->getInstance($event->getPackshot());
        $this->packshotTaskEngine->startArtwork();
    }

    public function onPackshotUnload(PackshotEvent $event)
    {
        $this->packshotTaskEngine->startMetadata();
    }

    public function onTrack(TrackEvent $event)
    {
        $this->packshotTaskEngine->startAudio($event->getTrack());
    }
}