<?php

namespace App\Http\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

class ServiceAPIClient
{
    private $client = null;

    /**
     * ServiceAPIClient constructor.
     * @param $email
     * @param $key
     */
    public function __construct()
    {

        $this->client = new Client();
    }

    /**
     * @param $method
     * @param $uri
     * @param $parameters
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call($method, $uri, $parameters)
    {
        switch (strtoupper($method)) {
            case ('POST' || 'PUT' || 'PATCH'):
                $data = [RequestOptions::JSON => $parameters];
                break;
            default :
                $data = [RequestOptions::QUERY => $parameters];
                break;
        }
        try {
            $response = $this->client->request($method, $uri, $data);
            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            return $e->getResponse();
        }
    }

}