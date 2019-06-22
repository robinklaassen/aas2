<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentsController extends Controller
{
    public function __construct()
    {
        // You need to be logged in and have admin rights to access
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function edit(Comment $comment, Requests\Request $request)
    {
        return "test";
    }

    public function update(Comment $comment, Requests\CommentRequest $request)
    {
        $comment->update($request->all());
        return redirect($request->query("origin", "/"));
    }

    public function create(Requests\Request $request)
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
