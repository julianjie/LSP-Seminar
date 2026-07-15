<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'speaker',
        'location',
        'seminar_date',
        'start_time',
        'end_time',
        'quota',
        'price',
        'poster',
        'registration_deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'seminar_date' => 'date',
            'registration_deadline' => 'date',
            'quota' => 'integer',
            'price' => 'integer',
        ];
    }

    /**
     * Scope to only include published seminars
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Relation to registrations
     */
    public function registrations()
    {
        return $this->hasMany(SeminarRegistration::class);
    }

    /**
     * Get the count of approved registrations
     */
    public function approvedRegistrationsCount(): int
    {
        return $this->registrations()->where('registration_status', 'approved')->count();
    }

    /**
     * Check if quota is still available
     */
    public function hasAvailableQuota(): bool
    {
        return $this->approvedRegistrationsCount() < $this->quota;
    }

    /**
     * Get remaining slots
     */
    public function remainingSlots(): int
    {
        $remaining = $this->quota - $this->approvedRegistrationsCount();
        return $remaining > 0 ? $remaining : 0;
    }
}
