<?php

namespace App\Http\Controllers;


use App\Models\Post;
use App\Events\NewPost;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;


class PostController extends Controller
{
    public function index(): Response
    {   
        return Inertia::render('Posts', [
            'posts' => Post::latest()->get()->take(5)
        ]);
    }

    public function store(Request $request)
    {
        Post::create($request->validate([
            'message' => 'required|string|max:30'
        ]));

        NewPost::dispatch(Post::latest()->first());

        // return redirect(route('index'));
    }
}
