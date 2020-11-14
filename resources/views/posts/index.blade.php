@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">文章列表页</h1>
        <h3 class="text-center text-warning"><a href="{{ route('posts.create') }}">创建文章</a></h3>
        <hr/>
        @foreach ($posts as $post)
            <div class="text-center">
                <h1><a href="{{ route('posts.show',$post->id) }}">{{ $post->title }}</a></h1>
                <hr>
                <div>{{ $post->published_at->diffForHumans() }}</div>
            </div>
        @endforeach
        <div class="text-center">
            {{ $posts->links() }}
        </div>
    </div>

@endsection