<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

use Kompakt\TestHelper\Filesystem\TmpDir;

// load testing configuration
require_once (file_exists(__DIR__ . '/config.php')) ? 'config.php' : 'config.php.dist';

// autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

function getTmpDir()
{
    return new TmpDir(EXAMPLE_KOMPAKT_GODISKORELEASEBATCH_TEMP_DIR);
}