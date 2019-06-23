<div class="row comment-item" style="margin-bottom: 7px;">
    <div class="col-md-12">{{$comment->text}}</div>
    <div class="col-md-12">
        <span class="by">
            @if($comment->is_secret)
            <i class="glyphicon glyphicon-eye-close"></i>
            @endif
            Door {{$comment->user->volnaam}} op {{$comment->created_at}}

            @can('update', $comment)
            <a href="{{action('CommentsController@edit', [$comment->id, 'origin' => Request::path()])}}"><i class="glyphicon glyphicon-edit"></i></a>
            @endcan
            
            @can('delete', $comment)
            <a href="{{action('CommentsController@delete', [$comment->id, 'origin' => Request::path()])}}"><i class="glyphicon glyphicon-remove"></i></a>
            @endcan
        </span>
    </div>
</div>