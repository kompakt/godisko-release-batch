<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Exception;

use Kompakt\Mediameister\Exception as BaseException;

class RuntimeException extends \RuntimeException implements BaseException
{}