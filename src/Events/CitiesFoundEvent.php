<?php

namespace Grechanyuk\MetroList\Events;

class CitiesFoundEvent {

    public $cities;

    public function __construct(array $cities)
    {
        $this->cities = $cities;
    }
}