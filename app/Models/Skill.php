<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'skill_name',
        'proficiency',
        'years_of_experience',
        'description',
    ];

    /**
     * Get the user that this skill belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_skill', 'skill_id', 'project_id')
            ->withPivot('proficiency_level', 'years_of_experience_needed');
    }

    /**
     * The users that belong to the skill.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_user')
                    ->withPivot('years_of_experience');
    }


}
