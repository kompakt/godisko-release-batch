<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\BatchCherrypicker\Console;

use Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber\SummaryPrinter as PackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Starter as PackshotTaskStarter;
use Kompakt\GodiskoReleaseBatch\Task\BatchCherrypicker\Subscriber\Picker;
use Kompakt\Mediameister\Batch\Task\Console\Subscriber\SummaryPrinter as BatchSummaryPrinter;

class SubscriberManager
{
    protected $batchSummaryPrinter = null;
    protected $packshotTaskStarter = null;
    protected $picker = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        BatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskStarter $packshotTaskStarter,
        Picker $picker,
        PackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskStarter = $packshotTaskStarter;
        $this->picker = $picker;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function getPicker()
    {
        return $this->picker;
    }

    public function begin()
    {
        $this->batchSummaryPrinter->activate();
        $this->picker->activate();
        $this->packshotTaskStarter->activate();
        $this->packshotSummaryPrinter->activate();
    }

    public function end()
    {
        $this->batchSummaryPrinter->deactivate();
        $this->picker->deactivate();
        $this->packshotTaskStarter->deactivate();
        $this->packshotSummaryPrinter->deactivate();
    }
}