<?php

declare(strict_types=1);

namespace App\Policies;

use App\Course;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any courses.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCapability('courses::show');
    }

    /**
     * Determine whether the user can view the course.
     *
     * @return mixed
     */
    public function view(User $user, Course $course)
    {
        return $user->hasCapability('courses::show');
    }

    /**
     * Determine whether the user can create courses.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability('courses::create');
    }

    /**
     * Determine whether the user can update the course.
     *
     * @return mixed
     */
    public function update(User $user, Course $course)
    {
        return $user->hasCapability('courses::edit');
    }

    /**
     * Determine whether the user can delete the course.
     *
     * @return mixed
     */
    public function delete(User $user, Course $course)
    {
        return $user->hasCapability('courses::delete');
    }

    /**
     * Determine whether the user can restore the course.
     *
     * @return mixed
     */
    public function restore(User $user, Course $course)
    {
        return $user->hasCapability('courses::create');
    }

    /**
     * Determine whether the user can permanently delete the course.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Course $course)
    {
        return $user->hasCapability('courses::delete');
    }
}
