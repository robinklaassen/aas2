<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $isValid = Auth::check();
        $commentId =  $this->route('comment');

        if ($commentId) {
            $comment = Comment::findOrFail($commentId);
            $isValid = $isValid && $comment->user_id == Auth::user()->id;
        }

        return $isValid;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "text" => "required"
        ];
    }
}
