<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $table = 'site_scripts';

    protected $fillable = [
        'title',
        'key',
        'status',
        'position',
        'content',
        'details',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'details' => 'array',
        'order' => 'integer',
    ];

    protected $attributes = [
        'status' => true,
        'order' => 0,
        'position' => 'body_end',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByPosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }
}
