<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory; //HERE
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory; //HERE
    //blog_post_id

    use SoftDeletes;
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
    
    public function scopeLatestScope(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot()
    {
        parent::boot();
    }
}
