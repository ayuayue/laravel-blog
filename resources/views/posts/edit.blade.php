@extends('layouts.app')
@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3 class="text-center text-warning">编辑文章</h3>
        <hr/>
        <form action="{{ route('posts.update',$post->id)}}" method="post">
            <input type="hidden" name="_method" value="put">
            {{  csrf_field() }}
            <div class="form-group">
                <label for="title" class="control-label">标题: </label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $post->title }}">
            </div>
            <div class="form-group">
                <label for="body" class="control-label">内容:</label>
                <textarea name="body" id="body" cols="30" rows="10" class="form-control">{{ $post->body }}</textarea>
            </div>
            <div class="form-group">
                <lable for="published_at" class="control-label">发表日期:</lable>
                <input id="published_at" name="published_at" type="date" value="{{ $post->published_at->toDateString() }}"
                       class="form-control">
            </div>
            <div class="form-group">
                <button type="submit">提交</button>
            </div>
        </form>
    </div>
@endsection
