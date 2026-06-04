<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'semester',
        'school_year'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}