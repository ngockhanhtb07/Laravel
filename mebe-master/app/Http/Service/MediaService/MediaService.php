<?php


namespace App\Http\Service\MediaService;


use App\Traits\ConsumesExternalService;

class MediaService
{
    use ConsumesExternalService;

    public $baseUri;
    public $secret;

    public function __construct()
    {
        $this->baseUri = config('services.medias.base_uri');
        $this->secret = config('services.medias.secret');
    }

    public function uploadMedia($data){
        return $this->performRequest('POST', '/api/media/upload', $data);
    }

}