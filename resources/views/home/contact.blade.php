@extends('layouts.app')
@section('title','Contact page')

@section('content')

    <h1>CONTACT PAGE</h1>
    <p>this is contact page!</p>

    @can('home.secret')
        <p>
            <a href="{{ route('secret') }}">
                Go to special contact details
            </a>
        </p>
    @endcan

@endsection