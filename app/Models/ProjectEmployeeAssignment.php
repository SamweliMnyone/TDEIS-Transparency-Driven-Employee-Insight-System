<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEmployeeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'required_skill',
        'proficiency_level',
        'years_of_experience_needed',
        'assignment_status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requiredSkill()
    {
        return $this->belongsTo(Skill::class, 'required_skill');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // Make sure 'user_id' is the correct foreign key
}




}
