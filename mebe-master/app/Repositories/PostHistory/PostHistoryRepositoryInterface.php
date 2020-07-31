<?php

namespace App\Repositories\PostHistory;

interface PostHistoryRepositoryInterface {

    public function updateOrCreate(array $data);
}