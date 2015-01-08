<?php

namespace Eveonline\EveonlineBundle\Services;

use PHPEveCentral\PHPEveCentral;

class MarketService
{
    public function getMarketDatas($typeid)
    {
        $marketDatas = PHPEveCentral::getInstance()->QuickLook(array($typeid))->send();
        return $marketDatas;
    }

}
