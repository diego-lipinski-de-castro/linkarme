<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    const BANKS = [
        'MERCURY' => 'Mercury',
        'PAYPAL' => 'Paypal',
        'WISE' => 'Wise',
    ];

    protected $fillable = [
        'number',
        'client_id',
        
        'value',
        'value_coin',
        
        'discount',
        'discount_coin',

        'paid',

        'bank',
        'payment_link',
    ];

    protected $casts = [
        'paid' => 'boolean',
    ];

    protected $appends = [
        'formatted_bank',
        'calculated_value',
    ];

    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (! blank($invoice->number)) {
                return;
            }

            do {
                $invoice->setAttribute('number', Str::upper(Str::random(6)));
            } while (self::where('number', $invoice->number)->exists());
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedBankAttribute(): ?string
    {
        if(!isset(self::BANKS[$this->bank])) {
            return null;
        }

        return self::BANKS[$this->bank];
    }

    public function getCalculatedValueAttribute()
    {
        $total = 0;

        foreach ($this->orders as $order) {
            $total += $order->total_sale;
        }

        return $total;
    }
}
