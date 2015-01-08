<?php

namespace Eveonline\EveonlineBundle\Command;

use Eveonline\EveonlineBundle\Services\ElasticsearchIndexerService;
use Eveonline\EveonlineBundle\Services\MarketService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MarketElasticsearchCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('eveonline:market2es')
            ->setDescription('Download EVE-Central market data to Elasticsearch');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Downloading API data</info>');

        /** @var MarketService $marketService */
        $marketService = $this->getContainer()->get('market');
        /** @var ElasticsearchIndexerService $indexer */
        $indexer = $this->getContainer()->get('elasticsearch_indexer');

        $types = $this->getContainer()->getParameter('eveapi.market_types');

        $documents = array();
        foreach($types as $typeid)
        {
            $market = $marketService->getMarketDatas($typeid);

            $document['itemname'] = $market->getAttributes()->itemname;
            $document['item'] = $market->getAttributes()->item;
            $document['item'] = $market->getAttributes()->item;

            $documents[]= $document;
        }

        print_r($documents);

        $output->writeln('Done');
    }

}
