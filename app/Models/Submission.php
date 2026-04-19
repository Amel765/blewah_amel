<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['user_id', 'status', 'result_data', 'title', 'description', 'is_hidden_from_admin'];

    protected $casts = [
        'result_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }

    public function comparisons()
    {
        return $this->hasMany(SubmissionComparison::class);
    }

    public function scores()
    {
        return $this->hasMany(SubmissionScore::class);
    }
}
