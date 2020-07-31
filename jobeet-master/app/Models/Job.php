<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class Job extends Model
{
    protected $table = 'jobs';

    protected $dates = [
        'deleted_at'
    ];

    protected $perPage = 10;

    protected $fillable = [
        'category_id',
        'creator',
        'title',
        'description',
        'is_public',
    ];

    /**
     * foreign key User (oneToMany)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'creator');
    }

    /**
     * Set relationship with category table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get all data Job
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[] | null
     */
    public static function getListJob()
    {
        return Job::paginate();
    }

    /**
     * Get data one Job
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection | null
     */
    public static function getJob(int $id)
    {
        return Job::find($id);
    }

    /**
     * Delete one Job
     *
     * @param int $id
     * @return Boolean
     */
    public static function deleteJob(int $id)
    {
        return Job::find($id)->delete();
    }

    /**
     * Create new job
     *
     * @param  $param
     * @return \Illuminate\Database\Eloquent\Collection | null
     */
    public static function createNewJob($request)
    {
        $creator = Auth::user()->id;
        $param = [
            'category_id' => $request['category_id'],
            'creator' => $creator,
            'title' => $request['title'],
            'description' => $request['description'],
        ];
        return Job::create($param);
    }

    /**
     * Update data one job
     *
     * @param Request $request
     * @param int $id
     * @return boolean
     */
    public static function updateJob($params, int $id)
    {
        return Job::find($id)->update($params);
    }
}
