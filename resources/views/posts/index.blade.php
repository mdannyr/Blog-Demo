@extends('layouts.app')

@section('title','Blog Posts')

@section('content')

{{--  to understand what is going on look at the BootStrap in your saved bookmarks in google under C# -> BootStrap  --}}
<div class="row">
      <div class="col-8">
        @forelse ($posts as $post)
          {{--  @include('posts.partials.post')  --}}
        <p>

          <h3> 
            @if ($post->trashed())
              <del>
            @endif
              <a class="{{ $post->trashed() ? 'text-muted' : ''}}"
                   href="{{ route('posts.show',['post'=>$post->id]) }}"> {{ $post->title }} </a>
            @if ($post->trashed())
              </del>
            @endif
            
           </h3> 
  

          @updated(['date' => $post->created_at, 'name' => $post->user->name])
          @endupdated
          
          @tags(['tags' => $post->tags])
          @endtags

          @if ($post->comments_count)
                <p>{{$post->comments_count  }} comments</p>
          @else
                <p>No Comments Yet!</p> 
          @endif

          @auth
              @can('update',$post)
                  <a href="{{ route('posts.edit',['post'=>$post->id]) }}" 
                      class="btn btn-primary"> Edit
                  </a>
              @endcan
          @endauth
                      {{--  @cannot('delete',$post)
                          <p> You can't delete this post</p>
                      @endcannot  --}}
          @auth
              @if (!$post->trashed())
                  @can('delete',$post)
                      <form class="d-inline" 
                          action="{{ route('posts.destroy',['post'=>$post->id]) }}" method="POST">
                          @csrf
                          @method('DELETE')

                          <input type="submit" value="Delete!" class="btn btn-primary">
                      </form>
                  @endcan
              @endif
          @endauth
          </p>
      @empty
        <p>No blog post yet! </p>
      @endforelse
      </div>
        <div class="col-4">
          <div class="container">
              <div class="row">
                @card(['title' => 'Most Commented',])
                      @slot('subtitle')
                          What people are currently talking about
                      @endslot
                      @slot('items')
                           @foreach ($mostCommented as $posts)
                                <li class="list-group-item">
                                  <a href="{{ route('posts.show',['post'=> $post->id]) }}">
                                      {{ $posts->title }}
                                  </a>
                                </li>
                            @endforeach
                      @endslot
                @endcard
              </div>
              <div class="row mt-4"> {{--  Margin top 4 = mt-4  --}}
                  @card(['title' => 'Most Active',])
                      @slot('subtitle')
                          Writers with most posts written
                      @endslot
                      @slot('items', collect($mostActive)->pluck('name'))
                  @endcard
              </div>
              <div class="row mt-4"> {{--  Margin top 4 = mt-4  --}}
                @card(['title' => 'Most Active Last Month',])
                      @slot('subtitle')
                          Writers with most posts written last month
                      @endslot
                      @slot('items', collect($mostActiveLastMonth)->pluck('name'))
                @endcard
            </div>
          </div>
        </div>
</div>

@endsection



