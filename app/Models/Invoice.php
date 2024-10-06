<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'number',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
