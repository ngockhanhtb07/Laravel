<?php


namespace App\Repositories\CategoryGroup;


use App\Model\CategoryGroup;
use App\Repositories\EloquentRepository;

class CategoryGroupRepository extends EloquentRepository implements CategoryGroupRepositoryInterface
{
    public function getModel()
    {
        return CategoryGroup::class;
    }


}
