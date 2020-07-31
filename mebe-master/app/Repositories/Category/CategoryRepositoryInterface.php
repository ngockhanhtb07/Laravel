<?php

namespace App\Repositories\Category;

interface CategoryRepositoryInterface {

    public function getProductsByCategory($categoryId, $request);

    public function getAttributesByCategory($categoryId);

    public function getDiaryCategory();

    public function getCategoryByGroup($groupName, $type,$all = 0);

}
