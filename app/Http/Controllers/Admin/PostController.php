<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {

        $post = Post::create($request->all());

        if ($file = $request->file('file')) {
            $rutaGuardarImg = 'images/posts/';
            $filePost = "file" . date('YmdHis') . "." . $file->getClientOriginalExtension();
            $file->move($rutaGuardarImg, $filePost);
            
            $post->image()->create([
                'url' => $filePost,
            ]);
        }

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('admin.posts.edit', compact('post'))->with('info', 'El post se creo con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $post->update($request->all());

        if ($file = $request->file('file')) {
            $rutaGuardarImg = 'images/posts/';
            $filePost = "file" . date('YmdHis') . "." . $file->getClientOriginalExtension();
            $file->move($rutaGuardarImg, $filePost);
            
            if($post->image){
                $post->image()->update([
                    'url' => $filePost,
                ]);
            }else{
                $post->image()->create([
                    'url' => $filePost,
                ]);
            }
        }

        if ($request->tags) {
            $post->tags()->detach($post->tags);
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.edit', compact('post'))->with('info', 'El post se actualzo con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
