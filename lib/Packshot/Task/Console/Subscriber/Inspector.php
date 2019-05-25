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
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Inspector
{
    protected $dispatcher = null;
    protected $batchEventNames = null;
    protected $packshotEventNames = null;
    protected $output = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames,
        ConsoleOutputInterface $output
    )
    {
        $this->dispatcher = $dispatcher;
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
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

        // batch events
        $this->dispatcher->$method(
            $this->batchEventNames->packshotLoadOk(),
            [$this, 'onPackshotLoadOk']
        );

        $this->dispatcher->$method(
            $this->batchEventNames->packshotLoadError(),
            [$this, 'onPackshotLoadError']
        );

        $this->dispatcher->$method(
            $this->batchEventNames->track(),
            [$this, 'onTrack']
        );

        $this->dispatcher->$method(
            $this->batchEventNames->trackError(),
            [$this, 'onTrackError']
        );

        // packshot events
        $this->dispatcher->$method(
            $this->packshotEventNames->frontArtwork(),
            [$this, 'onFrontArtwork']
        );

        $this->dispatcher->$method(
            $this->packshotEventNames->frontArtworkError(),
            [$this, 'onFrontArtworkError']
        );

        $this->dispatcher->$method(
            $this->packshotEventNames->audio(),
            [$this, 'onAudio']
        );

        $this->dispatcher->$method(
            $this->packshotEventNames->audioError(),
            [$this, 'onAudioError']
        );
    }

    public function onPackshotLoadOk(PackshotEvent $event)
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
                '  <info>UUID: %s</info>',
                $event->getPackshot()->getRelease()->getUuid()
            )
        );

        $date = $event->getPackshot()->getRelease()->getPhysicalReleaseDate();

        $date
            = ($date instanceof \DateTime)
            ? $date->format('Y-m-d')
            : '----'
        ;

        $this->output->writeln(
            sprintf(
                '  <info>Release date: %s</info>',
                $date
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
                '  <info>+ Track (%s) %s: %s</info>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getUuid(),
                $event->getTrack()->getTitle()
            )
        );
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <error>! Track (%s) %s: %s (%s)</error>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getUuid(),
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