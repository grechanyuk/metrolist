<?php

namespace Grechanyuk\MetroList\Entities;

class MetroLine {
    private $id;
    private $hexColor;
    private $name;
    private $stations;

    public function __construct(array $line)
    {
        $this->setId($line['id']);
        $this->setHexColor($line['hex_color']);
        $this->setName($line['name']);
        $this->setStations($line['stations']);
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $hexColor
     */
    public function setHexColor($hexColor)
    {
        $this->hexColor = $hexColor;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $stations
     */
    public function setStations($stations)
    {
        $this->stations = $stations;
    }

}