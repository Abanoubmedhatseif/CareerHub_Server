<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'min_salary',
        'max_salary',
        'city',
        'country',
        'min_exp_years',
        'max_exp_years',
        'expires_at',
        'type',
        'remote_type',
        'experience_level',
        'user_id',
        'status'
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_post_skill');
    }

    public function appliedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'applications')->withPivot('resume_path', 'status', 'id')->withTimestamps();
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class, 'job_industry')->withTimestamps();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
