<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use function GuzzleHttp\Promise\queue;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function blogPost()
    {
        return $this->HasMany(BlogPost::class);
    }

    // Returns query of a user with the most blog posts from biggest to smallest
    public function scopeWithMostBlogPosts(Builder $query)
    {
        // Make sure inside withCount("is the model function name properly")
        // orderBy('"lower case of the model function" + _ + in between each word') very stupid simple syntax
        // blogPost + _ = blog_post_count
        // blogPost + _ = blog_Post_count     /// Its not case senstive 
        return $query->withCount('blogPost')->orderBy('blog_Post_count', 'desc');
    }

    public function scopeWithMostBlogPostsLastMonth(Builder $query)
    {
        return $query->withCount(['blogPost' => function (Builder $query) {
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
        }])
        ->has('blogPost', '>=', 2)
        ->orderBy('blog_Post_count', 'desc');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
