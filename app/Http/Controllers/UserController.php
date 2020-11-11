<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(user $user) {
        return view('users.index', compact('user'));
    }

    public function follow(Request $request, User $user) {
        if ($request->user()->canFollow($user)) {
            $request->user()->following()->attach($user->id);
        }
        return redirect()->back();
    }

    public function unfollow(Request $request, User $user) {
        if ($request->user()->canUnfollow($user)) {
            $request->user()->following()->detach($user->id);
        }
        return redirect()->back();
    }
}
