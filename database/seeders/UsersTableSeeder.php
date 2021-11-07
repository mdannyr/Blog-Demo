<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Command is inside the seeder class and 20(second parameter) is the default value if user puts nothing
        // Asking user how many users in the database
        // max function to get the biggest number between user and 1 just to avoid not getting 0 as a possible input!!!
        $userCount = max((int)$this->command->ask('How many user would you like?', 20),1);

        User::factory()->johnDoe()->create();
        User::factory($userCount)->create();

        // Returns what type of class is it 
        // $doe = App\Models\User
        // $else = Illuminate\Database\Eloquent\Collection
        //dd(get_class($doe), get_class($else));

        // To add $doe user into a new variable which comes from a "collection" which is $else
        //$users = $else->concat([$doe]);
    }
}
