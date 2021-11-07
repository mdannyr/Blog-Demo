<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;


class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating 50 random BlogPosts and assigning it on $posts
        // When running php artisan migrate:refresh --seed to CREATE THEM into the database it would error because user_id doesnt have default value
        //$posts = BlogPost::factory(50)->create();

        // This is better it stills creates them as the above code but doesnt SAVE into the Database just yet   ->make()

        //Asking user how many blog posts does he/she wants into database
        $blogPostCount = (int)$this->command->ask('How many blog posts would you like?', 50);
        $users = User::all();

        
        
        BlogPost::factory($blogPostCount)->make()->each(

            // While its making the 50 Random Blog Posts
            // $users which was created earlier which is the total amount of users(21 in this case) were created on the Database at random also
            // use($users) is needed to get out of scope variables from the function
            // For every post's user_id we SET it to 
            // Randomly we could could assign users to blog posts from the ($users) which is a COLLECTION( total number of users in the database)
            // Collection has a RANDOM method built in to help with this assignment
            // So what this would do is from the list of the ((21) users = $users) that we have it will pick at random one of them 
            // and save each post with their given user(s)
            // To Understand where the variables come from check for the migrations class
            // AddUserToBlogPostsTable

            function ($post) use ($users) {
                $post->user_id = $users->random()->id;
                $post->save();
            }
        );

    }
}
