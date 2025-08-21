<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_number',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($card) {
            // Auto-generate 10-digit card number if not set
            if (empty($card->card_number)) {
                $card->card_number = rand(1000000000, 9999999999);
            }

            // Auto-set expiry date = 1 year from now if not set
            if (empty($card->expiry_date)) {
                $card->expiry_date = Carbon::now()->addYear();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
