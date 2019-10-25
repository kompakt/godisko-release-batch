<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchCherrypicker\Subscriber;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\EventNamesInterface as PackshotEventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Event\AudioEvent;
use Kompakt\Mediameister\Batch\Task\EventNamesInterface as BatchEventNamesInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Picker
{
    protected $dispatcher = null;
    protected $batchEventNames = null;
    protected $packshotEventNames = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        BatchEventNamesInterface $batchEventNames,
        PackshotEventNamesInterface $packshotEventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->batchEventNames = $batchEventNames;
        $this->packshotEventNames = $packshotEventNames;
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

        $this->dispatcher->$method(
            $this->packshotEventNames->audio(),
            [$this, 'onAudio']
        );
    }

    public function onAudio(AudioEvent $event)
    {
        $audioFile = null;

        $batcheNames = [
            '20191016',
            '20191019',
            '20191021',
        ];

        /*$batcheNames = [
            '20190809',
            '20191025'
        ];*/

        $dropDir = dirname(dirname($event->getPackshot()->getDir()));

        foreach ($batcheNames as $batchName)
        {
            $batchDir = sprintf('%s/%s', $dropDir, $batchName);

            foreach (new \DirectoryIterator($batchDir) as $packshotInfo)
            {
                if ($packshotInfo->isDot() || !$packshotInfo->isDir())
                {
                    continue;
                }

                $packshotName = $packshotInfo->getFilename();

                $findAudioFile = sprintf(
                    '%s/%s/%s/%s.wav',
                    $dropDir,
                    $batchName,
                    $packshotName,
                    $event->getTrack()->getIsrc()
                );

                if (!is_file($findAudioFile))
                {
                    continue;
                }

                $audioFile = $findAudioFile;

                #$packshotInfo->getPathname();
                #echo $audioFile . "\n";
            }
        }

        if (!$audioFile)
        {
            echo $event->getTrack()->getIsrc() . "\n";
        }
    }
}