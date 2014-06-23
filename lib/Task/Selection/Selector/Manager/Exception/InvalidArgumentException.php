<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Selection\Selector\Manager\Exception;

use Kompakt\GodiskoReleaseBatch\Exception as BaseException;

class InvalidArgumentException extends \InvalidArgumentException implements BaseException
{}