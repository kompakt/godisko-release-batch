<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\Share;

use Kompakt\Mediameister\Batch\BatchInterface;
use Kompakt\Mediameister\DropDir\DropDirInterface;
use Kompakt\Mediameister\Packshot\PackshotInterface;

class Target
{
    protected $dropDir = null;
    protected $batch = null;
    protected $packshot = null;

    public function __construct(DropDirInterface $dropDir)
    {
        $this->dropDir = $dropDir;
    }

    public function getDropDir()
    {
        return $this->dropDir;
    }

    public function setBatch(BatchInterface $batch)
    {
        $this->batch = $batch;
    }

    public function getBatch()
    {
        return $this->batch;
    }

    public function setPackshot(PackshotInterface $packshot)
    {
        $this->packshot = $packshot;
    }

    public function getPackshot()
    {
        return $this->packshot;
    }
}