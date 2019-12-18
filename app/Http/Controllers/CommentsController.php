<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentsController extends Controller
{


    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function edit(Comment $comment, Request $request)
    {
        $origin = $request->query("origin", "/");
        return view("comments.edit", compact('comment', 'origin'));
    }

    public function update(Comment $comment, CommentRequest $request)
    {
        $comment->update($request->all());

        return redirect($request->query("origin", "/"));
    }

    public function create(Request $request)
    {
        $type = $request->query("type");
        $key = $request->query("key");

        if (!$type || !$key) {
            abort(400, 'Invalid input, expected entity type and entity key.');
        }

        $origin = $request->query("origin", "/");
        return view("comments.create", compact('origin', 'type', 'key'));
    }

    public function store(CommentRequest $request)
    {

        $model = $request->get("entity_type");
        $entity = $model::findOrFail($request->get("entity_id"));

        if ($request->input("is_secret") && !\Auth::user()->hasCapability("comments::edit::secret")) {
            abort(403, 'Cannot create secret comment');
        }

        $comment = new Comment($request->all());
        $comment->user_id = \Auth::user()->id;


        $entity->comments()->save($comment);

        return redirect($request->query("origin", "/"));
    }

    public function delete(Comment $comment, Request $request)
    {
        $this->authorize("delete", $comment);
        return view('comments.delete', compact('comment'));
    }

    public function destroy(Comment $comment, Request $request)
    {
        $comment->delete();
        return redirect($request->query("origin", "/"))->with([
            'flash_message' => 'De opmerking is verwijderd!'
        ]);
    }
}
