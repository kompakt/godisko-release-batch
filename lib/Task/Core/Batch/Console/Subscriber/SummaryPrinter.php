<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Console\Subscriber;

use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\EventNamesInterface;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndErrorEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Event\TaskEndEvent;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\Share\Summary;
use Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\SummaryMaker;
use Kompakt\Mediameister\Util\Counter;
use Kompakt\Mediameister\Util\Timer\Timer;

class SummaryPrinter implements EventSubscriberInterface
{
    protected $eventNames = null;
    protected $summary = null;
    protected $output = null;

    public function __construct(
        EventNamesInterface $eventNames,
        Summary $summary,
        ConsoleOutputInterface $output
    )
    {
        $this->eventNames = $eventNames;
        $this->summary = $summary;
        $this->output = $output;
    }

    public function getSubscriptions()
    {
        return array(
            $this->eventNames->taskEnd() => array(
                array('onTaskEnd', 0)
            ),
            $this->eventNames->taskEndError() => array(
                array('onTaskEndError', 0)
            )
        );
    }

    public function onTaskEnd(TaskEndEvent $event)
    {
        $this->writeFullSummary($event->getTimer());
    }

    public function onTaskEndError(TaskEndErrorEvent $event)
    {
        $this->writeFullSummary($event->getTimer());
    }

    protected function writeFullSummary(Timer $timer)
    {
        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->writeItemSummary($this->summary->getPackshotCounter(), 'Packshots');
        $this->writeItemSummary($this->summary->getArtworkCounter(), 'Artwork');
        $this->writeItemSummary($this->summary->getFrontArtworkCounter(), 'Front Artwork');
        $this->writeItemSummary($this->summary->getMetadataCounter(), 'Metadata');
        $this->writeItemSummary($this->summary->getTrackCounter(), 'Tracks');
        $this->writeItemSummary($this->summary->getAudioCounter(), 'Audio');

        $this->output->writeln(
            sprintf(
                '<info>= Time: %d seconds</info>',
                $timer->getSeconds()
            )
        );
    }

    protected function writeItemSummary(Counter $counter, $title)
    {
        $error
            = ($counter->count(SummaryMaker::COUNTER_ERROR))
            ? sprintf(' <error>(%d errors)</error>', $counter->count(SummaryMaker::COUNTER_ERROR))
            : ''
        ;

        $this->output->writeln(
            sprintf(
                '<info>= %s: %s total, %d ok</info>%s',
                $title,
                $counter->getTotal(),
                $counter->count(SummaryMaker::COUNTER_OK),
                $error
            )
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}