<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Models as Database;
use Auth;

class UsersController extends Controller
{
    /**
     * Show data all user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = Database\User::getListUser();
        return view('users.index', compact('users'));
    }

    /**
     * Show Apply Page
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply(int $id)
    {
        $job = Database\Job::find($id);
        $user = Auth::user();
        return view('users.apply', compact('user', 'job'));
    }

    /**
     * Show Create Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Create new User
     *
     * @param StoreUserRequest $request
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreUserRequest $request)
    {
        $param = $request->all();
        $user = Database\User::createUser($param);
        if ($user) {
            flash('Delete Success')->success();
            return redirect()->route('users.index');
        } else {
            flash('Delete Fail')->error();
        }
    }

    /**
     * Show view update
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $user = Database\User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update data user
     *
     * @param Request $request
     * @param int $id
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $param = $request->all();
        $success = Database\User::updateUser($param, $id);
        if ($success) {
            flash('Update Success')->success();
            return redirect()->route('users.index');
        } else {
            flash('Update Failse')->error();
        }
    }

    /**
     * Delete User
     *
     * @param int $id
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(int $id)
    {
        $success = Database\User::deleteUser($id);
        if ($success) {
            flash('Delete Success')->success();
            return redirect()->route('users.index');
        } else {
            flash('Delete Failse')->error();
        }
    }

    /**
     *  Create Application
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeApplication(Request $request)
    {
        $param = $request->all();
        $success = Database\Application::saveApplication($param);
        if ($success) {
            flash('Apply Success')->success();
            return redirect()->route('jobs.index');
        } else {
            flash('Apply Failse')->error();
        }
    }
}
