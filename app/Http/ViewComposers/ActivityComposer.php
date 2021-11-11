<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Models\BlogPost;
use App\Models\User;

class ActivityComposer
{
    public function compose(View $view)
    {
        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', 60, function(){
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('users-most-active', 60, function(){
            return User::WithMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 60, function(){
            return User::WithMostBlogPostsLastMonth()->take(5)->get();
        });

        $view->with('mostCommented', $mostCommented);
        $view->with('mostActive', $mostActive);
        $view->with('mostActiveLastMonth', $mostActiveLastMonth);

    }

}