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
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Starter as PackshotTaskStarter;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchSummaryPrinter = null;
    protected $packshotTaskStarter = null;
    protected $packshotInspector = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskStarter $packshotTaskStarter,
        PackshotInspector $packshotInspector,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskStarter = $packshotTaskStarter;
        $this->packshotInspector = $packshotInspector;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->batchSummaryPrinter->activate();
        $this->packshotInspector->activate();
        $this->packshotTaskStarter->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchSummaryPrinter->deactivate();
        $this->packshotInspector->deactivate();
        $this->packshotTaskStarter->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}