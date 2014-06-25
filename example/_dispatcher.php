<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

use Kompakt\Mediameister\Adapter\EventDispatcher\Symfony\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

$dispatcher = new EventDispatcher(new SymfonyEventDispatcher());