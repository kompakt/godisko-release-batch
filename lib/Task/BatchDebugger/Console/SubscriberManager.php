<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchDebugger\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\SummaryPrinter as PackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Debugger as PackshotDebugger;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Starter as PackshotTaskStarter;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchDebugger = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskStarter = null;
    protected $packshotDebugger = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchDebugger $batchDebugger,
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskStarter $packshotTaskStarter,
        PackshotDebugger $packshotDebugger,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchDebugger = $batchDebugger;
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskStarter = $packshotTaskStarter;
        $this->packshotDebugger = $packshotDebugger;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->batchDebugger->activate();
        $this->batchSummaryPrinter->activate();
        $this->packshotTaskStarter->activate();
        $this->packshotDebugger->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchDebugger->deactivate();
        $this->batchSummaryPrinter->deactivate();
        $this->packshotTaskStarter->deactivate();
        $this->packshotDebugger->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}