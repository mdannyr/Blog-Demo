<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        // // For each regular post in the big list of $posts
        // foreach($posts as $post)
        // {
        //     // For each of those post pick from the top loop it goes to this loops and loops through comments on that post
        //     foreach($post->comments as $comment)
        //     {
        //         // Prints out the content on that comment
        //         echo $comment->content ;
        //     }
        // }

        return view('posts.index', [
                                                                                //MostCommented and WithMostBlogPosts are query methods so there not case senstive so that means MOSTCOMMENTED should call the same function also
            'posts' => BlogPost::latest()->withCount('comments')
                    ->with('user')->with('tags')->get(),        // 'comments' is the name of the database inside migrations
            // 'mostCommented' => $mostCommented,       //The method is from scopeMostCommented but you dont put'scope'-> take(how many AT MOST do we want !!! in this case 5)
            // 'mostActive' => $mostActive,          // same concept as in the above line
            // 'mostActiveLastMonth' => $mostActiveLastMonth,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$this->authorize('post.create');

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //To recieve incoming Data from user from HTML FORM and store it
    public function store(StorePost $request)
    {

        // This make sure the user input data passes some validation you make in the StorePost Class
        $val = $request->validated();

        // Add a user_id attribute and assigns based on the user is log in 
        // gets the user_id  we need this because if you want to add more blog post to the website
        $val['user_id'] = $request->user()->id;

        //Creating new blog post on the database    
        // Keep in mind if you are adding blog post and if its misssing any data make sure you update
        // The fillable array in this case we need to add [user_id] because we need to update the database and its require bcuz
        // Thie is important attribute to keep our schema solid and sql database in good standing 
        $post = BlogPost::create($val);

        $request->session()->flash('status', 'The blog post was created!');

        // Redirect the user to a new web page with the new title and content
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Creating a tag for the blog post
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function() use($id){
            return BlogPost::with('comments')->with('tags')->with('user')->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";
        

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach($users as $session => $lastVist){
            if($now->diffInMinutes($lastVist) >= 1)
            {
                $diffrence--;
            }
            else
            {
                $usersUpdate[$session] = $lastVist;
            }
        }

        if(
            !array_key_exists($sessionId, $users) 
            || $now->diffInMinutes($users[$sessionId]) >= 1
        )
        {
            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);

        if(!Cache::tags(['blog-post'])->has($counterKey))
        {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        }
        else
        {
            Cache::tags(['blog-post'])->increment($counterKey, $diffrence); 
        }
          

        $counter = Cache::tags(['blog-post'])->get($counterKey);
        //
        //abort_if(!isset($this->posts[$id]), 404);

        // Its able to query based on the function we created inside the comment.php class
        //Inside the comment model we created a function(scope) to create a query function so it could be use in here
        // check the model for more inside info
        // return view('posts.show', ['post' => BlogPost::with(['comments' => function ($query) {
        //     return $query->latest();
        // }])->findOrFail($id)]);


        // The reason this work like in the about commented code because in the BlogPost.php class is already set
        // Check the BlogPost::class in the Comment function its already set the query so we dont have to call it here
        // Saves time 
        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter,
        ]);

        // return view('posts.show', ['post' => BlogPost::with('comments')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findorFail($id);

        // when the user that is login try to press on the edit button 
        // check the user is allow to edit the form
        // check update method for more detail on this

        // Does the same thing as the below code


        //$this->authorize('update', $post);
        // if( Gate::denies('update-post',$post) )
        // {
        //     abort(403, 'You cant edit this blog post');
        // }

        // Same as the abouve code but calling authroize to write less code because of auto mapping 
        // it only works if you dont change the name of function inside of laravel

        $this->authorize($post);

        // Keeping this authorize so it wont get confusing in the future !
        //$this->authorize('post.update',$post);  

        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {

        // To check if the BlogPost exist in the database if it doesnt then it would sent 404 error page
        $post = BlogPost::findOrFail($id);

        // Checking if it denies access to update the blog based on the user is login in
        // For reference go to AuthSerivceProvider.php file which is located on Http/Controllers/Providers folder
        //    if( Gate::denies('update-post',$post) )
        //    {
        //        abort(403, 'You cant edit this blog post');
        //    }
        // $this->authorize('update',$post);

        $this->authorize($post);

        //Goes through validation method to check for inputs are valid such length and min/max size of the
        // title and content of the user post there trying to update and then the post is saved in the database
        $val = $request->validated();
        $post->fill($val);
        $post->save();

        // Flashes a message when the post was updated
        $request->session()->flash('status', 'Blog post was updated!');

        // Sends you to another link when it everything went good with blog post id
        // The route send it to view page(html) to display the redirect page whatever it is
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        // if( Gate::denies('delete-post',$post) )
        // {
        //     abort(403, 'You cant delete this blog post');
        // }

        // Keeping this authorize so it wont get confusing in the future !
        //$this->authorize('post.delete', $post);       


        //$this->authorize('delete', $post);


        // Same as the abouve code but calling authroize to write less code because of auto mapping 
        // it only works if you dont change the name of function inside of laravel

        $this->authorize($post);
        $post->delete();

        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
