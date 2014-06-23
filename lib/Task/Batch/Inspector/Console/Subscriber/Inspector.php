<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Batch\Inspector\Console\Subscriber;

use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Task\Batch\Core\EventNamesInterface;
use Kompakt\Mediameister\Task\Batch\Core\Event\ArtworkEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\ArtworkErrorEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\PackshotLoadErrorEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\PackshotLoadEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\TaskEndErrorEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\TaskEndEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\TaskRunEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\TrackErrorEvent;
use Kompakt\Mediameister\Task\Batch\Core\Event\TrackEvent;

class Inspector implements EventSubscriberInterface
{
    protected $eventNames = null;
    protected $output = null;
    protected $batch = null;
    protected $currentPackshot = null;

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
            // task events
            $this->eventNames->taskRun() => array(
                array('onTaskRun', 0)
            ),
            $this->eventNames->taskEnd() => array(
                array('onTaskEnd', 0)
            ),
            $this->eventNames->taskEndError() => array(
                array('onTaskEndError', 0)
            ),
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
            )
        );
    }

    public function onTaskRun(TaskRunEvent $event)
    {
        $this->batch = $event->getBatch();
        $this->output->writeln('');

        $this->output->writeln(
            sprintf(
                '<info>Processing batch: %s</info>',
                $this->batch->getName()
            )
        );
    }

    public function onTaskEnd(TaskEndEvent $event)
    {
        $this->output->writeln('');
        $this->output->writeln('');
    }

    public function onTaskEndError(TaskEndErrorEvent $event)
    {
        $this->output->writeln('');
        $this->output->writeln('');
    }

    public function onPackshotLoad(PackshotLoadEvent $event)
    {
        $this->currentPackshot = $event->getPackshot();

        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>+ Packshot: %s</info>',
                $this->currentPackshot->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Name: %s</info>',
                $this->currentPackshot->getRelease()->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Label: %s</info>',
                $this->currentPackshot->getRelease()->getLabel()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Ean: %s</info>',
                $this->currentPackshot->getRelease()->getEan()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <info>Release date: %s</info>',
                $this->currentPackshot->getRelease()->getPhysicalReleaseDate()->format('Y-m-d')
            )
        );
    }

    public function onPackshotLoadError(PackshotLoadErrorEvent $event)
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
                $this->currentPackshot->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '  <error>! %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onArtwork(ArtworkEvent $event)
    {
        $frontArtworkFile = $this->currentPackshot->getArtworkFinder()->getFrontArtworkFile();

        if (!$frontArtworkFile)
        {
            throw new \Exception('Front artwork missing');
        }

        $this->output->writeln(
            sprintf('  <info>+ Front artwork: ok</info>')
        );
    }

    public function onArtworkError(ArtworkErrorEvent $event)
    {
        $this->output->writeln(
            sprintf('  <error>! Front artwork: missing</error>')
        );
    }

    public function onTrack(TrackEvent $event)
    {
        $isrc = $event->getTrack()->getIsrc();
        $audioFile = $this->currentPackshot->getAudioFinder()->getAudioFile($isrc);

        if (!$audioFile)
        {
            throw new \Exception('Audio missing');
        }

        $this->output->writeln(
            sprintf(
                '    <info>+ Track (%s): %s (Audio ok)</info>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getTitle()
            )
        );
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '    <error>! Track (%s): %s (%s)</error>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getTitle(),
                $event->getException()->getMessage()
            )
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}