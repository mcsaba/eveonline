<?php

namespace Eveonline\EveonlineBundle\Controller;

use Eveonline\EveonlineBundle\Services\ElasticsearchSearcherService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function regionAction($name,  Request $request)
    {
        /** @var ElasticsearchSearcherService $searcher */
        $searcher = $this->get('elasticsearch_searcher');

        $hits = $searcher->regionSearch($name);

        $documents = array();
        foreach ($hits['hits']['hits'] as $hit)
        {
            $documents[] = $hit['_source'];
        }
        unset($hits);

        return $this->render('EveonlineEveonlineBundle:Search:region.html.twig',
            array('documents' => $documents, 'regionName' => $name)
        );
    }
}
