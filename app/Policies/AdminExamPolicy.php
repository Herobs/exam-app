<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\Exam;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminExamPolicy
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

    public function update(Teacher $admin, Exam $exam)
    {
        return $admin->id === $exam->holder;
    }
}
