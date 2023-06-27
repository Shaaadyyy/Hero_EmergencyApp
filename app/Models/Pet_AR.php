<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet_AR extends Model
{
    use HasFactory;

    protected $table = 'pet__a_r_s';

    protected $fillable = ['caseName', 'description', 'category_id', 'category', 'caseImg', 'caseVideo', 'solution', 'pet_id'];

    protected $hidden = ['created_at', 'updated_at', 'pet_id'];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

}
