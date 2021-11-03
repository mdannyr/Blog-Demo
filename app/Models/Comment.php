<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; //HERE
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory; //HERE
    //blog_post_id

    // if the function is called post then laravel by default will look for post_id
    public function blogPost()
    {    
        //if you wanted to use another name for the following key, there 
        // a second parameter like this Example return $this->belongsTo(BlogPost::class,'post_id'); 
        // But if you do this make sure you do change your foreign key on the file app->database->migration-> Schema ->
        //$table->unsignedBigInteger('post_id')->index();
        //$table->foreign('post_id')->references('id')->on('blog_posts');
        // Some ways to configure it out!
        
        return $this->belongsTo(BlogPost::class);
    }
}
