<p>

    @foreach ($tags as $tag)
        {{-- Look at bootstrap to find the icons/layouts for most things  "badge-lg" is created in App/Sass/app.scss folder --}}
        {{-- Make sure you run npm run dev after every change for the bootstrap! --}}
        <a href="{{ route('posts.tags.index' , ['tag' => $tag->id]) }}" 
            class="badge badge-success badge-lg"> {{ $tag->name }}</a>
        
    @endforeach
</p>