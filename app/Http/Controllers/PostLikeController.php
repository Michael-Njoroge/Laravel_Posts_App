<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Post $post, Request $request)
    {
        if($post->likedBy($request->user())){
            return response(null,409);
        }

        $post->likes()->create([
            'user_id' => $request->user()->id,
        ]);
  
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Request $request)
    {
         $request->user()->likes()->where('post_id',$post->id)->delete();

         return back();
    }
}