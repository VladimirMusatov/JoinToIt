<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email', 
        'logo_src',
        'website'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
