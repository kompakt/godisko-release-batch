<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Runner;

use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Task\Core\Batch\Console\Subscriber\GenericSummaryPrinter as GenericBatchSummaryPrinter;
use Kompakt\Mediameister\Task\Core\Batch\Subscriber\GenericSummaryMaker as GenericBatchSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Concrete\Batch\Zipper\Console\Subscriber\Zipper;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Console\Subscriber\GenericSummaryPrinter as GenericPackshotSummaryPrinter;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\GenericSummaryMaker as GenericPackshotSummaryMaker;
use Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\PackshotTaskEngineStarter;

class SubscriberManager
{
    protected $dispatcher = null;
    protected $batchSummaryMaker = null;
    protected $batchSummaryPrinter = null;
    protected $packshotTaskEngineStarter = null;
    protected $zipper = null;
    protected $packshotSummaryMaker = null;
    protected $packshotSummaryPrinter = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        GenericBatchSummaryMaker $batchSummaryMaker,
        GenericBatchSummaryPrinter $batchSummaryPrinter,
        PackshotTaskEngineStarter $packshotTaskEngineStarter,
        Zipper $zipper,
        GenericPackshotSummaryMaker $packshotSummaryMaker,
        GenericPackshotSummaryPrinter $packshotSummaryPrinter
    )
    {
        $this->dispatcher = $dispatcher;
        $this->batchSummaryMaker = $batchSummaryMaker;
        $this->batchSummaryPrinter = $batchSummaryPrinter;
        $this->packshotTaskEngineStarter = $packshotTaskEngineStarter;
        $this->zipper = $zipper;
        $this->packshotSummaryMaker = $packshotSummaryMaker;
        $this->packshotSummaryPrinter = $packshotSummaryPrinter;
    }

    public function getZipper()
    {
        return $this->zipper;
    }

    public function begin()
    {
        $this->dispatcher->addSubscriber($this->batchSummaryMaker);
        $this->dispatcher->addSubscriber($this->batchSummaryPrinter);
        $this->dispatcher->addSubscriber($this->zipper);
        $this->dispatcher->addSubscriber($this->packshotTaskEngineStarter);
        $this->dispatcher->addSubscriber($this->packshotSummaryMaker);
        $this->dispatcher->addSubscriber($this->packshotSummaryPrinter);
    }

    public function end()
    {
        $this->dispatcher->removeSubscriber($this->batchSummaryMaker);
        $this->dispatcher->removeSubscriber($this->batchSummaryPrinter);
        $this->dispatcher->removeSubscriber($this->zipper);
        $this->dispatcher->removeSubscriber($this->packshotTaskEngineStarter);
        $this->dispatcher->removeSubscriber($this->packshotSummaryMaker);
        $this->dispatcher->removeSubscriber($this->packshotSummaryPrinter);
    }
}