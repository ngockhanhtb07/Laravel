<?php

namespace App\Repositories\Children;

use App\Model\Children;
use App\Repositories\EloquentRepository;

class ChildrenRepository extends EloquentRepository implements ChildrenRepositoryInterface
{

    public function getModel()
    {
        return Children::class;
    }

}
