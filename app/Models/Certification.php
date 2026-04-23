<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_certifications';

    protected $fillable = [
        'name',
        'issuer',
        'issued_at',
        'icon_path',
        'description',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'sort_order' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
