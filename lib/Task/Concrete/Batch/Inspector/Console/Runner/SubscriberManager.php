<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Inspector\Console\Runner;

use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\Inspector as PackshotInspector;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\PackshotTaskEngineStarter;

class SubscriberManager
{
    protected $dispatcher = null;
    protected $batchSummaryMaker = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $packshotInspector = null;
    protected $packshotSummaryMaker = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        GenericBatchSummaryMaker $batchSummaryMaker,
        GenericBatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        PackshotInspector $packshotInspector,
        GenericPackshotSummaryMaker $packshotSummaryMaker,
        GenericPackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->dispatcher = $dispatcher;
        $this->batchSummaryMaker = $batchSummaryMaker;
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->packshotInspector = $packshotInspector;
        $this->packshotSummaryMaker = $packshotSummaryMaker;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function begin()
    {
        $this->dispatcher->addSubscriber($this->batchSummaryMaker);
        $this->dispatcher->addSubscriber($this->batchSummaryPrinter);
        $this->dispatcher->addSubscriber($this->packshotInspector);
        $this->dispatcher->addSubscriber($this->packshotTaskEngineStarter);
        $this->dispatcher->addSubscriber($this->packshotSummaryMaker);
        $this->dispatcher->addSubscriber($this->packshotSummaryPrinter);
    }

    public function end()
    {
        $this->dispatcher->removeSubscriber($this->batchSummaryMaker);
        $this->dispatcher->removeSubscriber($this->batchSummaryPrinter);
        $this->dispatcher->removeSubscriber($this->packshotInspector);
        $this->dispatcher->removeSubscriber($this->packshotTaskEngineStarter);
        $this->dispatcher->removeSubscriber($this->packshotSummaryMaker);
        $this->dispatcher->removeSubscriber($this->packshotSummaryPrinter);
    }
}