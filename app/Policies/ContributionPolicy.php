<?php
namespace App\Policies;

use App\Models\Contribution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContributionPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Contribution $contribution)
    {
        return $user->id === $contribution->user_id;
    }

    public function update(User $user, Contribution $contribution)
    {
        return $user->id === $contribution->user_id;
    }

    public function delete(User $user, Contribution $contribution)
    {
        return $user->id === $contribution->user_id;
    }
}