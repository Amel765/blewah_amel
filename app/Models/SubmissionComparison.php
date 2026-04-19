<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionComparison extends Model
{
    protected $fillable = ['submission_id', 'criteria_id_1', 'criteria_id_2', 'value'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function criteria1()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id_1');
    }

    public function criteria2()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id_2');
    }
}
