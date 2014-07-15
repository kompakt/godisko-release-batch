<?php

/*
 * This file is part of the kompakt/godisko-release-batch package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\GodiskoReleaseBatch\Task\Core\Batch\Subscriber\Share;

use Kompakt\Mediameister\Util\Counter;

class Summary
{
    protected $packshotCounter = null;
    protected $artworkCounter = null;
    protected $frontArtworkCounter = null;
    protected $trackCounter = null;
    protected $audioCounter = null;
    protected $metadataCounter = null;

    public function __construct(Counter $counterPrototype)
    {
        $this->packshotCounter = clone $counterPrototype;
        $this->artworkCounter = clone $counterPrototype;
        $this->frontArtworkCounter = clone $counterPrototype;
        $this->trackCounter = clone $counterPrototype;
        $this->audioCounter = clone $counterPrototype;
        $this->metadataCounter = clone $counterPrototype;
    }

    public function getPackshotCounter()
    {
        return $this->packshotCounter;
    }

    public function getArtworkCounter()
    {
        return $this->artworkCounter;
    }

    public function getFrontArtworkCounter()
    {
        return $this->frontArtworkCounter;
    }

    public function getTrackCounter()
    {
        return $this->trackCounter;
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