<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home_AR extends Model
{
    use HasFactory;

    protected $table = 'home__a_r_s';

    protected $fillable = ['caseName', 'description', 'category_id', 'category', 'caseImg', 'caseVideo', 'solution', 'home_id'];

    protected $hidden = ['created_at', 'updated_at', 'home_id'];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }

}
