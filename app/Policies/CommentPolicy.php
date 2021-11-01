<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasCapability('comments::show');
    }

    /**
     * Determine whether the user can view the comment.
     *
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        return ($user->hasCapability('comments::show::secret') && $comment->is_secret)
            || (! $comment->is_secret && $user->hasCapability('comments::show'));
    }

    /**
     * Determine whether the user can create comments.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability('comments::create');
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $this->view($user, $comment)
            && ((! $comment->is_secret && $user->hasCapability('comments::edit'))
                || ($comment->is_secret && $user->hasCapability('comments::edit::secret'))
                || $comment->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $this->view($user, $comment)
            && ($user->hasCapability('comments::delete') || $comment->user_id === $user->id);
    }

    /**
     * Determine whether the user can restore the comment.
     *
     * @return mixed
     */
    public function restore(User $user, Comment $comment)
    {
        return $this->delete($user, $comment);
    }

    /**
     * Determine whether the user can permanently delete the comment.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Comment $comment)
    {
        return $this->delete($user, $comment);
    }
}
