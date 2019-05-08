<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\UuidLister\Console;

use Kompakt\Mediameister\DropDir\DropDir;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class TaskRunner
{
    protected $dropDir = null;
    protected $output = null;

    public function __construct(
        DropDir $dropDir,
        ConsoleOutputInterface $output
    )
    {
        $this->dropDir = $dropDir;
        $this->output = $output;
    }

    public function run($batchName)
    {
        $batch = $this->dropDir->getBatch($batchName);

        if (!$batch)
        {
            $this->output->writeln(
                sprintf(
                    '<error>Batch does not exist: %s</error>',
                    $batchName
                )
            );

            return;
        }

        $unloadablePackshots = [];

        foreach ($batch->getPackshots() as $packshot)
        {
            try {
                $packshot->load();
                $uuid = $packshot->getRelease()->getUuid();
                $this->output->writeln(sprintf('<info>%s</info>', $uuid));
            }
            catch (\Exception $e) {
                $unloadablePackshots[] = $packshot->getName();
            }
        }

        if (!count($unloadablePackshots))
        {
            return;
        }

        foreach ($unloadablePackshots as $packshotName)
        {
            $this->output->writeln(sprintf('<error>Packshot error: %s</error>', $packshotName));
        }
    }
}