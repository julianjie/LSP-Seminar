<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seminar_id',
        'registration_code',
        'registration_status',
        'payment_status',
        'payment_proof',
        'payment_date',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
        ];
    }

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to Seminar
     */
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
