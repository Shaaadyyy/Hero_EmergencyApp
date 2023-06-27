<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name'];

    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = false;

    public function medical()
    {
        return $this->hasMany(Medical::class);
    }
    public function pet()
    {
        return $this->hasMany(Pet::class);
    }
    public function home()
    {
        return $this->hasMany(Home::class);
    }

    public function medical_ar()
    {
        return $this->hasMany(Medical_AR::class);
    }
    public function pet_ar()
    {
        return $this->hasMany(Pet_AR::class);
    }
    public function home_ar()
    {
        return $this->hasMany(Home_AR::class);
    }

}
