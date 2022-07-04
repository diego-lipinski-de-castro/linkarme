<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

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
        'site_id',
        'client_id',
        'seller_id',
        'url',
        'broken',
        'ssl',
        'receipt_date',
        'delivery_date',
        'payment_date',
        'charged',
        'paid',
        'markup',
        'comission',
        'status',
    ];

    protected $casts = [
        'comission' => 'integer',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function checks()
    {
        return $this->morphMany(Check::class, 'checkable');
    }

    public function scopeOfSite($query, $site)
    {
        return $query->where('site_id', $site);
    }

    public function scopeOfClient($query, $client)
    {
        return $query->where('client_id', $client);
    }

    public function scopeOfSeller($query, $seller)
    {
        return $query->where('seller_id', $seller);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getFormattedStatusAttribute()
    {
        return self::STATUSES[$this->status];
    }
}
