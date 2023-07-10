<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/7/18
 * Time: 11:47 AM
 * To change this template use File | Settings | File Templates.
 */
use SilverStripe\Core\Environment;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Mediasuite\MsSsLoggingElasticsearch\CustomESHandler;
use SilverStripe\Core\Injector\Injector;

$logger = Injector::inst()->get(LoggerInterface::class);

if ($logger instanceof Logger
    && Environment::getEnv('ELASTIC_SEARCH_INDEX')
    && Environment::getEnv('ELASTIC_SEARCH_HOST')
    && Environment::getEnv('ELASTIC_SEARCH_APIKEY')) {
    $options = array(
        'index' => Environment::getEnv('ELASTIC_SEARCH_INDEX')      // Elastic index name
    );
    $config = [
        'connections' => [
            [   'host' => Environment::getEnv('ELASTIC_SEARCH_HOST'),
                'port' => 443,
                'transport' => 'Https',
                'headers' => [
                'Authorization' => 'ApiKey' . Environment::getEnv('ELASTIC_SEARCH_APIKEY')
                ]
            ]
        ]
    ];
    $client = new Client($config);
    $handler = new CustomESHandler($client, $options);
    
    $logger->pushHandler($handler);
}
