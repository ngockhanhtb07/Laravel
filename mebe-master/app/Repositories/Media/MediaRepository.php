<?php


namespace App\Repositories\Media;


use App\Http\Resources\Media\MediaCollection;
use App\Model\Entity;
use App\Model\Media;
use App\Repositories\EloquentRepository;
use Carbon\Carbon;

class MediaRepository  extends EloquentRepository implements MediaRepositoryInterface
{
    public function getModel()
    {
        return Media::class;
    }

    public function getBanners($type)
    {
        $medias =  $this->_model
            ->where('entity_id',$type)
            ->where('is_enabled',1)
            ->orderBy('index')
            ->orderBy('created_at')
            ->get();
        $data = new MediaCollection($medias);
        return $data;
    }

    public function getListEntity($type)
    {
        $entities = Entity::where('type',1)->get();
        return $entities;
    }

    public function getImages($id)
    {
        $images = $this->_model->where('entity_id',$id)->get()->load('User');
        return $images;
    }

    public function deleteImage($id)
    {
        $result = $this->_model->findOrFail($id);
        $result->delete();
    }

    public function findUpdate($conditions, $data) {
        $media = $this->_model->where($conditions)->first();
        if ($media) {
            $media->update($data);
        }
        return $media;
    }

    public function createManyOrUpdate($mediaArray) {
        if (is_array($mediaArray) && $mediaArray) {
            foreach ($mediaArray as $media) {
                $newMedia = $this->_model->where([
                    'owner_id' => $media['owner_id'],
                    'entity_id' => 5,
                    'type' => 'image',
                    'user_id' => $media['user_id'],
                    'external_id' => $media['external_id']
                ])->get();
                if ($newMedia->count() == 0) {
                    $this->_model->insert($media);
                } else {
                    $newMedia->first()->update($media);
                }
            }
        }
    }

    public function deleteRemainMedia($condition, $mediaIds) {
        // delete remain media when delete media in diary
        $media = $this->_model->where($condition);
        if (count($mediaIds) > 0) {
            $media->whereNotIn('media_id', $mediaIds);
        }
        $media->delete();
    }

}
