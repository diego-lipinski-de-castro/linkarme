<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Offer extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'seller_id',
        'site_id',
        'cost',
        'cost_coin',
        'sale',
        'sale_coin',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type::class)
            ->withTimestamps()
            ->withPivot([
                'cost',
                'sale',
                'cost_coin',
                'sale_coin',
            ]);
    }
}
