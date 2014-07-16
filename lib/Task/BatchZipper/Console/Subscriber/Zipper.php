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
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface as BatchEventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndEvent;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;

class Zipper implements EventSubscriberInterface
{
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
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames,
        ChildFileNamerFactory $childFileNamerFactory,
        FileAdderFactory $fileAdderFactory,
        $targetDirPathname
    )
    {
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

    public function getSubscriptions()
    {
        return array(
            // batch events
            $this->batchEventNames->taskEnd() => array(
                array('onTaskEnd', 0)
            ),
            // packshot events
            $this->packshotEventNames->frontArtwork() => array(
                array('onFrontArtwork', 0)
            ),
            $this->packshotEventNames->audio() => array(
                array('onAudio', 0)
            ),
            $this->packshotEventNames->metadata() => array(
                array('onMetadata', 0)
            )
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