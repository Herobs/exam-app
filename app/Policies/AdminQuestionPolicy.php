<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\Question;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminQuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(Teacher $admin, Question $question)
    {
        if ($question->user > 0) {
            return $admin->id === $question->user;
        } else {
            return hasRight($admin->rights, config('rights.RIGHT_PUBLIC'));
        }
    }
}
