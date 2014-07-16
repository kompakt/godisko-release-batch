<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Exception\InvalidArgumentException;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface as PackshotEventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface as BatchEventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\PackshotErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\PackshotEvent;
use Kompakt\Mediameister\Batch\Task\Event\TrackErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TrackEvent;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;

class Inspector implements EventSubscriberInterface
{
    protected $batchEventNames = null;
    protected $packshotEventNames = null;
    protected $output = null;

    public function __construct(
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames,
        ConsoleOutputInterface $output
    )
    {
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
        $this->output = $output;
    }

    public function getSubscriptions()
    {
        return array(
            // batch events
            $this->batchEventNames->track() => array(
                array('onTrack', 0)
            ),
            $this->batchEventNames->trackError() => array(
                array('onTrackError', 0)
            ),
            $this->batchEventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            $this->batchEventNames->packshotLoadError() => array(
                array('onPackshotLoadError', 0)
            ),
            // packshot events
            $this->packshotEventNames->frontArtwork() => array(
                array('onFrontArtwork', 0)
            ),
            $this->packshotEventNames->frontArtworkError() => array(
                array('onFrontArtworkError', 0)
            ),
            $this->packshotEventNames->audio() => array(
                array('onAudio', 0)
            ),
            $this->packshotEventNames->audioError() => array(
                array('onAudioError', 0)
            )
        );
    }

    public function onPackshotLoad(PackshotEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>+ Packshot: %s</info>',
                $event->getPackshot()->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Name: %s</info>',
                $event->getPackshot()->getRelease()->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Label: %s</info>',
                $event->getPackshot()->getRelease()->getLabel()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>CatNo: %s</info>',
                $event->getPackshot()->getRelease()->getCatalogNumber()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Ean: %s</info>',
                $event->getPackshot()->getRelease()->getEan()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Release date: %s</info>',
                $event->getPackshot()->getRelease()->getPhysicalReleaseDate()->format('Y-m-d')
            )
        );
    }

    public function onPackshotLoadError(PackshotErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>! Packshot: %s</info>',
                $event->getPackshot()->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <error>! %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onTrack(TrackEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <info>+ Track (%s): %s</info>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getTitle()
            )
        );
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <error>! Track (%s): %s (%s)</error>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getTitle(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onFrontArtwork(ArtworkEvent $event)
    {
        if (!$event->getPathname())
        {
            throw new InvalidArgumentException('Artwork missing');
        }

        $this->output->writeln(
            sprintf('  <info>> Front artwork</info>')
        );
    }

    public function onFrontArtworkError(ArtworkErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <error>> Front artwork: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onAudio(AudioEvent $event)
    {
        if (!$event->getPathname())
        {
            throw new InvalidArgumentException('Audio missing');
        }

        $this->output->writeln(
            sprintf('    <info>> Audio</info>')
        );
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '    <error>> Audio: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}