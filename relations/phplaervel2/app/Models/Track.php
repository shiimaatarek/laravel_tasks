<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    
    protected $table = 'tracks';  

   
    protected $fillable = ['name','description' ];

    function students()
    {
        return $this->hasMany(Student::class);
    }
}