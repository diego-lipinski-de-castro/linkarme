<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Contracts\Auditable;

class SiteType extends Pivot implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'site_id',
        'type_id',
        'cost',
        'sale',
        'cost_coin',
        'sale_coin',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
