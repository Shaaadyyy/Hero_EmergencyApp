<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyNum extends Model
{
    use HasFactory;

    protected $table = 'emergency_nums';
    protected $fillable = ['name' , 'number'];

    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = false;
}
