@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center text-warning">{{ $post->title }}</h3>
        <hr/>
        <div class="pull-left">{{ $post->author->name }}</div> <div class="pull-right">{{$post->published_at}}</div>
        <br/>
        <hr/>
        {{ $post->body }}
        <hr/>
        <div class="pull-left"><a href="{{ route('posts.edit',$post->id) }}">编辑文章</a></div>
        <div class="pull-right">
            <form action="{{ route('posts.destroy',$post->id) }}" method="post">
                <input type="hidden" name="_method" value="delete">
                {{ csrf_field() }}
                <div class="form-group">
                    <button type="submit">删除文章</button>
                </div>
            </form>
        </div>
    </div>
@endsection