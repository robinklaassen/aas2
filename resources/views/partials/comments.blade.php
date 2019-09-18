<h1 class="caption">
    Overige informatie
    <a href="{{ url('comments/new') }}?origin={{Request::path()}}&type={{urlencode($type)}}&key={{$key}}">
        <i class="glyphicon glyphicon-plus" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Informatie toevoegen"></i>
    </a>
</h1>

@foreach($comments as $comment)
<div class="row comment-item" style="margin-bottom: 7px;">
    <div class="col-md-12">{{$comment->text}}</div>
    <div class="col-md-12">
        <span class="by">
            @if($comment->is_secret)
            <i class="glyphicon glyphicon-eye-close"></i>
            @endif
            Door {{$comment->user->volnaam}} op {{$comment->updated_at}}

            @can('update', $comment)
            <a href="{{action('CommentsController@edit', [$comment->id, 'origin' => Request::path()])}}"><i class="glyphicon glyphicon-edit"></i></a>
            @endcan

            @can('delete', $comment)
            <a href="{{action('CommentsController@delete', [$comment->id, 'origin' => Request::path()])}}"><i class="glyphicon glyphicon-remove"></i></a>
            @endcan
        </span>
    </div>
</div>
@endforeach