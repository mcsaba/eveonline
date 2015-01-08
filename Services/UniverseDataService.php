<?php

namespace Eveonline\EveonlineBundle\Services;

use AppKernel;

class UniverseDataService
{
    /** @var  AppKernel */
    private $kernel;

    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    public function getMapDatas(array $extendData = null)
    {
        $pdo = $this->getPdo();

        $stmt = $pdo->query('
          select
            s.solarSystemID, r.regionID, c.constellationID,
            s.solarSystemName, s.security, c.constellationName, r.regionName
          from
            mapSolarSystems s
            INNER JOIN mapConstellations c on s.constellationID = c.constellationID
            INNER JOIN mapRegions r on c.regionID = r.regionID
        ');

        $mapDatas = array();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            $regionName = str_replace(' ', '_', $row['regionName']);
            $constellationName = str_replace(' ', '_', $row['constellationName']);
            $solarSystemName = str_replace(' ', '_', $row['solarSystemName']);

            $mapDatas[$row['solarSystemID']] = array(
                'solarsystem_ID' => (int)$row['solarSystemID'],
                'region_ID' => (int)$row['regionID'],
                'constellation_ID' => (int)$row['constellationID'],
                'solarsystem' => $row['solarSystemName'],
                'constellation' => $row['constellationName'],
                'region' => $row['regionName'],
                'security' => (float)$row['security'],
                'killboard_solarsystem' => 'https://zkillboard.com/system/' . $row['solarSystemID'],
                'killboard_region' => 'https://zkillboard.com/region/' . $row['regionID'],
                'dotlan_region' => 'http://evemaps.dotlan.net/map/' . $regionName,
                'dotlan_constellation' => 'http://evemaps.dotlan.net/map/' . $regionName . '/' . $constellationName,
                'dotlan_solarsystem' => 'http://evemaps.dotlan.net/map/' . $regionName . '/' . $solarSystemName,
            );

            if (!is_null($extendData))
            {
                $mapDatas[$row['solarSystemID']] = array_merge($mapDatas[$row['solarSystemID']], $extendData);
            }
        }

        return $mapDatas;
    }

    private function getPdo()
    {
        $path = $this->kernel->locateResource("@EveonlineEveonlineBundle/Resources/db/universeDataDx.db");
        $dsn = sprintf("sqlite:%s", $path);

        $pdo = new \PDO($dsn);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

}
