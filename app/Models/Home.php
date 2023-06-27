<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;

    protected $fillable = ['caseName', 'description', 'category_id', 'category', 'caseImg', 'caseVideo',  'solution', 'case_id'];

    protected $hidden = ['created_at', 'updated_at', 'case_id'];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function home_ar()
    {
        return $this->hasOne(Home_AR::class);
    }

}
