<?php
namespace App\Repositories\Attribute;

use App\Http\Resources\Product\ProductCollection;
use App\Model\Attribute;
use App\Repositories\EloquentRepository;
use App\Repositories\Product\AttributeRepositoryInterface;

class AttributeRepository extends EloquentRepository implements AttributeRepositoryInterface
{

    public function getModel()
    {
        return Attribute::class;
    }


}