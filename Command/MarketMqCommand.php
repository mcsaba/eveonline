<?php

namespace Eveonline\EveonlineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MarketMqCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('eveonline:marketmq2es')
            ->setDescription('EMDR  market data to Elasticsearch');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $context = new \ZMQContext();
        $subscriber = $context->getSocket(\ZMQ::SOCKET_SUB);

        // Connect to the first publicly available relay.
        $subscriber->connect("tcp://relay-us-central-1.eve-emdr.com:8050");
        // Disable filtering.
        $subscriber->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, "");

        while (true) {
            // Receive raw market JSON strings.
            $market_json = gzuncompress($subscriber->recv());
            // Un-serialize the JSON data to a named array.
            $market_data = json_decode($market_json);
            // Dump the market data to stdout. Or, you know, do more fun things here.
            print_r($market_data);
        }

        $output->writeln('Done');
    }

}
