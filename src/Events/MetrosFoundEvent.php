<?php

namespace Grechanyuk\MetroList\Events;

class MetrosFoundEvent {

    public $metros;

    public function __construct(array $metros)
    {
        $this->metros = $metros;
    }
}