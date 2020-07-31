<?php


namespace App\Repositories\Media;


interface MediaRepositoryInterface
{
    public function getBanners($type);

    public function getListEntity($type);

    public function getImages($id);

    public function deleteImage($id);

    public function findUpdate($conditions, $data);

    public function createManyOrUpdate($mediaArray);

    public function deleteRemainMedia($condition, $mediaIds);

}
