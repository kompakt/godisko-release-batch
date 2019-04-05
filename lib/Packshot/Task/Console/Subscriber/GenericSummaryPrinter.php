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
use Kompakt\Mediameister\Util\Counter;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\Share\Summary;
use Kompakt\GodiskoReleaseBatch\Packshot\Task\Subscriber\GenericSummaryMaker;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GenericSummaryPrinter
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $summary = null;
    protected $output = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        Summary $summary,
        ConsoleOutputInterface $output
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->summary = $summary;
        $this->output = $output;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->taskEnd(),
            [$this, 'onTaskEnd']
        );

        $this->dispatcher->$method(
            $this->eventNames->taskEndError(),
            [$this, 'onTaskEndError']
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