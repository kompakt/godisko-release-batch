<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\SummaryPrinter as PackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console\Subscriber\Zipper;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $zipper = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        Zipper $zipper,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->zipper = $zipper;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function getZipper()
    {
        return $this->zipper;
    }

    public function begin()
    {
        $this->batchSummaryPrinter->activate();
        $this->zipper->activate();
        $this->packshotTaskEngineStarter->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchSummaryPrinter->deactivate();
        $this->zipper->deactivate();
        $this->packshotTaskEngineStarter->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}