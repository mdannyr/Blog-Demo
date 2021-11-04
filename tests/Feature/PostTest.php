<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class PostTest extends TestCase
{
    // Something wrong with this line of code not working propertly
    // actingAs giving an error
    //$this->actingAs($user);
    // $this->actingAs($this->user())

    use RefreshDatabase;

    public function testNoBlogPostsWhenNothingInDatabase()
    {


        $response = $this->get('/posts');
        $response->assertSeeText('NO POSTS FOUND!');
    }

    public function testSee1BlogPostWhenThereIs1OnlyWithNoComments()
    {
        // Arrange

        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('Test Title');

        //Testing a new post with no comments on it. When a new post is created there is no comments on it!
        $response->assertSeeText('No Comments Yet!');


        // Test if it created a new title on the database called blog_posts
        // blog_posts is under database/migrations/ "named of 'schema create database in the function' "
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Test Title'
        ]);
    }

    public function testSee1BlogPostWhenThereIs1OnlyWithComments()
    {
        // Arrange

        $post = $this->createDummyBlogPost();


        // To auto generate 4 comments into the database with the blog_post_id that is current on
        Comment::factory(4)->create([
            'blog_post_id' => $post->id,
        ]);

        $response = $this->get('/posts');

        //To check if it reads the 4 comments in the screen because we return the total number of comments per post in which this case is 4
        $response->assertSeeText('4 comments');
    }



    public function testStoreValid()
    {
       // $user = $this->user();

        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];

        // Something wrong with this line of code not working propertly
        // actingAs giving an error
        //$this->actingAs($user);


        // Simulating a HTTP request that would be in the browser like I would be submitting a form
        // It should redirect to the actual blog post page so we can check for the status
        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)         // Http code for succesful redirect being displayed and its called immediately after make in the the new blog post
            ->assertSessionHas('status'); // To check for the flash message shown when the new blog post is created. "status" is the name of the variable.

        $this->assertEquals(session('status'), 'The blog post was created!');
    }

    public function testStoreFails()
    {

        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        // Simulating a HTTP request that would be in the browser like I would be submitting a form
        // It should redirect to the actual blog post page so we can check for the status
        $this->actingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)         // Http code for succesful redirect being displayed and its called immediately after make in the the new blog post
            ->assertSessionHas('errors'); // errors is created automatically by Laravel to be used globally


        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');

        // $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
        // dd($messages);

        // dd($messages->getMessageBag());

    }

    public function testUpdateValid()
    {
        // Arrange
        // Create a blog post inside the database, and only then we will verify if it actually gets notified 


        $postData = [
            'title' => 'Test Title',
            'content' => 'Content of the blog post'
        ];

        $post = new BlogPost();
        $post->fill($postData);
        $post->save();

        $this->assertDatabaseHas('blog_posts', $postData);

        // parameters for the new form updated title and content
        $params = [
            'title' => 'A new named title',
            'content' => 'New Content was changed'
        ];

        // updating the post and checking the status of the new page and checks if really got redirected also
        // To update you could call PUT or PATCH METHOD because its on the route::list 
        $this->actingAs($this->user())
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)         // Http code to check if it was redirect succesfullu and being displayed and its called immediatediatlly also
            ->assertSessionHas('status');

        // check on the status/flash nessage for a succesfull update
        $this->assertEquals(session('status'), 'Blog post was updated!');

        // Checking on the database of the original title and content are missing. Should come out true to pass test succcefully because they were change!
        $this->assertDatabaseMissing('blog_posts', $postData);

        // Checking on the database if the new update title and content are SAVED. should come out true to pass test
        $this->assertDatabaseHas('blog_posts', $params);
    }

    public function testDeletePost()
    {
        // Arrange
        $user = $this->user();

        $postData = [
            'title' => 'Test Title',
            'content' => 'Content of the blog post'
        ];

        //$post = $this->createDummyBlogPost();

        // Create a new Blog Post
        $post = new BlogPost();
        $post->fill($postData);
        $post->save();

        $this->assertDatabaseHas('blog_posts', $postData);


        // you change it based on the METHOD you see in the route::list in which case its DELETE
        $this->actingAs($this->user())
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)              // Http code to check if it was redirect succesfullu and being displayed and its called immediatediatlly also
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!');

        $this->assertDatabaseMissing('blog_posts', $postData);
    }

    private function createDummyBlogPost(): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'Test Title';
        // $post->content ='Content of the blog post!';
        // $post->save();

        $post = BlogPost::factory()->newTitle()->create();



        return $post;
    }
}
