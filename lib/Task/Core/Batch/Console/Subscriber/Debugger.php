<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Console\Subscriber;

use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\ArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\BatchEndEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\BatchEndErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\BatchStartEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\BatchStartErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\MetadataErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\MetadataEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\PackshotLoadErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\PackshotLoadEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskRunErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskRunEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TrackErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TrackEvent;

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
            // task events
            $this->eventNames->taskRun() => array(
                array('onTaskRun', 0)
            ),
            $this->eventNames->taskRunError() => array(
                array('onTaskRunError', 0)
            ),
            $this->eventNames->taskEnd() => array(
                array('onTaskEnd', 0)
            ),
            $this->eventNames->taskEndError() => array(
                array('onTaskEndError', 0)
            ),
            // batch events
            $this->eventNames->batchStart() => array(
                array('onBatchStart', 0)
            ),
            $this->eventNames->batchStartError() => array(
                array('onBatchStartError', 0)
            ),
            $this->eventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            $this->eventNames->packshotLoadError() => array(
                array('onPackshotLoadError', 0)
            ),
            $this->eventNames->batchEnd() => array(
                array('onBatchEnd', 0)
            ),
            $this->eventNames->batchEndError() => array(
                array('onBatchEndError', 0)
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
            ),
            $this->eventNames->metadata() => array(
                array('onMetadata', 0)
            ),
            $this->eventNames->metadataError() => array(
                array('onMetadataError', 0)
            )
        );
    }

    public function onTaskRun(TaskRunEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>+ Task run</info>'
            )
        );
    }

    public function onTaskRunError(TaskRunErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>+ Task run error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onTaskEnd(TaskEndEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>+ Task end</info>'
            )
        );
    }

    public function onTaskEndError(TaskEndErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>+ Task end error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onBatchStart(BatchStartEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <info>+ Batch start</info>'
            )
        );
    }

    public function onBatchStartError(BatchStartErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <error>! Batch start error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onBatchEnd(BatchEndEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <info>+ Batch end</info>'
            )
        );
    }

    public function onBatchEndError(BatchEndErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <error>! Batch end error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPackshotLoad(PackshotLoadEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '    <info>+ Packshot load (%s)</info>',
                $event->getPackshot()->getName()
            )
        );
    }

    public function onPackshotLoadError(PackshotLoadErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '    <error>! Packshot load error (%s): %s</error>',
                $event->getPackshot()->getName(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onArtwork(ArtworkEvent $event)
    {
        /*$this->output->writeln(
            sprintf(
                '      <info>+ Artwork</info>'
            )
        );*/
    }

    public function onArtworkError(ArtworkErrorEvent $event)
    {
        /*$this->output->writeln(
            sprintf(
                '      <error>! Artwork error: %s</error>',
                $event->getException()->getMessage()
            )
        );*/
    }

    public function onFrontArtwork(FrontArtworkEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <info>+ Front Artwork</info>'
            )
        );
    }

    public function onFrontArtworkError(FrontArtworkErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <error>! Front Artwork error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onTrack(TrackEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <info>+ Track</info>'
            )
        );
    }

    public function onTrackError(TrackErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <error>! Track error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onAudio(AudioEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '        <info>- Audio</info>'
            )
        );
    }

    public function onAudioError(AudioErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '        <error>! Audio error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onMetadata(MetadataEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <info>+ Metadata</info>'
            )
        );
    }

    public function onMetadataError(MetadataErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '      <error>! Metadata error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }
}