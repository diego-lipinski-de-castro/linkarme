<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;

class Order extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public const STATUSES = [
        'WAITING' => 'Aguardando',
        'PRODUCTION' => 'Produção do artigo',
        'SUBMITTED' => 'Enviado para vendedor',
        'PROCESSING' => 'Aguardando publicação',
        'PUBLISHED' => 'Publicado',
        'INVOICE' => 'Invoice',
        'COMPLETED' => 'Finalizado',
    ];

    protected $fillable = [
        'number',
        'status',
        'client_id',
        'type_id',
        'receipt_date',
        'delivery_date',
        'payment_date',
        'company',
        'invoice_id',
    ];

    protected $casts = [
        'comission' => 'integer',
    ];

    protected $appends = [
        'formatted_status',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (! blank($order->number)) {
                return;
            }

            do {
                $order->setAttribute('number', Str::upper(Str::random(6)));
            } while (self::where('number', $order->number)->exists());
        });
    }

    public function items()
    {
        return $this
            ->belongsToMany(Site::class, 'order_item')
            ->using(OrderItem::class)
            ->withPivot(([
                'id',

                'order_id',
                'site_id',
                'seller_id',

                'link',

                'cost',
                'sale',
                'comission',

                'cost_coin',
                'sale_coin',
                'comission_coin',

                'received',
                'paid',
                'comissioned',

                'link_status',
            ]))
            ->withTimestamps();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function checks(): MorphMany
    {
        return $this->morphMany(Check::class, 'checkable');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function scopeSmart($query, $search): Builder
    {
        $search = str_replace('#', '', $search);

        return $query->where('number', 'like', "$search%");
    }

    public function scopeOfClient($query, $client): Builder
    {
        return $query->where('client_id', $client);
    }

    public function scopeStatus($query, $status): Builder
    {
        return $query->where('status', $status);
    }

    public function getFormattedStatusAttribute()
    {
        return self::STATUSES[$this->status];
    }

    public function getTotalSaleAttribute()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->sale;
        }

        return $total;
    }
}
