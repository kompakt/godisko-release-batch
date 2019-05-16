<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface as PackshotEventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataEvent;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface as BatchEventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndEvent;
use Kompakt\Mediameister\Util\Counter;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SummaryPrinter
{
    const OK = 'ok';
    const ERROR = 'error';

    protected $dispatcher = null;
    protected $batchEventNames = null;
    protected $packshotEventNames = null;
    protected $output = null;
    protected $frontArtworkCounter = null;
    protected $audioCounter = null;
    protected $metadataCounter = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames,
        ConsoleOutputInterface $output,
        Counter $counterPrototype
    )
    {
        $this->dispatcher = $dispatcher;
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
        $this->output = $output;
        $this->frontArtworkCounter = clone $counterPrototype;
        $this->audioCounter = clone $counterPrototype;
        $this->metadataCounter = clone $counterPrototype;
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
            $this->batchEventNames->taskEnd(),
            [$this, 'onTaskEnd']
        );

        $this->dispatcher->$method(
            $this->batchEventNames->taskEndError(),
            [$this, 'onTaskEndError']
        );

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

        $this->dispatcher->$method(
            $this->packshotEventNames->metadata(),
            [$this, 'onMetadata']
        );

        $this->dispatcher->$method(
            $this->packshotEventNames->metadataError(),
            [$this, 'onMetadataError']
        );
    }

    public function onTaskEnd(TaskEndEvent $event)
    {
        $this->printSummary();
    }

    public function onTaskEndError(TaskEndErrorEvent $event)
    {
        $this->printSummary();
    }

    public function onFrontArtwork(ArtworkEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->frontArtworkCounter->add(self::OK, $id);
    }

    public function onFrontArtworkError(ArtworkErrorEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->frontArtworkCounter->add(self::ERROR, $id);
    }

    public function onAudio(AudioEvent $event)
    {
        $id = sprintf('%s/%s', $event->getPackshot()->getName(), $event->getTrack()->getIsrc());
        $this->audioCounter->add(self::OK, $id);
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $id = sprintf('%s/%s', $event->getPackshot()->getName(), $event->getTrack()->getIsrc());
        $this->audioCounter->add(self::ERROR, $id);
    }

    public function onMetadata(MetadataEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->metadataCounter->add(self::OK, $id);
    }

    public function onMetadataError(MetadataErrorEvent $event)
    {
        $id = $event->getPackshot()->getName();
        $this->metadataCounter->add(self::ERROR, $id);
    }

    protected function printSummary()
    {
        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->writeItemSummary($this->frontArtworkCounter, 'Front Artwork');
        $this->writeItemSummary($this->audioCounter, 'Audio');
        $this->writeItemSummary($this->metadataCounter, 'Metadata');
    }

    protected function writeItemSummary(Counter $counter, $title)
    {
        $error
            = ($counter->count(self::ERROR))
            ? sprintf(' <error>(%d errors)</error>', $counter->count(self::ERROR))
            : ''
        ;

        $this->output->writeln(
            sprintf(
                '<info>= %s: %s total, %d ok</info>%s',
                $title,
                $counter->getTotal(),
                $counter->count(self::OK),
                $error
            )
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}