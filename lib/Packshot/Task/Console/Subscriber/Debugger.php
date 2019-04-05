<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataEvent;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Debugger
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $output = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        ConsoleOutputInterface $output
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->output = $output;
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
        $this->output->writeln(
            sprintf(
                '      <info>> DEBUG: Front Artwork</info>'
            )
        );
    }

    public function onFrontArtworkError(ArtworkErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <error>> DEBUG: Front Artwork: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onAudio(AudioEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '        <info>> DEBUG: Audio</info>'
            )
        );
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '        <error>> DEBUG: Audio: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onMetadata(MetadataEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <info>> DEBUG: Metadata</info>'
            )
        );
    }

    public function onMetadataError(MetadataErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <error>> DEBUG: Metadata: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }
}