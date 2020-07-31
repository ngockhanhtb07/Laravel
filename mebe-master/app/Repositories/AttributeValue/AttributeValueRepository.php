<?php
namespace App\Repositories\AttributeValue;

use App\Model\AttributeValue;
use App\Repositories\EloquentRepository;


class AttributeValueRepository extends EloquentRepository implements AttributeValueRepositoryInterface
{

    public function getModel()
    {
        return AttributeValue::class;
    }


}