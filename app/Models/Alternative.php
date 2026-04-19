<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $fillable = ['name', 'submission_id'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
