<?php

namespace MonstreX\VoyagerSite\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $table = 'site_redirects';

    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'is_active',
        'hits',
        'last_hit_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hits' => 'integer',
        'status_code' => 'integer',
        'last_hit_at' => 'datetime',
    ];

    protected $attributes = [
        'hits' => 0,
        'status_code' => 301,
        'is_active' => true,
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function findByPath(string $path): ?self
    {
        $path = '/'.ltrim($path, '/');

        return static::active()
            ->where('from_url', $path)
            ->first();
    }

    public function recordHit(): void
    {
        $this->increment('hits');
        $this->update(['last_hit_at' => now()]);
    }
}
