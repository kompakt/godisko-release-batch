<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface as PackshotEventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\ArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\MetadataEvent;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface as BatchEventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndEvent;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Zipper
{
    protected $dispatcher = null;
    protected $batchEventNames = null;
    protected $packshotEventNames = null;
    protected $childFileNamerFactory = null;
    protected $targetDirPathname = null;
    protected $fileAdder = null;
    protected $zip = null;
    protected $candidates = array();
    protected $skipMetadata = false;
    protected $skipArtwork = false;
    protected $skipAudio = false;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames,
        ChildFileNamerFactory $childFileNamerFactory,
        FileAdderFactory $fileAdderFactory,
        $targetDirPathname
    )
    {
        $this->dispatcher = $dispatcher;
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
        $this->childFileNamerFactory = $childFileNamerFactory;
        $this->targetDirPathname = $targetDirPathname;
        $this->zip = new \ZipArchive();
        $this->fileAdder = $fileAdderFactory->getInstance($this->zip);
    }

    public function skipMetadata($flag)
    {
        $this->skipMetadata = (bool) $flag;
    }

    public function skipArtwork($flag)
    {
        $this->skipArtwork = (bool) $flag;
    }

    public function skipAudio($flag)
    {
        $this->skipAudio = (bool) $flag;
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
            $this->batchEventNames->taskEnd(),
            [$this, 'onTaskEnd']
        );

        // packshot events
        $this->dispatcher->$method(
            $this->packshotEventNames->frontArtwork(),
            [$this, 'onFrontArtwork']
        );

        $this->dispatcher->$method(
            $this->packshotEventNames->audio(),
            [$this, 'onAudio']
        );

        $this->dispatcher->$method(
            $this->packshotEventNames->metadata(),
            [$this, 'onMetadata']
        );
    }

    public function onTaskEnd(TaskEndEvent $event)
    {
        $childFileNamer = $this->childFileNamerFactory->getInstance($this->targetDirPathname);
        $name = $childFileNamer->make($event->getBatch()->getName(), '', '.zip');
        $zipPathname = sprintf('%s/%s', $this->targetDirPathname, $name);
        $this->zip->open($zipPathname, \ZIPARCHIVE::CREATE);

        foreach ($this->candidates as $pathname)
        {
            $this->fileAdder->addFileFromBasedir($pathname, $event->getBatch()->getDir());
        }

        $this->zip->close();
    }

    public function onFrontArtwork(ArtworkEvent $event)
    {
        if ($this->skipArtwork)
        {
            return;
        }

        if ($event->getPathname())
        {
            $this->candidates[] = $event->getPathname();
        }
    }

    public function onAudio(AudioEvent $event)
    {
        if ($this->skipAudio)
        {
            return;
        }

        if ($event->getPathname())
        {
            $this->candidates[] = $event->getPathname();
        }
    }

    public function onMetadata(MetadataEvent $event)
    {
        if ($this->skipMetadata)
        {
            return;
        }

        if ($event->getPathname())
        {
            $this->candidates[] = $event->getPathname();
        }
    }
}