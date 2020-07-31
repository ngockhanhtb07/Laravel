<?php


namespace App\Http\Service\GatewayService;


use App\Traits\ConsumesExternalService;

class GatewayService
{
    use ConsumesExternalService;

    public $baseUri;
    public $secret;

    public function __construct($token)
    {
        $this->baseUri = config('services.gateway.base_uri');
        $this->secret = 'Bearer '.$token;
    }

    public function likeCommentNotification($data) {
        return $this->performRequest('POST', '/api/v1/like/notification', $data);
    }

    public function updateBabyInfo($data) {
        return $this->performRequest('POST', '/api/v1/updateBabyInfo', $data);
    }

}
