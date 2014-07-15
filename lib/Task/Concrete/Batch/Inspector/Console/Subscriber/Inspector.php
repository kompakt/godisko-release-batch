<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Inspector\Console\Subscriber;

use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\PackshotLoadErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\PackshotLoadEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskRunEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TrackErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TrackEvent;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;

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
            $this->eventNames->frontArtwork() => array(
                array('onFrontArtwork', 0)
            ),
            $this->eventNames->frontArtworkError() => array(
                array('onFrontArtworkError', 0)
            ),
            $this->eventNames->track() => array(
                array('onTrack', 0)
            ),
            $this->eventNames->trackError() => array(
                array('onTrackError', 0)
            ),
            $this->eventNames->audio() => array(
                array('onAudio', 0)
            ),
            $this->eventNames->audioError() => array(
                array('onAudioError', 0)
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
        /*$frontArtworkFile = $this->currentPackshot->getArtworkFinder()->getFrontArtworkFile();

        if (!$frontArtworkFile)
        {
            throw new \Exception('Front artwork missing');
        }

        $this->output->writeln(
            sprintf('  <info>+ Front artwork: ok</info>')
        );*/
    }

    public function onArtworkError(ArtworkErrorEvent $event)
    {
        /*$this->output->writeln(
            sprintf('  <error>! Front artwork: missing</error>')
        );*/
    }

    public function onFrontArtwork(FrontArtworkEvent $event)
    {
        if (!$event->getPathname())
        {
            throw new \Exception('Front artwork missing');
        }

        $this->output->writeln(
            sprintf('  <info>+ Front artwork: ok</info>')
        );
    }

    public function onFrontArtworkError(FrontArtworkErrorEvent $event)
    {
        $this->output->writeln(
            sprintf('  <error>! Front artwork: missing</error>')
        );
    }

    protected $currentTrack = null;

    public function onTrack(TrackEvent $event)
    {
        $this->currentTrack = $event->getTrack();

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
        $this->currentTrack = $event->getTrack();
        
        $this->output->writeln(
            sprintf(
                '  <error>! Track (%s): %s (%s)</error>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getTitle(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onAudio(AudioEvent $event)
    {
        if (!$event->getPathname())
        {
            throw new \Exception('Audio missing');
        }

        $this->output->writeln(
            sprintf(
                '    <info>+ Audio Ok</info>'
            )
        );
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '    <error>! %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}