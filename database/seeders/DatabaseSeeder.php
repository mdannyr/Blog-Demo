<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    

    public function run()
    {
        //Ask the user if he wants to refresh the database by default is set to no
        if($this->command->confirm('Do you want to refresh the database'))
        {
            // if yes then it would migrate refresh automactilly because of the below method call
            // this does php aritan migrate:refresh with out me typing it in the terminal
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refresed');
        }

        $this->call(
            [UsersTableSeeder::class, 
            BlogPostsTableSeeder::class, 
            CommentsTableSeeder::class
        ]);

        
    }
}
