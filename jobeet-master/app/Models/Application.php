<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Application extends Model
{
    protected $table = 'applications';

    protected $fillable = [
        'job_id',
        'user_id',
        'message',
    ];

    /**
     * Save Application
     *
     * @param $request
     * @return->App\Models\Application|null
     */
    public static function saveApplication($request)
    {
        return Application::create($request);
    }
}
