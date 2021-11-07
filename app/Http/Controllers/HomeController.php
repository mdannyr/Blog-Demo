<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    

    public function home()
    {
        // dd(Auth::check());
        // dd(Auth::id());
        // dd(Auth::user());

        return view('home.index');
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function secret()
    {
        // Make sure its located in the right folder file location
        // For this example below it has to be located on the 
        // App/resouces/views/home/secret.blade.php should be the exact location 
        // Keep in mind you created the home folder yourself
        // laravel convention is "name of the foler" . "name of the file.blade.php"
        // This case the folder is home and file is secret
        // which should be home.secret
        return view('home.secret');
    }
}
