<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
        return $this->middleware(['auth'])->only('store','destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()-> with(['user','likes'])->paginate(5); //this are the collections
        return view('posts.index',[
            'posts' => $posts
        ]);
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $request->user()->posts()->create($request->only('body'));
    
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show',[
            'post' => $post
        ]);
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete',$post);

        $post->delete();

        return back();
    }
}
