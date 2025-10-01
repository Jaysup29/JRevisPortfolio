<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    
    use HasFactory, SoftDeletes;
    
    protected $table = 'tbl_skills';
    protected $fillable = [
        'name',
        'icon_path',
        'category',
        'status',
        'proficiency',
        'sort_order'
    ];

    protected $casts = [
        'proficiency' => 'integer',
        'sort_order' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrderedByProficiency($query)
    {
        return $query->orderBy('proficiency', 'desc');
    }

}
