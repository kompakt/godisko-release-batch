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
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataErrorEvent;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorLogger
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $logger = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate(Logger $logger)
    {
        $this->handleListeners(true);
        $this->logger = $logger;
    }

    public function deactivate()
    {
        $this->handleListeners(false);
        $this->logger = null;
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->frontArtworkError(),
            [$this, 'onFrontArtworkError']
        );

        $this->dispatcher->$method(
            $this->eventNames->audioError(),
            [$this, 'onAudioError']
        );

        $this->dispatcher->$method(
            $this->eventNames->metadataError(),
            [$this, 'onMetadataError']
        );
    }

    public function onFrontArtworkError(ArtworkErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s: "%s"',
                $event->getPackshot()->getName(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s/%s: "%s"',
                $event->getPackshot()->getName(),
                $event->getTrack()->getIsrc(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onMetadataError(MetadataErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s: "%s"',
                $event->getPackshot()->getName(),
                $event->getException()->getMessage()
            )
        );
    }
}