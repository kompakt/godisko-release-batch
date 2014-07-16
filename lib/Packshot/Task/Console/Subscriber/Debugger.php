<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber;

use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataEvent;

class Debugger implements EventSubscriberInterface
{
    protected $eventNames = null;
    protected $output = null;

    public function __construct(
        EventNamesInterface $eventNames,
        ConsoleOutputInterface $output
    )
    {
        $this->eventNames = $eventNames;
        $this->output = $output;
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