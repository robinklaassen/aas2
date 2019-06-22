<div class="row comment-item" style="margin-bottom: 7px;">
    <div class="col-md-12">{{$comment->text}}</div>
    <div class="col-md-12">
        <span class="by">
            @if($comment->is_secret)
            <i class="glyphicon glyphicon-eye-close"></i>
            @endif
            Door {{$comment->user->volnaam}} op {{$comment->created_at}}

            @if($comment->user_id == \Auth::user()->id )
            <a href="{{action('CommentsController@edit', [$comment->id, 'origin' => Request::path()])}}"><i class="glyphicon glyphicon-edit"></i></a>
            @endif
            @if(\Auth::user()->is_admin || $comment->user_id == \Auth::user()->id )
            <a href="{{action('CommentsController@delete', [$comment->id, 'origin' => Request::path()])}}"><i class="glyphicon glyphicon-remove"></i></a>
            @endif
        </span>
    </div>
</div>