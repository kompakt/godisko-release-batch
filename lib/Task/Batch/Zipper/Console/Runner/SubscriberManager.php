<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Batch\Zipper\Console\Runner;

use Kompakt\GodiskoReleaseBatch\Task\Batch\Zipper\Console\Subscriber\Zipper;
use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Task\Batch\Console\Subscriber\SummaryPrinter;
use Kompakt\Mediameister\Task\Batch\Subscriber\SummaryMaker;

class SubscriberManager
{
    protected $dispatcher = null;
    protected $zipper = null;
    protected $summaryMaker = null;
    protected $summaryPrinter = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Zipper $zipper,
        SummaryMaker $summaryMaker,
        SummaryPrinter $summaryPrinter
    )
    {
        $this->dispatcher = $dispatcher;
        $this->zipper = $zipper;
        $this->summaryMaker = $summaryMaker;
        $this->summaryPrinter = $summaryPrinter;
    }

    public function getZipper()
    {
        return $this->zipper;
    }

    public function begin()
    {
        $this->dispatcher->addSubscriber($this->zipper);
        $this->dispatcher->addSubscriber($this->summaryMaker);
        $this->dispatcher->addSubscriber($this->summaryPrinter);
    }

    public function end()
    {
        $this->dispatcher->removeSubscriber($this->zipper);
        $this->dispatcher->removeSubscriber($this->summaryMaker);
        $this->dispatcher->removeSubscriber($this->summaryPrinter);
    }
}