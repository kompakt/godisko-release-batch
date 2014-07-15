<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch;

interface EventNamesInterface
{
    // task events
    public function taskRun();
    public function taskRunError();
    public function taskEnd();
    public function taskEndError();

    // batch events
    public function batchStart();
    public function batchStartError();
    public function packshotLoad();
    public function packshotLoadError();
    public function batchEnd();
    public function batchEndError();

    // packshot events
    public function artwork(); // deprecated
    public function artworkError(); // deprecated
    public function frontArtwork();
    public function frontArtworkError();
    public function track();
    public function trackError();
    public function audio();
    public function audioError();
    public function metadata();
    public function metadataError();
}