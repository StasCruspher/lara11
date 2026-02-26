<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShootingExercise extends Model
{
    protected $table = 'shooting_exercise';

    public $timestamps = false;

    protected $fillable = [
            'code',
            'name',
            'weapon_id',
            'target_description',
            'distance_m',
            'ammo_count',
            'time_minutes',
            'time_seconds',
            'time_unlimited',
            'shooting_position',
            'execution_order',
        ];

    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }

    public function grades()
    {
        return $this->hasMany(ShootingGradeRequirement::class);
    }
}
