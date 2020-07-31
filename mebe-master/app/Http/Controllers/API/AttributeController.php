<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Product\AttributeRepositoryInterface;
use App\Traits\CommonResponse;

class AttributeController extends Controller {
    use CommonResponse;

    protected $_attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
    }
}