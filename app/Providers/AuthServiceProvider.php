<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Policies\BlogPostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        BlogPost::class => BlogPostPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define( 'home.secret', function($user){
            return $user->is_admin;
        });

        // Declaring defintions for the gate 
        // Gate::define returns bool

        // To check if the user's id matches the post they created themselves to update the blogPost
        // Gate::define(
        //     'update-post',

        //     // We are naming a method "update-post" which checks accepts $user (dynamic variable could be anything) in this case (User from the 'users' schema table)
        //     // We also accepts a $blogPost (dynamic variable) which this case is (Blog Post from 'blog_posts' schema table)
        //     // It checks if the user's primary key matches the blogPost foreign key in the add_user_to_blog_posts_table.php (check for reference in database migration table)
        //     // They share a bound because blogPost belongs to User::class
        //     function ($user, $blogPost) {
        //        return $user->id == $blogPost->user_id;
        //     }
        // );

        // // To check if the user's id matches the post they created themselves to delete the blogPost
        // Gate::define(
        //     'delete-post',

        //     // We are naming a method "delete-post" which checks accepts $user (dynamic variable could be anything) in this case (User from the 'users' schema table)
        //     // We also accepts a $blogPost (dynamic variable) which this case is (Blog Post from 'blog_posts' schema table)
        //     // It checks if the user's primary key matches the blogPost foreign key in the add_user_to_blog_posts_table.php (check for reference in the database migration table)
        //     // They share a bound because blogPost belongs to User::class
        //     function ($user, $blogPost) {
        //        return $user->id == $blogPost->user_id;
        //     }
        // );


        // Gate::define('post.update',[BlogPostPolicy::class,'update']);
        // Gate::define('post.delete',[BlogPostPolicy::class,'delete']);

        // Creates Resoucre class for post.create post.delete post.edit ETC
        Gate::resource('post', BlogPostPolicy::class);

        

        // The Gate::before is a method inside the Gate Class that will be called FIRST
        // BEFORE ANY OTHER METHODS(Gate Checks' The above methods') inside the GATE CLASS
        
        
        Gate::before(

            // Based on results of this function $ablility will change depending on the logic
            function($user, $ability)
            {
            // If the user is admin then it would return true on all GATE CHECKS (Gate methods) no matter what
            // Remeber this function will be called first before any gate checks
            // Poweful Method Gate::before takes MAJOR Priority
            // if it doesnt return nothing then it would continue with the other GATE CHECKS(methods) inside the class 

            // This if statement is checking if the user is admin and to only to update the post
            if($user->is_admin && in_array($ability,['update','delete']))
            {
                return true;
            }

        });



        // // Same concept in terms of POWER with GATE::BEFORE
        // // The difference it goes through Gate Checks(methods) first then it goes through this and makes CHANGES at the very end!!!
        // Gate::after(
        //     function($user, $ability , $result)
        // {
        //     if($user->is_admin && in_array($ability,['update-post']))
        //     {
        //         return true;
        //     }
        // });

        //


    }
}
