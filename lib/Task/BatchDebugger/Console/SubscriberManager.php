<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchDebugger\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Debugger as PackshotDebugger;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Batch\Task\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;

class SubscriberManager
{
    protected $batchDebugger = null;
    protected $batchSummaryMaker = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $packshotDebugger = null;
    protected $packshotSummaryMaker = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchDebugger $batchDebugger,
        GenericBatchSummaryMaker $batchSummaryMaker,
        GenericBatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        PackshotDebugger $packshotDebugger,
        GenericPackshotSummaryMaker $packshotSummaryMaker,
        GenericPackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchDebugger = $batchDebugger;
        $this->batchSummaryMaker = $batchSummaryMaker;
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->packshotDebugger = $packshotDebugger;
        $this->packshotSummaryMaker = $packshotSummaryMaker;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->batchDebugger->activate();
        $this->batchSummaryMaker->activate();
        $this->batchSummaryPrinter->activate();
        $this->packshotTaskEngineStarter->activate();
        $this->packshotDebugger->activate();
        $this->packshotSummaryMaker->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchDebugger->deactivate();
        $this->batchSummaryMaker->deactivate();
        $this->batchSummaryPrinter->deactivate();
        $this->packshotTaskEngineStarter->deactivate();
        $this->packshotDebugger->deactivate();
        $this->packshotSummaryMaker->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}