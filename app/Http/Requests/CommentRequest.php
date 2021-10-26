<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Comment;
use Illuminate\Foundation\Http\FormRequest;
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
        // Only registered users are allowed to comment
        if (! Auth::check()) {
            return false;
        }

        $comment = $this->route('comment');

        // You can only edit your own comments
        if ($comment && $comment->user_id !== Auth::user()->id) {
            return false;
        }

        // Secret comments can only be set by admins
        return ! $this->get('is_secret') || Auth::user()->is_admin === 2;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required',
        ];
    }
}
