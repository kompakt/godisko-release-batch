<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Batch\Inspector\Console\Runner;

use Kompakt\GodiskoReleaseBatch\Task\Batch\Inspector\Console\Subscriber\Inspector;
use Kompakt\Mediameister\Generic\EventDispatcher\EventDispatcherInterface;
use Kompakt\Mediameister\Task\Batch\Core\Console\Subscriber\SummaryPrinter;
use Kompakt\Mediameister\Task\Batch\Core\Subscriber\SummaryMaker;

class SubscriberManager
{
    protected $dispatcher = null;
    protected $inspector = null;
    protected $summaryMaker = null;
    protected $summaryPrinter = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Inspector $inspector,
        SummaryMaker $summaryMaker,
        SummaryPrinter $summaryPrinter
    )
    {
        $this->dispatcher = $dispatcher;
        $this->inspector = $inspector;
        $this->summaryMaker = $summaryMaker;
        $this->summaryPrinter = $summaryPrinter;
    }

    public function begin()
    {
        $this->dispatcher->addSubscriber($this->inspector);
        $this->dispatcher->addSubscriber($this->summaryMaker);
        $this->dispatcher->addSubscriber($this->summaryPrinter);
    }

    public function end()
    {
        $this->dispatcher->removeSubscriber($this->inspector);
        $this->dispatcher->removeSubscriber($this->summaryMaker);
        $this->dispatcher->removeSubscriber($this->summaryPrinter);
    }
}