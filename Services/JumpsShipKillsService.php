<?php

namespace Eveonline\EveonlineBundle\Services;

class JumpsShipKillsService
{
    /** @var  EveonlineApiService */
    private $eveapi;
    /** @var  UniverseDataService */
    private $universe;

    public function __construct($eveapi, $universe)
    {
        $this->eveapi = $eveapi;
        $this->universe = $universe;
    }

    public function getApiDatas()
    {
        $apiDatas = $this->universe->getMapDatas(array(
            'jumps' => 0,
            'ship_kills' => 0,
            'faction_kills' => 0,
            'pod_kills' => 0,
        ));

        $jumps    = $this->eveapi->getMapJumps();
        $kills    = $this->eveapi->getMapShipKills();

        foreach($jumps['result']['solarSystems'] as $row) {
            $apiDatas[$row['solarSystemID']] = array_merge($apiDatas[$row['solarSystemID']], array(
                'jumps' => (int)$row['shipJumps'],
            ));
        }

        foreach($kills['result']['solarSystems'] as $row) {
            $apiDatas[$row['solarSystemID']] = array_merge($apiDatas[$row['solarSystemID']], array(
                'ship_kills' => (int)$row['shipKills'],
                'faction_kills' => (int)$row['factionKills'],
                'pod_kills' => (int)$row['podKills'],
            ));
        }

        return $apiDatas;
    }

}
