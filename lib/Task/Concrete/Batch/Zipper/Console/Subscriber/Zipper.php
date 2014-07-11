<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Subscriber;

use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Task\Core\Batch\EventNamesInterface;
use Kompakt\Mediameister\Task\Core\Batch\Event\ArtworkEvent;
use Kompakt\Mediameister\Task\Core\Batch\Event\MetadataEvent;
use Kompakt\Mediameister\Task\Core\Batch\Event\PackshotLoadEvent;
use Kompakt\Mediameister\Task\Core\Batch\Event\TaskEndEvent;
use Kompakt\Mediameister\Task\Core\Batch\Event\TaskRunEvent;
use Kompakt\Mediameister\Task\Core\Batch\Event\TrackEvent;
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
    protected $currentPackshot = null;
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
            // batch events
            $this->eventNames->packshotLoad() => array(
                array('onPackshotLoad', 0)
            ),
            // packshot events
            $this->eventNames->artwork() => array(
                array('onArtwork', 0)
            ),
            $this->eventNames->track() => array(
                array('onTrack', 0)
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

    public function onPackshotLoad(PackshotLoadEvent $event)
    {
        $this->currentPackshot = $event->getPackshot();
    }

    public function onArtwork(ArtworkEvent $event)
    {
        if ($this->skipArtwork)
        {
            return;
        }

        $frontArtworkFile = $this->currentPackshot->getArtworkFinder()->getFrontArtworkFile();

        if ($frontArtworkFile)
        {
            $this->candidates[] = $frontArtworkFile;
        }
    }

    public function onTrack(TrackEvent $event)
    {
        if ($this->skipAudio)
        {
            return;
        }

        $isrc = $event->getTrack()->getIsrc();
        $audioFile = $this->currentPackshot->getAudioFinder()->getAudioFile($isrc);

        if ($audioFile)
        {
            $this->candidates[] = $audioFile;
        }
    }

    public function onMetadata(MetadataEvent $event)
    {
        if ($this->skipMetadata)
        {
            return;
        }

        $metadataFile = $this->currentPackshot->getMetadataLoader()->getFile();

        if ($metadataFile)
        {
            $this->candidates[] = $metadataFile;
        }
    }
}