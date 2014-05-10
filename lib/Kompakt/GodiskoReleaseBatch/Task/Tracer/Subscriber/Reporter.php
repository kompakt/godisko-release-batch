<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Tracer\Subscriber;

use Kompakt\Mediameister\Batch\Tracer\EventNamesInterface as BatchEventNamesInterface;
use Kompakt\Mediameister\Batch\Tracer\Event\PackshotLoadErrorEvent;
use Kompakt\Mediameister\Batch\Tracer\Event\PackshotLoadEvent;
use Kompakt\Mediameister\Component\Native\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Component\Native\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Packshot\Tracer\EventNamesInterface as PackshotEventNamesInterface;
use Kompakt\Mediameister\Packshot\Tracer\Event\ArtworkEvent;
use Kompakt\Mediameister\Packshot\Tracer\Event\ArtworkErrorEvent;
use Kompakt\Mediameister\Packshot\Tracer\Event\TrackEvent;
use Kompakt\Mediameister\Packshot\Tracer\Event\TrackErrorEvent;
use Kompakt\Mediameister\Task\Tracer\EventNamesInterface as TaskEventNamesInterface;
use Kompakt\Mediameister\Task\Tracer\Event\InputErrorEvent;
use Kompakt\Mediameister\Task\Tracer\Event\TaskFinalEvent;
use Kompakt\Mediameister\Task\Tracer\Event\TaskRunEvent;
use Kompakt\Mediameister\Util\Counter;

class Reporter implements EventSubscriberInterface
{
    protected $taskEventNames = null;
    protected $batchEventNames = null;
    protected $packshotEventNames = null;
    protected $output = null;
    protected $packshotCounter = null;
    protected $frontArtworkCounter = null;
    protected $audioCounter = null;
    protected $sourceBatch = null;
    protected $currentPackshot = null;

    public function __construct(
        TaskEventNamesInterface $taskEventNames,
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames,
        ConsoleOutputInterface $output
    )
    {
        $this->taskEventNames = $taskEventNames;
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
        $this->output = $output;
        $this->packshotCounter = new Counter();
        $this->frontArtworkCounter = new Counter();
        $this->audioCounter = new Counter();
    }

    public function getSubscriptions()
    {
        return array(
            // task event handlers
            $this->taskEventNames->inputError() => array(
                array('onInputError', 0)
            ),
            $this->taskEventNames->taskRun() => array(
                array('onTaskRun', 0)
            ),
            $this->taskEventNames->taskFinal() => array(
                array('onTaskFinal', 0)
            ),
            // batch event handlers
            $this->batchEventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            $this->batchEventNames->packshotLoadError() => array(
                array('onPackshotLoadError', 0)
            ),
            // packshot event handlers
            $this->packshotEventNames->artwork() => array(
                array('onArtwork', 0)
            ),
            $this->packshotEventNames->artworkError() => array(
                array('onArtworkError', 0)
            ),
            $this->packshotEventNames->track() => array(
                array('onTrack', 0)
            ),
            $this->packshotEventNames->trackError() => array(
                array('onTrackError', 0)
            )
        );
    }

    public function onInputError(InputErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>! Task input error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onTaskRun(TaskRunEvent $event)
    {
        $this->sourceBatch = $event->getSourceBatch();

        $this->output->writeln('');

        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator(true)
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>Processing batch: %s</info>',
                $this->sourceBatch->getName()
            )
        );

        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator(true)
            )
        );

        $this->output->writeln('');
    }

    public function onTaskFinal(TaskFinalEvent $event)
    {
        $this->output->writeln('');

        $this->output->writeln(
            sprintf('<comment>%s</comment>', $this->getSeparator(true))
        );

        $this->output->writeln(
            sprintf(
                '<info>Packshots: %d (%d ok, %d errors)</info>',
                $this->packshotCounter->getCount(),
                $this->packshotCounter->getOks(),
                $this->packshotCounter->getErrors()
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>Artwork: %d (%d ok, %d missing)</info>',
                $this->frontArtworkCounter->getCount(),
                $this->frontArtworkCounter->getOks(),
                $this->frontArtworkCounter->getErrors()
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>Audio: %d (%d ok, %d missing)</info>',
                $this->audioCounter->getCount(),
                $this->audioCounter->getOks(),
                $this->audioCounter->getErrors()
            )
        );

        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->output->writeln(
            sprintf(
                '<info>Batch processed in %s seconds</info>',
                $event->getTimer()->getSeconds()
            )
        );

        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator(true)
            )
        );

        $this->output->writeln('');
    }

    public function onPackshotLoad(PackshotLoadEvent $event)
    {
        $this->currentPackshot = $event->getPackshot();
        $this->packshotCounter->addOks(1);

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
        $this->packshotCounter->addErrors(1);

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
        $frontArtworkFile = $this->currentPackshot->getArtworkLoader()->getFrontArtworkFile();

        if (!$frontArtworkFile)
        {
            throw new \Exception('Front artwork missing');
        }
        
        $this->frontArtworkCounter->addOks(1);

        $this->output->writeln(
            sprintf('  <info>+ Front artwork: ok</info>')
        );
    }

    public function onArtworkError(ArtworkErrorEvent $event)
    {
        $this->frontArtworkCounter->addErrors(1);

        $this->output->writeln(
            sprintf('  <error>! Front artwork: missing</error>')
        );
    }

    public function onTrack(TrackEvent $event)
    {
        $isrc = $event->getTrack()->getIsrc();
        $audioFile = $this->currentPackshot->getAudioLoader()->getAudioFile($isrc);

        if (!$audioFile)
        {
            throw new \Exception('Audio missing');
        }
        
        $this->audioCounter->addOks(1);

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
        $this->audioCounter->addErrors(1);

        $this->output->writeln(
            sprintf(
                '    <error>! Track (%s): %s (%s)</error>',
                $event->getTrack()->getIsrc(),
                $event->getTrack()->getTitle(),
                $event->getException()->getMessage()
            )
        );
    }

    protected function getSeparator($emphasize = false)
    {
        return ($emphasize)
            ? '///////////////////////////////////////'
            : '---------------------------------------'
        ;
    }
}