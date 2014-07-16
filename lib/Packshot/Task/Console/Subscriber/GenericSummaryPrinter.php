<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Packshot\Task\Console\Subscriber;

use Kompakt\Mediameister\Batch\Task\EventNamesInterface;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndErrorEvent;
use Kompakt\Mediameister\Batch\Task\Event\TaskEndEvent;
use Kompakt\Mediameister\Generic\Console\Output\ConsoleOutputInterface;
use Kompakt\Mediameister\Generic\EventDispatcher\EventSubscriberInterface;
use Kompakt\Mediameister\Util\Counter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Share\Summary;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\GenericSummaryMaker;

class GenericSummaryPrinter implements EventSubscriberInterface
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
        $this->writeFullSummary();
    }

    public function onTaskEndError(TaskEndErrorEvent $event)
    {
        $this->writeFullSummary();
    }

    protected function writeFullSummary()
    {
        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        $this->writeItemSummary($this->summary->getFrontArtworkCounter(), 'Front Artwork');
        $this->writeItemSummary($this->summary->getAudioCounter(), 'Audio');
        $this->writeItemSummary($this->summary->getMetadataCounter(), 'Metadata');
    }

    protected function writeItemSummary(Counter $counter, $title)
    {
        $error
            = ($counter->count(GenericSummaryMaker::ERROR))
            ? sprintf(' <error>(%d errors)</error>', $counter->count(GenericSummaryMaker::ERROR))
            : ''
        ;

        $this->output->writeln(
            sprintf(
                '<info>= %s: %s total, %d ok</info>%s',
                $title,
                $counter->getTotal(),
                $counter->count(GenericSummaryMaker::OK),
                $error
            )
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}