<?php

namespace Grechanyuk\MetroList\Events;

class CitiesFoundEvent {

    public $cities;

    public function __construct($cities)
    {
        $this->cities = $cities;
    }
}