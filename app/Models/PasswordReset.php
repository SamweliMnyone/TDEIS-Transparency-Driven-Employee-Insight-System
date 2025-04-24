<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    // Define the table name (Laravel will default to 'password_resets')
    protected $table = 'password_resets';

    // Define the primary key, in case it's different from 'id' (not needed here, but good to specify)
    // protected $primaryKey = 'your_primary_key';

    // Specify which fields are fillable (this is for mass assignment)
    protected $fillable = ['email', 'token', 'created_at'];

    // Disable timestamps if you don't want Eloquent to automatically manage created_at and updated_at
    public $timestamps = false;

    // Optionally, you can define relationships, accessor, mutator methods, etc.
}
