<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Debugger\Console\Runner;

use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\Debugger as BatchDebugger;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\Debugger as PackshotDebugger;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\PackshotTaskEngineStarter;

class SubscriberManager
{
    protected $dispatcher = null;
    protected $batchDebugger = null;
    protected $batchSummaryMaker = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $packshotDebugger = null;
    protected $packshotSummaryMaker = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        BatchDebugger $batchDebugger,
        GenericBatchSummaryMaker $batchSummaryMaker,
        GenericBatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        PackshotDebugger $packshotDebugger,
        GenericPackshotSummaryMaker $packshotSummaryMaker,
        GenericPackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->dispatcher = $dispatcher;
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
        $this->dispatcher->addSubscriber($this->batchDebugger);
        $this->dispatcher->addSubscriber($this->batchSummaryMaker);
        $this->dispatcher->addSubscriber($this->batchSummaryPrinter);
        $this->dispatcher->addSubscriber($this->packshotTaskEngineStarter);
        $this->dispatcher->addSubscriber($this->packshotDebugger);
        $this->dispatcher->addSubscriber($this->packshotSummaryMaker);
        $this->dispatcher->addSubscriber($this->packshotSummaryPrinter);
    }

    public function end()
    {
        $this->dispatcher->removeSubscriber($this->batchDebugger);
        $this->dispatcher->removeSubscriber($this->batchSummaryMaker);
        $this->dispatcher->removeSubscriber($this->batchSummaryPrinter);
        $this->dispatcher->removeSubscriber($this->packshotTaskEngineStarter);
        $this->dispatcher->removeSubscriber($this->packshotDebugger);
        $this->dispatcher->removeSubscriber($this->packshotSummaryMaker);
        $this->dispatcher->removeSubscriber($this->packshotSummaryPrinter);
    }
}