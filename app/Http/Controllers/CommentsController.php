<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        // You need to be logged in and have admin rights to access
        $this->middleware('auth');
        $this->middleware('admin');
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
        return "test";
    }

    public function store(Requests\CommentRequest $request)
    {
        Comment::create($request->all());
        return redirect($request->query("origin", "/"));
    }

    public function delete(Comment $comment, Request $request)
    {
        return view('comments.delete', compact('comment'));
    }

    public function destroy(Comment $comment, Request $request)
    {
        $comment->delete();
        return redirect($request->query("origin", "/"))->with([
            'flash_message' => 'De opermking is verwijderd!'
        ]);
    }
}
