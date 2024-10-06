<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'client_id',
        'broken',
        'ssl',
        'receipt_date',
        'delivery_date',
        'payment_date',
        'charged',
        'paid',
        'markup',
        'comission',
        'company',
        'status',
        'invoice_id',
    ];

    protected $casts = [
        'comission' => 'integer',
    ];

    protected $appends = [
        'formatted_charged',
        'formatted_paid',
        'formatted_markup',
        'formatted_comission',
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
            ->withTimestamps();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function checks()
    {
        return $this->morphMany(Check::class, 'checkable');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopeOfClient($query, $client)
    {
        return $query->where('client_id', $client);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getFormattedStatusAttribute()
    {
        return self::STATUSES[$this->status];
    }

    public function getFormattedChargedAttribute()
    {
        return 'R$ '.number_format($this->charged / 100, 2, ',', '.');
    }

    public function getFormattedPaidAttribute()
    {
        return 'R$ '.number_format($this->paid / 100, 2, ',', '.');
    }

    public function getFormattedMarkupAttribute()
    {
        return 'R$ '.number_format($this->markup / 100, 2, ',', '.');
    }

    public function getFormattedComissionAttribute()
    {
        return 'R$ '.number_format($this->comission / 100, 2, ',', '.');
    }
}
