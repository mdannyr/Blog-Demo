<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{   
    //Attributes the BlogPost has make sure it makes sense with the schema we build database migration!
    protected $fillable = ['title','content', 'user_id'];
    use HasFactory;

    use SoftDeletes;


    public function comments()
    {
        // By using latest in here it makes default without calling it explictly in the PostController.php class
        // Sorting the Comments in decedning order by default
        return $this->hasMany(Comment::class)->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Adding Query for the local BlogPosts
    // This is explict function you need to call it so it could be apply
    public function scopeLatestScope(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        //once laravel calls withCount you have access to "name of model inside this class"_count
        // withCount("requires the name of the function inside this class which is => comments)
        // comments is called because inside this class is called public function comments() LOOK ABOVE !!!!!
        // So you order in decending order by comments class
        // make sure is comments + _ = comments_count
        // This works also COMMENTS_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }
    

    public static function boot(){

        static::addGlobalScope(new DeletedAdminScope);
        parent::boot();

        //global query scope I made check App\Scopes\LatestScope
        // make sure you have the libary in here by "use" keyword like include in C#

        //static::addGlobalScope(new LatestScope);
        

        //Creating a Delete Event!
        static::deleting(function (BlogPost $blogPost)
        {
            // Make sure you delete comments based on what is inside in this class check up in your function
            // look up
            // This will delete all related models from this Particular Model($blogPost)
            // It will delete all the comments from the database when it comes to this specfic $blogPost
            // So this will let us pass through this foreign key constraint and every related model will
            // be deleted along with the BlogPost
            // Problem with this is that it permamented delete the comments that is associated with the blog post
            // even if the blog post was soft delete ( even if it still has deleted_at atrribute with DATE TIME on it)
            // To fix this issue we need to create AddSoftDeletesTpCommentsTable
            
            $blogPost->comments()->delete();        
        });

        static::restoring(function (BlogPost $blogPost)
        {
            // You need this command and function to fully restore the  Blog Post
            // with the comments associate with it or else
            // The comments would still be soft Deleted assuming if we have SoftDelete migration table for this tho!
             
            $blogPost->comments()->restore();
            
        });


        
    }
    
}
