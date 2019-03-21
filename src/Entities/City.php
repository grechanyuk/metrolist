<?php

namespace Grechanyuk\MetroList\Entities;

class City {
    private $id;
    private $name;
    private $parent_id;
    private $areas;
    private $parent;

    public function __construct(array $city, array $parent = null)
    {
        $this->setId($city['id']);
        $this->setName($city['name']);
        if($parent) {
            $this->setParent(new City($parent));
        }
        $this->setParentId($city['parent_id']);
        $this->setAreas($city['areas']);
    }

    /**
     * @param mixed $areas
     */
    public function setAreas($areas)
    {
        $this->areas = $areas;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}