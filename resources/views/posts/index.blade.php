@extends('layouts.app')

@section('content')
    @foreach ($posts as $post)
    <div class="text-center">
        <h1><a href="{{ route('posts.show',$post->id) }}">{{ $post->title }}</a></h1>
        <hr>
        <div>{{ $post->body }}</div>
        <div>{{ $post->published_at->diffForHumans() }}</div>
    </div>
    @endforeach
    <div class="text-center">
        {{ $posts->links() }}
    </div>
@endsection