<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function blogPosts()
    {
        // Change the name of the "pivot" to tagged
        return $this->belongsToMany(BlogPost::class)->withTimestamps()->as('tagged');
    }


}