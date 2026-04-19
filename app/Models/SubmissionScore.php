<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionScore extends Model
{
    protected $fillable = ['submission_id', 'alternative_id', 'criteria_id', 'value'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
