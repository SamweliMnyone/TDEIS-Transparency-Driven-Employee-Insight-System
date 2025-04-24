<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'date',
        'file_path',
        'user_id'
    ];

    protected $dates = ['date'];

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to get file URL
    public function getFileUrl()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    protected $casts = [
        'date' => 'date' // or 'datetime' if you need time information
    ];
}