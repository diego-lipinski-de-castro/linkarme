<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Pivot
{
    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'site_id',
        
        'link',
        
        'cost',
        'cost_coin',
        
        'sale',
        'sale_coin',

        'comission',
        'comission_coin',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function getFormattedCostAttribute()
    {
        return 'R$ '.number_format($this->cost / 100, 2, ',', '.');
    }

    public function getFormattedSaleAttribute()
    {
        return 'R$ '.number_format($this->sale / 100, 2, ',', '.');
    }

    public function getFormattedComissionAttribute()
    {
        return 'R$ '.number_format($this->comission / 100, 2, ',', '.');
    }
}
