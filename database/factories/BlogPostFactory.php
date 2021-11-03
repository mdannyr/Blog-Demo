<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    // The default state of any new BlogPost Factory to auto generate data with given parameters of length and type 
    public function definition()
    {
        return [
            'title' =>$this->faker->sentence(10),
            'content' =>$this->faker->paragraphs(5, true),
        ];
    }



    // Im making a new state with given parameters i chose
    // if one of them is empty then it goes back to default state of the factory
    public function newTitle()
    {
       return $this->state([
            'title' => 'Test Title',
            'content' => 'Content of the blog post!',
       ]);
    }
}
