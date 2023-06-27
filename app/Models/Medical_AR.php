<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_AR extends Model
{
    use HasFactory;

    protected $table = 'medical__a_r_s';
    protected $fillable = ['caseName' , 'description' , 'caseImg' , 'caseVideo' , 'solution', 'category_id' , 'category', 'medical_id'];
    protected $hidden = ['created_at', 'updated_at', 'medical_id'];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function medical()
    {
        return $this->belongsTo(Medical::class, 'medical_id');
    }

}
