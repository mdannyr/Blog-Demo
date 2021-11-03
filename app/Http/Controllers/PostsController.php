<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PostsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::enableQueryLog();


        // Lazy Loading
        // $posts = BlogPost::all();

        // Assign the posts variable to all the BlogPosts that contain comments
        // Also gets() all those posts and put intp $posts variable

        // A better query to break it down and be faster and performance
        // $posts = BlogPost::with('comments')->get();

        // // For each regular post in the big list of $posts
        // foreach($posts as $post)
        // {
        //     // For each of those post pick from the top loop it goes to this loops and loops through comments on that post
        //     foreach($post->comments as $comment)
        //     {
        //         // Prints out the content on that comment
        //         echo $comment->content ;
        //     }
        // }

        // dd(DB::getQueryLog());


        // posts => BlogPost::all() it gets all the Blog posts with no querys thats why Query is important
        //return view('posts.index', ['posts' => BlogPost::all()]);
        
        
        return view('posts.index', [
            'posts' => BlogPost::withCount('comments')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //To recieve incoming Data from user from HTML FORM and store it
    public function store(StorePost $request)
    {
        $val = $request->validated();

        //Creating new blog post on the database    
        $post = BlogPost::create($val);



        $request->session()->flash('status', 'The blog post was created!');

        // Redirect the user to a new web page with the new title and content
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        //abort_if(!isset($this->posts[$id]), 404);
        return view('posts.show', ['post' => BlogPost::with('comments')->findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        // To check if the BlogPost exist in the database if it doesnt then it would sent 404 error page
        $post = BlogPost::findOrFail($id);

        $val = $request->validated();
        $post->fill($val);
        $post->save();

        $request->session()->flash('status', 'Blog post was updated!');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
