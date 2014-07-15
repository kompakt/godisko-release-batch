<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch;

use Kompakt\Mediameister\Batch\BatchInterface;
use Kompakt\Mediameister\Entity\TrackInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Packshot\PackshotInterface;
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
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Exception\RuntimeException;
use Kompakt\Mediameister\Util\Timer\Timer;

class BatchTask
{
    protected $dispatcher = null;
    protected $eventNames = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function run(BatchInterface $batch)
    {
        try {
            $this->doRun($batch);
        }
        catch (\Exception $e)
        {
            throw new RuntimeException(sprintf('Task error: "%s', $e->getMessage()), null, $e);
        }
    }

    protected function doRun(BatchInterface $batch)
    {
        $timer = new Timer();
        $timer->start();

        if (!$this->runTask($batch))
        {
            $this->endTask($timer);
            return;
        }

        if (!$this->startBatch())
        {
            $this->endBatch();
            $this->endTask($timer);
            return;
        }

        foreach($batch->getPackshots() as $packshot)
        {
            if (!$this->loadPackshot($packshot))
            {
                continue;
            }

            $this->handleArtwork();
            $this->handleFrontArtwork($packshot);

            foreach ($packshot->getRelease()->getTracks() as $track)
            {
                $this->handleTrack($track);
                $this->handleAudio($packshot, $track);
            }

            $this->handleMetadata($packshot);
        }

        $this->endBatch();
        $this->endTask($timer);
    }

    protected function runTask(BatchInterface $batch)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->taskRun(),
                new TaskRunEvent($batch)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->taskRunError(),
                new TaskRunErrorEvent($e)
            );

            return false;
        }
    }

    protected function endTask(Timer $timer)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->taskEnd(),
                new TaskEndEvent($timer->stop())
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->taskEndError(),
                new TaskEndErrorEvent($e, $timer->stop())
            );

            return false;
        }
    }

    protected function startBatch()
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->batchStart(),
                new BatchStartEvent()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->batchStartError(),
                new BatchStartErrorEvent($e)
            );

            return false;
        }
    }

    protected function endBatch()
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->batchEnd(),
                new BatchEndEvent()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->batchEndError(),
                new BatchEndErrorEvent($e)
            );

            return false;
        }
    }

    protected function loadPackshot(PackshotInterface $packshot)
    {
        try {
            $packshot->load();

            $this->dispatcher->dispatch(
                $this->eventNames->packshotLoad(),
                new PackshotLoadEvent($packshot)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->packshotLoadError(),
                new PackshotLoadErrorEvent($packshot, $e)
            );

            return false;
        }
    }

    // deprecated
    protected function handleArtwork()
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->artwork(),
                new ArtworkEvent()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->artworkError(),
                new ArtworkErrorEvent($e)
            );

            return false;
        }
    }

    protected function handleFrontArtwork(PackshotInterface $packshot)
    {
        $pathname = $packshot->getArtworkFinder()->getFrontArtworkFile();

        try {
            $this->dispatcher->dispatch(
                $this->eventNames->frontArtwork(),
                new FrontArtworkEvent($pathname)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->frontArtworkError(),
                new FrontArtworkErrorEvent($e, $pathname)
            );

            return false;
        }
    }

    protected function handleTrack(TrackInterface $track)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->track(),
                new TrackEvent($track)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->trackError(),
                new TrackErrorEvent($track, $e)
            );

            return false;
        }
    }

    protected function handleAudio(PackshotInterface $packshot, $track)
    {
        $pathname = $packshot->getAudioFinder()->getAudioFile($track->getIsrc());

        try {
            $this->dispatcher->dispatch(
                $this->eventNames->audio(),
                new AudioEvent($pathname)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->audioError(),
                new AudioErrorEvent($e, $pathname)
            );

            return false;
        }
    }

    protected function handleMetadata(PackshotInterface $packshot)
    {
        $pathname = $packshot->getMetadataLoader()->getFile();

        try {
            $this->dispatcher->dispatch(
                $this->eventNames->metadata(),
                new MetadataEvent($pathname)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->metadataError(),
                new MetadataErrorEvent($e, $pathname)
            );

            return false;
        }
    }
}