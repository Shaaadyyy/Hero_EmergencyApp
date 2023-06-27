<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    protected $table = 'medicals';
    protected $fillable = ['caseName' , 'description' , 'caseImg' , 'caseVideo' , 'solution', 'category_id' , 'category', 'case_id'];
    protected $hidden = ['created_at', 'updated_at', 'case_id'];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function medical_ar()
    {
        return $this->hasOne(Medical_AR::class, 'medical_id');
    }


}
