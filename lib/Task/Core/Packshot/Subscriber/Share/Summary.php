<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Packshot\Subscriber\Share;

use Kompakt\Mediameister\Util\Counter;

class Summary
{
    protected $frontArtworkCounter = null;
    protected $audioCounter = null;
    protected $metadataCounter = null;

    public function __construct(Counter $counterPrototype)
    {
        $this->frontArtworkCounter = clone $counterPrototype;
        $this->audioCounter = clone $counterPrototype;
        $this->metadataCounter = clone $counterPrototype;
    }

    public function getFrontArtworkCounter()
    {
        return $this->frontArtworkCounter;
    }

    public function getAudioCounter()
    {
        return $this->audioCounter;
    }

    public function getMetadataCounter()
    {
        return $this->metadataCounter;
    }
}