<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    protected $fillable = [
        'user_id', 'job_id', 'resume_path', 'status'
    ];
    public $incrementing = true;
    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
        $this->save();
    }

    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();
    }
}
