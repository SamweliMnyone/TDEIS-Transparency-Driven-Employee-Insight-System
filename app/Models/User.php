<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Import the Sanctum trait
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Consider removing this, roles are handled by Spatie
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the skills associated with this user.
     *
     * This is a one-to-many relationship, where one user can have many skills.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */


    /**
     * Get the projects that this user is assigned to.
     *
     * This defines the many-to-many relationship with Project through the
     * 'project_employee_assignments' pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_employee_assignments')
            ->withPivot(['required_skill', 'proficiency_level', 'years_of_experience_needed', 'assignment_status'])
            ->withTimestamps();
    }

     /**
     * Get the projects managed by this user.
     *
     * This is a one-to-many relationship, where one user can manage many projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    /**
     * The users that belong to the skill.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'skill_user', 'skill_id', 'user_id')
            ->withPivot('proficiency_level', 'years_of_experience');
    }
    /**
     * Get the project employee assignments for this user.
     *
     * This is a one-to-many relationship, showing all assignments this user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ProjectEmployeeAssignment::class, 'user_id');
    }

    /**
     * Prevent admin role from being removed.
     *
     * This overrides the default syncRoles method to prevent the removal of the
     * 'ADMIN' role.  This is a business logic rule, not a database relationship.
     *
     * @param  mixed  $roles
     * @return mixed
     */
    public function syncRoles($roles)
    {
        if ($this->hasRole('ADMIN')) {
            return $this;
        }

        return parent::syncRoles($roles);
    }

    /**
     * Prevent direct permission changes for admin.
     *
     * This overrides the default syncPermissions method to prevent direct
     * modification of permissions for users with the 'ADMIN' role.
     * This is a business logic rule, not a database relationship.
     *
     * @param  mixed  $permissions
     * @return mixed
     */
    public function syncPermissions($permissions)
    {
        if ($this->hasRole('ADMIN')) {
            return $this;
        }

        return parent::syncPermissions($permissions);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

   public function team(): BelongsToMany
   {
       return $this->belongsToMany(User::class, 'project_user')
           ->withTimestamps();
   }

   /**
    * The project manager for this project
    */
   public function manager(): BelongsTo
   {
       return $this->belongsTo(User::class, 'project_manager_id');
   }

   public function projectAssignments()
{
    return $this->hasMany(ProjectEmployeeAssignment::class, 'user_id');
}
}