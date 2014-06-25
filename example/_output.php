<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

use Kompakt\Mediameister\Adapter\Console\Symfony\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;

$output = new ConsoleOutput(new SymfonyConsoleOutput());