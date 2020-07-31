<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobUpdateRequest;
use App\Http\Requests\StoreJobRequest;
use Illuminate\Http\Request;
use App\Models as Database;
use Illuminate\Support\Facades\Auth;


class JobsController extends Controller
{
    /**
     * Get data category -> select box
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $jobs = Database\Job::getListJob();
        $categories = Database\Category::getListCategory();
        return view('jobs.index', compact('categories', 'jobs'));
    }

    /**
     * Get data categories & Show create page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $creator = Auth::user()->id;
        $categories = Database\Category::getListCategory();
        return view('jobs.create', compact('categories', 'creator'));
    }

    /**
     * Create new job
     *
     * @param StoreJobRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreJobRequest $request)
    {
        $param = $request->all();
        $job = Database\Job::createNewJob($param);
        if ($job) {
            flash('Update Success')->success();
            return redirect()->route('jobs.index');
        } else {
            flash('Update Failse')->error();
        }
    }

    /**
     * Delete one job
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        $success = Database\Job::deleteJob($id);
        if ($success) {
            flash('Delete Success')->success();
        } else {
            flash('Delete Fail')->error();
        }
        return redirect()->route('jobs.index');
    }

    /**
     * Show edit page
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $job = Database\Job::find($id);
        $categories = Database\Category::getListCategory();
        return view('jobs.edit', compact('categories', 'job'));
    }

    /**
     * Update job
     *
     * @param JobUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(JobUpdateRequest $request, int $id)
    {
        $param = $request->all();
        $success = Database\Job::updateJob($param, $id);
        if ($success) {
            flash('Update Success')->success();
            return redirect()->route('jobs.index');
        } else {
            flash('Update False')->error();
        }
    }
}
