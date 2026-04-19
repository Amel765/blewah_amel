<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = ['name', 'type', 'weight', 'submission_id'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'criteria_id');
    }

    public function comparisons1()
    {
        return $this->hasMany(Comparison::class, 'criteria_id_1');
    }

    public function comparisons2()
    {
        return $this->hasMany(Comparison::class, 'criteria_id_2');
    }
}
