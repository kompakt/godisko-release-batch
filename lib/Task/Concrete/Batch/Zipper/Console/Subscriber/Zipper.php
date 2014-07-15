<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Subscriber;

use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\AudioEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\FrontArtworkEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\MetadataEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskRunEvent;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Util\Archive\Factory\FileAdderFactory;
use Kompakt\Mediameister\Util\Filesystem\Factory\ChildFileNamerFactory;

class Zipper implements EventSubscriberInterface
{
    protected $eventNames = null;
    protected $childFileNamerFactory = null;
    protected $targetDirPathname = null;
    protected $fileAdder = null;
    protected $zip = null;
    protected $batch = null;
    protected $candidates = array();
    protected $skipMetadata = false;
    protected $skipArtwork = false;
    protected $skipAudio = false;

    public function __construct(
        EventNamesInterface $eventNames,
        ChildFileNamerFactory $childFileNamerFactory,
        FileAdderFactory $fileAdderFactory,
        $targetDirPathname
    )
    {
        $this->eventNames = $eventNames;
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
            // task events
            $this->eventNames->taskRun() => array(
                array('onTaskRun', 0)
            ),
            $this->eventNames->taskEnd() => array(
                array('onTaskEnd', 0)
            ),
            // packshot events
            $this->eventNames->frontArtwork() => array(
                array('onFrontArtwork', 0)
            ),
            $this->eventNames->audio() => array(
                array('onAudio', 0)
            ),
            $this->eventNames->metadata() => array(
                array('onMetadata', 0)
            )
        );
    }

    public function onTaskRun(TaskRunEvent $event)
    {
        $this->batch = $event->getBatch();
    }

    public function onTaskEnd(TaskEndEvent $event)
    {
        $childFileNamer = $this->childFileNamerFactory->getInstance($this->targetDirPathname);
        $name = $childFileNamer->make($this->batch->getName(), '', '.zip');
        $zipPathname = sprintf('%s/%s', $this->targetDirPathname, $name);
        $this->zip->open($zipPathname, \ZIPARCHIVE::CREATE);

        foreach ($this->candidates as $pathname)
        {
            $this->fileAdder->addFileFromBasedir($pathname, $this->batch->getDir());
        }

        $this->zip->close();
    }

    public function onFrontArtwork(FrontArtworkEvent $event)
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