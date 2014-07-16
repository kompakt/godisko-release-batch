<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber;

use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Task\Core\Batch\EventNamesInterface;
use Kompakt\Mediameister\Task\Core\Batch\Event\PackshotEvent;
use Kompakt\Mediameister\Task\Core\Batch\Event\TrackEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Factory\PackshotTaskEngineFactory;

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