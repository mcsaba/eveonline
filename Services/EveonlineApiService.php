<?php

namespace Eveonline\EveonlineBundle\Services;

use Pheal\Access\StaticCheck;
use Pheal\Cache\FileStorage;
use Pheal\Core\Config;
use Pheal\Pheal;

class EveonlineApiService
{
    /** @var  Pheal */
    private $pheal;

    public function __construct($keyID, $vCode)
    {
        Config::getInstance()->http_ssl_verifypeer = false;
        Config::getInstance()->cache = new FileStorage('d:/phealcache/');
        Config::getInstance()->access = new StaticCheck();

        $this->pheal = new Pheal($keyID, $vCode);
    }

    public function getMapJumps()
    {
        $response = $this->pheal->mapScope->Jumps();
        return $response->toArray();
    }

    public function getMapShipKills()
    {
        $response = $this->pheal->mapScope->Kills();
        return $response->toArray();
    }

    public function getServerStatus()
    {
        $response = $this->pheal->serverScope->ServerStatus();
        return $response->toArray();
    }

}
