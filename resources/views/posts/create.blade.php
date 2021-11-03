@extends('layouts.app')

@section('title', 'Create the post')

@section('content')


<!-- Create a new form in html to gather information from user and sending it to post.store-->
<form action="{{ route('posts.store') }}" method="POST">


    {{-- // Create CSRF token to make it secure to gather data from a valid user --}}
    @csrf

    @include('posts.partials.form')
    

     {{-- creates an input submit button and naming it "Create" --}}
    <div><input type="submit" value="Create" class="btn btn-primary btn-block"></div>
</form>


@endsection