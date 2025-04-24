<?php

// app/Policies/SkillPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Skill;

class SkillPolicy
{
    public function update(User $user, Skill $skill)
    {
        return $user->id === $skill->user_id;
    }

    public function delete(User $user, Skill $skill)
    {
        return $user->id === $skill->user_id;
    }
}