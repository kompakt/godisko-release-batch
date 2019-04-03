<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchInspector\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Inspector as PackshotInspector;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Batch\Task\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;

class SubscriberManager
{
    protected $batchSummaryMaker = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $packshotInspector = null;
    protected $packshotSummaryMaker = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        GenericBatchSummaryMaker $batchSummaryMaker,
        GenericBatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        PackshotInspector $packshotInspector,
        GenericPackshotSummaryMaker $packshotSummaryMaker,
        GenericPackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchSummaryMaker = $batchSummaryMaker;
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->packshotInspector = $packshotInspector;
        $this->packshotSummaryMaker = $packshotSummaryMaker;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->batchSummaryMaker->activate();
        $this->batchSummaryPrinter->activate();
        $this->packshotInspector->activate();
        $this->packshotTaskEngineStarter->activate();
        $this->packshotSummaryMaker->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchSummaryMaker->deactivate();
        $this->batchSummaryPrinter->deactivate();
        $this->packshotInspector->deactivate();
        $this->packshotTaskEngineStarter->deactivate();
        $this->packshotSummaryMaker->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}