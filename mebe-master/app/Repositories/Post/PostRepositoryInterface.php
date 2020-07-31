<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface {

    public function getHottestPost();

    public function getRelatedPost($postId);

    public function getDiaryPostToday($userId, $categoryId);

    public function getDiaryPost($userId, $categoryId);

    public function countDiaryDate($user_id);

    public function getBestDiary($number = 6);

    public function getNewDiary($number = 6);

    public function getGuide($number = 6);

    public function getMyDiary($user_id, $number = 6, $id = null, $sort);

    public function getNews($number = 6);

    public function getFavouritePost($user_id, $type);

    public function searchPost($type, $key, $page);

    public function findUpdate(array $data, $attribute);

    public function getLatestDiary($userId);
}
