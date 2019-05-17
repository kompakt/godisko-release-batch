<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\SummaryPrinter as PackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Starter as PackshotTaskStarter;
use Kompakt\GodiskoReleaseBatch\Task\BatchZipper\Subscriber\Zipper;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchSummaryPrinter = null;
    protected $packshotTaskStarter = null;
    protected $zipper = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskStarter $packshotTaskStarter,
        Zipper $zipper,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskStarter = $packshotTaskStarter;
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
        $this->packshotTaskStarter->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchSummaryPrinter->deactivate();
        $this->zipper->deactivate();
        $this->packshotTaskStarter->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}