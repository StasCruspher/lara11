<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    protected $table = 'weapon';

    public $timestamps = false;

    protected $fillable = ['name'];

    public function exercises()
    {
        return $this->hasMany(ShootingExercise::class);
    }
}
