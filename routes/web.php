<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/','HomeController@home')->name('home.index');

Route::get('/', [HomeController::class, 'home'])
    ->name('home.index');
    //->middleware('auth');

Route::get('/contact', [HomeController::class, 'contact'])
    ->name('home.contact');

// Route::get('/contact','HomeController@contact')->name('contact');

Route::get('/single', AboutController::class);

Auth::routes();




Route::resource('posts', PostsController::class);//->only(['index', 'show', 'store', 'create','edit','update']);

// Route::get('/posts', function () use ($posts) {
//     // compact($posts) === ['posts' => $posts])
//     //dd(request()->all());
//     dd((int)request()->query('page', 1));

//     return view('posts.index', ['posts' => $posts]);
// });

// Route::get('/posts/{id}', function ($id) use ($posts) {

//     abort_if(!isset($posts[$id]), 404);
//     return view('posts.show', ['post' => $posts[$id]]);
// })->name('posts.show');

$posts = [
    1 => [
        'title' => 'Intro to Laravel',
        'content' => 'This is a short intro to Laravel',
        'is_new' => true,
        'has_comments' => true
    ],

    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false
    ],
    3 => [
        'title' => 'Intro to Golang',
        'content' => 'This is a short intro to Laravel',
        'is_new' => false,

    ],
];

Route::get('/recent-posts/{days_ago?}', function ($daysAgo = 20) {
    return 'Post from ' . $daysAgo . ' days ago';
})->name('posts.recent.index')->middleware(('auth'));               // Middleware auth will make it so you need to be authenticated to vist this route

Route::prefix('/fun')->name('fun.')->group(function () use ($posts) {


    Route::get('responses', function () use ($posts) {
        return response($posts, 201)
            ->header('Content-Type', 'application/json')
            ->cookie('My_COOKIE', 'Piotr Jura', 3600);
    })->name('responses');

    Route::get('/redirect', function () {

        return redirect('/contact');
    })->name('redirect');

    Route::get('back', function () {

        return back();
    })->name('back');

    Route::get('named-route', function () {

        return redirect()->route('posts.show', ['id' => 1]);
    })->name('nammed-route');

    Route::get('away', function () {

        return redirect()->away('http://google.com');
    })->name('away');

    Route::get('json', function () use ($posts) {

        return response()->json($posts);
    })->name('json');

    Route::get('download', function () use ($posts) {

        return response()->download(public_path('/daniel.jpg'), 'face.jpg');
    })->name('dowmload');
});
