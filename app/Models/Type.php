<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'available',
        'cost',
        'sale',
        'cost_coin',
        'sale_coin',
    ];

    public function getAvailableAttribute()
    {
        return false;
    }

    public function getCostAttribute()
    {
        return 0;
    }

    public function getSaleAttribute()
    {
        return 0;
    }

    public function getCostCoinAttribute()
    {
        return 'BRL';
    }

    public function getSaleCoinAttribute()
    {
        return 'BRL';
    }
}
