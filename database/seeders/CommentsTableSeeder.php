<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        $posts = BlogPost::all();

        if($posts->count() === 0)
        {
            $this->command->info('There are no blog posts. So that means you cant have any comments. Sorry');
            return;
        }

        $commentCount = (int)$this->command->ask('How many comments would you like?', 150);
        Comment::factory($commentCount)->make()->each(

            // Same Concept we did in the above function but this time we are trying to assign
            // For all comments (all 150) that was created to assign the foreign key
            // So for each indivdual comment could get assign to some post(s) and the posts come from
            // The collection of $posts that was created earlier which in this case is (50)
            // Save each comment
            function ($comment) use ($posts) {
                $comment->blog_post_id = $posts->random()->id;
                $comment->save();
            }
        );
    }
}
