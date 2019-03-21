<?php

namespace Grechanyuk\MetroList\Events;

class MetrosFoundEvent {

    public $metros;

    public function __construct($metros)
    {
        $this->metros = $metros;
    }
}