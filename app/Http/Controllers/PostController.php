<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Events\PostCreated;

class PostController extends Controller
{
    public function index(Request $request, Post $post) {
        $allPosts = $post->whereIn('user_id', $request->user()->following()->pluck('users.id')->push($request->user()->id))->with('user');
        $posts = $allPosts->orderBy('created_at', 'desc')->take(10)->get();
        return response()->json([
            'posts' =>$posts,
        ]);
    }

    public function create(Request $request, Post $post) {
        //create post
        $createdPost = $request->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body
        ]);
        //boradcast
        broadcast(new PostCreated($createdPost, $request->user()))->toOthers();
        //return response
        return response()->json($post->with('user')->find($createdPost->id));
    }
}
