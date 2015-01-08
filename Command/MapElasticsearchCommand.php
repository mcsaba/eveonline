<?php

namespace Eveonline\EveonlineBundle\Command;

use Eveonline\EveonlineBundle\Services\ElasticsearchIndexerService;
use Eveonline\EveonlineBundle\Services\EveonlineApiService;
use Eveonline\EveonlineBundle\Services\JumpsShipKillsService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MapElasticsearchCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('eveonline:map2es')
            ->setDescription('Download Eveonline Map API to Elasticsearch');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Downloading API data</info>');

        /** @var EveonlineApiService $eveapi */
        $eveapi = $this->getContainer()->get('eveonline_api');
        /** @var ElasticsearchIndexerService $indexer */
        $indexer = $this->getContainer()->get('elasticsearch_indexer');
        /** @var JumpsShipKillsService $jumpShipKills */
        $jumpShipKills = $this->getContainer()->get('jump_shipkills');

        $serverStatus = $eveapi->getServerStatus();
        $serverStatusApiDatas[] = array(
            'server_open' => (int)($serverStatus['result']['serverOpen'] == 'True' ? 1 : 0),
            'online_players' => (int)$serverStatus['result']['onlinePlayers'],
        );

        $mapApiDatas = $jumpShipKills->getApiDatas();

        $output->writeln('<info>Store the map datas to ES</info>');

        $indexer->storeDocuments($serverStatusApiDatas, 'serverstatus');
        $indexer->storeDocuments($mapApiDatas, 'map');

        $output->writeln('Done');
    }

}
