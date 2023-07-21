<?php

namespace App\Policies;

use App\Models\User;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update($user, $task)
    {
        return $user->id == $task->user_id;
    }

    public function delete($user, $task)
    {
        return $user->id == $task->user_id;
    }

    public function move($user, $task)
    {
        return $user->id == $task->user_id;
    }
    public function complete($user, $task)
    {
        return $user->id == $task->user_id;
    }

    public function check($user, $task)
    {
        return $user->id == $task->user_id;
    }
}
