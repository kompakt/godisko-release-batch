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
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchDebugger = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $packshotDebugger = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchDebugger $batchDebugger,
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        PackshotDebugger $packshotDebugger,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchDebugger = $batchDebugger;
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->packshotDebugger = $packshotDebugger;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->batchDebugger->activate();
        $this->batchSummaryPrinter->activate();
        $this->packshotTaskEngineStarter->activate();
        $this->packshotDebugger->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchDebugger->deactivate();
        $this->batchSummaryPrinter->deactivate();
        $this->packshotTaskEngineStarter->deactivate();
        $this->packshotDebugger->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}