<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchInspector\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\Inspector as PackshotInspector;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\SummaryPrinter as PackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\PackshotTaskEngineStarter;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $packshotInspector = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        PackshotInspector $packshotInspector,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->packshotInspector = $packshotInspector;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->batchSummaryPrinter->activate();
        $this->packshotInspector->activate();
        $this->packshotTaskEngineStarter->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchSummaryPrinter->deactivate();
        $this->packshotInspector->deactivate();
        $this->packshotTaskEngineStarter->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}