<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function jobPosts(): BelongsToMany
    {
        return $this->belongsToMany(JobPost::class, 'job_post_skill');
    }
}
