<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShootingGradeRequirement extends Model
{
    protected $fillable = ['shooting_exercise_id', 'grade_name', 'condition_description'];

    public function exercise()
    {
        return $this->belongsTo(ShootingExercise::class, 'shooting_exercise_id');
    }
}
