<?php

namespace Grechanyuk\MetroList;

use Grechanyuk\MetroList\Entities\City;
use Grechanyuk\MetroList\Entities\MetroLine;
use Grechanyuk\MetroList\Events\CitiesFoundEvent;
use Grechanyuk\MetroList\Events\MetrosFoundEvent;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class MetroList
{
    private $client;
    private $cache;
    private $country;
    private $percent;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.hh.ru/']);
        $this->cache = config('metrolist.cache');
        $this->country = config('metrolist.country');
        $this->percent = config('metrolist.percent');
    }

    public function getAreas(int $area_id = null)
    {
        $url = 'areas';

        if ($area_id) {
            $url .= '/' . $area_id;
        }
        
        $cacheName = str_replace('/', '.', 'metrolist.hh.' . $url);

        if ($this->cache) {
            if (Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }


        try {
            $areas = $this->client->get($url);
        } catch (\Exception $e) {
            \Log::warning('Error with hh.ru', ['message' => $e->getMessage(), 'area_id' => $area_id]);
            return null;
        }

        if ($areas->getStatusCode() == 200) {
            $answer = json_decode($areas->getBody()->getContents(), true);
            if ($this->cache) {
                Cache::put($cacheName, $answer, $this->cache * 60);
            }
            return $answer;
        }

        return false;
    }

    public function getAreaFromName(string $city = '', string $country = null)
    {
        $areas = $this->getAreas();

        if (!$areas) {
            return false;
        }

        if(!$country) {
            $country = $this->country;
        }

        $areas = $this->searchCountry($areas, $country);

        $city = $this->search($city, $areas);

        event(new CitiesFoundEvent($city));

        return $city;
    }

    public function getMetrosListByCityName(string $city, string $country = null)
    {
        $cities = $this->getAreaFromName($city, $country);

        if (count($cities) === 1) {
            $metros = $this->getMetroList($cities[0]['id']);
        } else {
            $cityArr = [];
            foreach ($cities as $city) {
                $parent = [];
                if(!empty($city['parent_id'])) {
                    $parent = $this->getAreas($city['parent_id']);
                }

                $cityArr[] = new City($city, $parent);
            }

            return $cityArr;
        }

        $metroArr = [];

        if(!empty($metros['lines'])) {
            foreach ($metros['lines'] as $line) {
                $metroArr[] = new MetroLine($line);
            }
        }

        event(new MetrosFoundEvent($metroArr));

        return $metroArr;

    }

    public function getMetroList(int $city_id)
    {
        $url = 'metro/' . $city_id;
        $cacheName = 'metrolist.hh.' . str_replace('/', '.', $url);

        if ($this->cache) {
            if (Config::has($cacheName)) {
                return Config::get($cacheName);
            }
        }

        try {
            $response = $this->client->get($url);
        } catch (\Exception $e) {
            if ($e->getCode() != 404) {
                \Log::warning('Error with hh.ru', ['message' => $e->getMessage(), 'city_id' => $city_id]);
            }

            return null;
        }

        if ($response->getStatusCode() == 200) {
            $answer = json_decode($response->getBody()->getContents(), true);

            if ($this->cache) {
                Cache::put($cacheName, $answer, $this->cache * 60);
            }
            
            return $answer;
        }

        return false;
    }

    private function searchCountry(array $areas, string $country) {
        $areas = Arr::where($areas, function ($value, $key) use($country) {
            $percent = 0;
            similar_text($country, $value['name'], $percent);

            return $percent > $this->percent;
        });

        return $areas;
    }

    private function search(string $needle, array $array, array $search = [])
    {
        foreach ($array as $item) {
            $percent = 0;
            similar_text(mb_strtolower(trim($item['name'])), mb_strtolower(trim($needle)), $percent);
            if ($percent > $this->percent) {
                $search[] = $item;
            }

            if ($item['areas']) {
                $search = $this->search($needle, $item['areas'], $search);
            }
        }

        return $search;
    }
}