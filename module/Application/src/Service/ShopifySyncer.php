<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 11:44
 */

namespace Application\Service;


use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Http\Client;

class ShopifySyncer
{
    private $client;
    private $cache;
    private $configs;

    public function __construct($configs)
    {
        $this->configs = $configs;

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(null, $this->configs['http_client'] ?? $this->configs['http_client']);
        }

        return $this->client;
    }

    /**
     * @param mixed $client
     * @return ShopifySyncer
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function getReviews($app)
    {
        $this->getClient()->setUri("{$this->configs['url']}/{$app}/reviews.json");
        $response = $this->getClient()->send();

        return json_decode($response->getBody(), true);
    }
}