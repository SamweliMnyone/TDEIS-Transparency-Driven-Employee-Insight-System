<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'objective',
        'scope',
        'estimated_time',
        'estimated_cost',
        'project_manager_id',
    ];

    /**
     * Get the user that is assigned as the project manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skill', 'project_id', 'skill_id')
            ->withPivot('proficiency_level', 'years_of_experience_needed');
    }

    /**
     * The assignments for the project.
     */
    public function assignments()
    {
        return $this->hasMany(ProjectEmployeeAssignment::class);
    }
    /**
     * Get the users (employees) assigned to this project.
     *
     * This defines the many-to-many relationship with User through the
     * 'project_employee_assignments' pivot table.  It also retrieves the
     * extra attributes stored in the pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_employee_assignments')
            ->withPivot(['required_skill', 'proficiency_level', 'years_of_experience_needed', 'assignment_status'])
            ->withTimestamps(); // Include created_at and updated_at for the pivot table
    }

    /**
     * Get the project employee assignments for this project.
     *
     * This is a one-to-many relationship, where one project can have many
     * employee assignments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */



public function manager()
{
    return $this->belongsTo(User::class, 'project_manager_id');
}

}
