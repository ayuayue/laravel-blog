@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center text-warning">{{ $post->title }}</h3>
        <hr/>
        <div class="pull-left">{{ $post->author->name }}</div> <div class="pull-right">{{$post->published_at}}</div>
        <br/>
        <hr/>
        {{ $post->body }}

    </div>
@endsection