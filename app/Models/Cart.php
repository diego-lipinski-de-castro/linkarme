<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable;

class Cart extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'client_id',
    ];

    protected $casts = [
    ];

    protected $appends = [
    ];

    public function scopeOfClient($query, $id)
    {
        return $query->where('client_id', $id);
    }

    public function items(): BelongsToMany
    {
        return $this
            ->belongsToMany(Site::class, 'cart_item')
            ->using(CartItem::class)
            ->withTimestamps();
    }
}
