<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    public function index($tag)
    {
        $tag = Tag::findOrFail($tag);

        // Make sure ->blogPosts should be the same name as in the TAG CLASS!!!!
        return view('posts.index', [
            'posts' => $tag->blogPosts, 
            // 'mostCommented' =>[], 
            // 'mostActive' => [] , 
            // 'mostActiveLastMonth' => [],
        ]);
    }
}
