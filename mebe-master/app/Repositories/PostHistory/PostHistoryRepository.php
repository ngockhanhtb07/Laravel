<?php

namespace App\Repositories\PostHistory;

use App\Model\PostHistory;
use App\Repositories\EloquentRepository;

class PostHistoryRepository extends EloquentRepository implements PostHistoryRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     * @return string
     */
    public function getModel()
    {
        return PostHistory::class;
    }

    public function updateOrCreate(array $data) {
        return $this->_model->updateOrCreate($data);
    }
}