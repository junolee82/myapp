@extends('layouts.app')

@section('content')

    <div class="page-header">
        <h4>
            <a href="{{ route('articles.index') }}">
            {{--  {{ trans('forum.title') }}  --}}목록
            </a>
            <small>
            / {{--  {{ trans('forum.articles.create') }}  --}}새 포럼 글 쓰기
            </small>
        </h4>
    </div>

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="form__article">
    {!! csrf_field() !!}

    @include('articles.partial.form')

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">저장하기</button>
    </div>
    </form>

@stop
