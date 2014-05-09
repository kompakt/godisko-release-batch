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
use Kompakt\Mediameister\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Packshot\Tracer\EventNamesInterface as PackshotEventNamesInterface;
use Kompakt\Mediameister\Packshot\Tracer\Event\ArtworkEvent;
use Kompakt\Mediameister\Packshot\Tracer\Event\TrackEvent;
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
    protected $packshotCounter = null;
    protected $frontArtworkCounter = null;
    protected $audioCounter = null;
    protected $sourceBatch = null;
    protected $currentPackshot = null;

    public function __construct(
        TaskEventNamesInterface $taskEventNames,
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames
    )
    {
        $this->taskEventNames = $taskEventNames;
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
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
            $this->packshotEventNames->track() => array(
                array('onTrack', 0)
            )
        );
    }

    public function onInputError(InputErrorEvent $event)
    {
        echo sprintf("! Task input error: '%s'\n", $event->getException()->getMessage());
    }

    public function onTaskRun(TaskRunEvent $event)
    {
        $this->sourceBatch = $event->getSourceBatch();

        echo sprintf(
            "\n%sProcessing batch '%s'\n%s\n",
            $this->getSeparator(true),
            $this->sourceBatch->getName(),
            $this->getSeparator(true)
        );
    }

    public function onTaskFinal(TaskFinalEvent $event)
    {
        echo sprintf("\n%s", $this->getSeparator(true));

        echo sprintf(
            "Packshots: %d (%d ok, %d errors)\n",
            $this->packshotCounter->getCount(),
            $this->packshotCounter->getOks(),
            $this->packshotCounter->getErrors()
        );

        echo sprintf(
            "Artwork: %d (%d ok, %d missing)\n",
            $this->frontArtworkCounter->getCount(),
            $this->frontArtworkCounter->getOks(),
            $this->frontArtworkCounter->getErrors()
        );

        echo sprintf(
            "Audio: %d (%d ok, %d missing)\n",
            $this->audioCounter->getCount(),
            $this->audioCounter->getOks(),
            $this->audioCounter->getErrors()
        );

        echo sprintf(
            "%sBatch processed in %s seconds\n%s\n",
            $this->getSeparator(),
            $event->getTimer()->getSeconds(),
            $this->getSeparator(true)
        );
    }

    public function onPackshotLoad(PackshotLoadEvent $event)
    {
        $this->currentPackshot = $event->getPackshot();
        $this->packshotCounter->addOks(1);

        echo sprintf(
            "%s> Packshot load '%s'\n",
            $this->getSeparator(),
            $event->getPackshot()->getName()
        );
    }

    public function onPackshotLoadError(PackshotLoadErrorEvent $event)
    {
        $this->packshotCounter->addErrors(1);

        echo sprintf(
            "%s! Packshot load error '%s': %s\n",
            $this->getSeparator(),
            $event->getPackshot()->getName(),
            $event->getException()->getMessage()
        );
    }

    public function onArtwork(ArtworkEvent $event)
    {
        $frontArtworkFile = $this->currentPackshot->getArtworkLoader()->getFrontArtworkFile();

        if ($frontArtworkFile)
        {
            $this->frontArtworkCounter->addOks(1);
            echo sprintf("  > Artwork ok\n");
        }
        else {
            $this->frontArtworkCounter->addErrors(1);
            echo sprintf("  ! Artwork missing\n");
        }
    }

    public function onTrack(TrackEvent $event)
    {
        $isrc = $event->getTrack()->getIsrc();
        $audioFile = $this->currentPackshot->getAudioLoader()->getAudioFile($isrc);

        if ($audioFile)
        {
            $this->audioCounter->addOks(1);
            echo sprintf("  > Track '%s' (Audio ok)\n", $event->getTrack()->gettitle());
        }
        else {
            $this->audioCounter->addErrors(1);
            echo sprintf("  ! Track '%s' (Audio missing)\n", $event->getTrack()->gettitle());
        }
    }

    protected function getSeparator($emphasize = false)
    {
        return ($emphasize)
            ? "///////////////////////////////////////\n"
            : "---------------------------------------\n"
        ;
    }
}