<?php

namespace Eveonline\EveonlineBundle\Services;

use Elasticsearch\Client;

class ElasticsearchSearcherService
{
    /** @var  Client */
    private $client;
    /** @var ElasticsearchIndexerService  */
    private $indexer;

    public function __construct(Client $client, ElasticsearchIndexerService $indexer)
    {
        $this->client = $client;
        $this->indexer = $indexer;
    }

    public function regionSearch($region)
    {
        $params['index'] = $this->indexer->getIndexName();
        $params['type']  = 'map';
        $params['body']['query']['match']['region'] = $region;

        return $this->client->search($params);
    }

}
