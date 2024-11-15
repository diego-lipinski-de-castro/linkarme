<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Pivot
{
    protected $table = 'order_item';

    const LINK_STATUSES = [
        'SUBMITTED' => 'Enviado ao portal',
        'PRODUCTION' => 'Em redação',
        'WAITING' => 'Aguardando aprovação',
        'PUBLISHED' => 'Publicado',
    ];

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

        'received',
        'paid',
        'comissioned',

        'link_status',
    ];

    protected $casts = [
        'received' => 'boolean',
        'paid' => 'boolean',
        'comissioned' => 'boolean',
    ];

    protected $appends = [
        'formatted_link_status',
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

    public function getFormattedLinkStatusAttribute(): ?string
    {
        if(!isset(self::LINK_STATUSES[$this->link_status])) {
            return null;
        }

        return self::LINK_STATUSES[$this->link_status];
    }
}
