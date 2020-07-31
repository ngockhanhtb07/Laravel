<?php

namespace App\Http\Service\GHN;

use Illuminate\Support\Facades\App;
use App\Http\Service\ServiceAPIClient;

class GHNServiceAPIClient {

    protected $_serviceAPIClient;
    protected $_environment;
    protected $_host;
    protected $_token;

    public function __construct(ServiceAPIClient $serviceAPIClient)
    {
        $this->_serviceAPIClient = $serviceAPIClient;
        $this->_environment = App::environment();
        $this->_host = config('services.shipping.ghn.environment.'.$this->_environment);
        $this->_token = config('services.shipping.ghn.account.token');
    }

    public function getToken() {
        $isShippingAvailable = config('services.shipping.ghn.enabled');
        if ($isShippingAvailable) {
            $uri = $this->_host.config('services.shipping.ghn.api.token');
            $parameters = [
                'email' => config('services.shipping.ghn.account.email'),
                'password' => config('services.shipping.ghn.account.password'),
                'token' => $this->_token
            ];
            return $this->_serviceAPIClient->call('POST', $uri, $parameters);
        }
        return false;
    }

    public function calculateShippingFee($parameters) {
        $isShippingAvailable = config('services.shipping.ghn.enabled');
        if ($isShippingAvailable) {
            $uri = $this->_host.config('services.shipping.ghn.api.calculateFee');
            $parameters['token'] = $this->_token;
            return $this->_serviceAPIClient->call('POST', $uri, $parameters);
        }
        return false;
    }

    public function getDistricts() {
        $isShippingAvailable = config('services.shipping.ghn.enabled');
        if ($isShippingAvailable) {
            $uri = $this->_host.config('services.shipping.ghn.api.districts');
            $parameters = [
                'token' => $this->_token
            ];
            return $this->_serviceAPIClient->call('POST', $uri, $parameters);
        }
        return false;
    }

    public function getServiceAvailableList() {

    }

    public function getServiceID() {

    }

    public function createShipment($parameters) {
        $isShippingAvailable = config('services.shipping.ghn.enabled');
        if ($isShippingAvailable) {
            $uri = $this->_host.config('services.shipping.ghn.api.shipment.create');
            $parameters['token'] = $this->_token;
            $result = $this->_serviceAPIClient->call('POST', $uri, $parameters);
            return $result;
        }
        return false;
    }

    public function cancelShipment($parameters) {
        $isShippingAvailable = config('services.shipping.ghn.enabled');
        if ($isShippingAvailable) {
            $uri = $this->_host.config('services.shipping.ghn.api.shipment.cancel');
            $parameters['token'] = $this->_token;
            $result = $this->_serviceAPIClient->call('POST', $uri, $parameters);
            return $result;
        }
        return false;
    }



}