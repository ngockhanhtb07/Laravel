<?php

namespace App\Http\Controllers;

use App\Models as Database;
use Illuminate\Http\Request;

class TopController extends Controller
{
    /**
     * Show data jobs
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $jobs = Database\Job::getListJob();
        return view('top.index', compact('jobs'));
    }

}
