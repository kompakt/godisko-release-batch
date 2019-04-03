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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PackshotTaskEngineStarter
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $packshotTaskEngineFactory = null;
    protected $packshotTaskEngine = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        PackshotTaskEngineFactory $packshotTaskEngineFactory
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->packshotTaskEngineFactory = $packshotTaskEngineFactory;
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
            $this->eventNames->packshotLoad(),
            [$this, 'onPackshotLoad']
        );

        $this->dispatcher->$method(
            $this->eventNames->packshotUnload(),
            [$this, 'onPackshotUnload']
        );

        $this->dispatcher->$method(
            $this->eventNames->track(),
            [$this, 'onTrack']
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