<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'profile_image',
        'cover_image',
        'role'
    ];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->profile_image) {
                Storage::disk('public')->delete('profile-images/' . basename($user->profile_image));
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isEmployer()
    {
        return $this->role == 'employer';
    }

    public function isCandidate()
    {
        return $this->role == 'candidate';
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function appliedJobs(): BelongsToMany
    {
        return $this->BelongsToMany(JobPost::class, 'applications')->withPivot('resume_path', 'status')->withTimestamps();
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(JobPost::class);
    }
}
