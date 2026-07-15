<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'target',
        'is_active',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relation to user creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to filter announcements target for specific role
     */
    public function scopeForRole($query, string $role)
    {
        return $query->whereIn('target', ['all', $role . 's']);
    }

    /**
     * Scope to filter active and published announcements
     */
    public function scopeActiveAndPublished($query)
    {
        return $query->where('is_active', true)
                     ->where('published_at', '<=', now());
    }
}
