@extends('layouts.app')

@section('title', 'Update the post')

@section('content')



<form action="{{ route('posts.update', ['post'=>$post->id]) }}" method="POST">


    {{-- // Create CSRF token to make it secure to gather data from a valid user --}}
    @csrf
    @method('PUT')

    @include('posts.partials.form')
    

     {{-- creates an input submit button and naming it "Update" --}}
    <div><input type="submit" value="Update" class="btn btn-primary btn-block"></div>
</form>


@endsection