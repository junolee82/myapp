<div class="media media__create__comment {{ isset($parentId)? 'sub' : 'top' }}">

    @include('users.partial.avatar', ['user' => $currentUser, 'size' => 32])

    <div class="media-body">
        <form action="{{ route('articles.comments.store', $article->id) }}" method="POST" class="form-horizontal">
            {!! csrf_field() !!}

            @if(isset($parentId))
                <input type="hidden" name="parent_id" value="{{ $parentId }}">
            @endif

            <div class="form-group {{$errors->has('content')? 'has-error' : '' }}">
                <textarea name="content" class="form-control">{{ old('content') }}</textarea>
                {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
            </div>

            <div class="text-right">
                <div class="btn-group">
                <input type="submit" class="btn btn-primary btn-sm" value="댓글저장" />
                </div>
            </div>
        </form>
    </div>

</div>