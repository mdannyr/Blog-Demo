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
          <p class="text-muted">
              Added {{ $post->created_at->diffForHumans() }}
              by {{ $post->user->name }}
          </p>

          @if ($post->comments_count)
                <p>{{$post->comments_count  }} comments</p>
          @else
                <p>No Comments Yet!</p> 
          @endif
          @can('update',$post)
              <a href="{{ route('posts.edit',['post'=>$post->id]) }}" 
                  class="btn btn-primary"> Edit
              </a>
          @endcan

                      {{--  @cannot('delete',$post)
                          <p> You can't delete this post</p>
                      @endcannot  --}}
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
          </p>
      @empty
        <p>No blog post yet! </p>
      @endforelse
      </div>
        <div class="col-4">
          <div class="container">
              <div class="row">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Most Commented</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            What people are currently talking about
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                      @foreach ($mostCommented as $posts)
                          <li class="list-group-item">
                            <a href="{{ route('posts.show',['post'=> $post->id]) }}">
                                {{ $posts->title }}
                            </a>
                          </li>
                      @endforeach
                    </ul>
                </div>
              </div>
              <div class="row mt-4"> {{--  Margin top 4 = mt-4  --}}
                  <div class="card" style="width: 100%;">
                      <div class="card-body">
                          <h5 class="card-title">Most Active</h5>
                          <h6 class="card-subtitle mb-2 text-muted">
                              Users with most posts written
                          </h6>
                      </div>
                      <ul class="list-group list-group-flush">
                        @foreach ($mostActive as $user)
                            <li class="list-group-item">
                              {{ $user->name}}
                            </li>
                        @endforeach
                      </ul>
                  </div>
              </div>
              <div class="row mt-4"> {{--  Margin top 4 = mt-4  --}}
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Most Active Last Month</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            Users with most posts written last month
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                      @foreach ($mostActiveLastMonth as $user)
                          <li class="list-group-item">
                            {{ $user->name}}
                          </li>
                      @endforeach
                    </ul>
                </div>
            </div>
          </div>
        </div>
</div>

@endsection



