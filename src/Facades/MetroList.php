<?php

namespace Grechanyuk\MetroList\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getAreaFromName(string $cityName, string|null $countryName = null)
 * @method static getMetrosListByCityName(string $cityName, string|null $countryName = null)
 * @method static getAreas(int|null $area_id = null)
 * @method static getMetroList(int $area_id)
 */
class MetroList extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'metrolist';
    }
}
