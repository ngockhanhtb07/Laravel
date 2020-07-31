<?php
namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

trait ConsumesExternalService
{

    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {

        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }
        $data[RequestOptions::HEADERS] = $headers;
        switch (strtoupper($method)) {
            case ('POST' || 'PUT' || 'PATCH'):
                $data[RequestOptions::JSON] = $formParams;
                break;
            default :
                $data[RequestOptions::QUERY] = $formParams;
                break;
        }
        $response = $client->request($method, $requestUrl, $data);

        return $response->getBody()->getContents();
    }
}
