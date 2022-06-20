<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Site extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public const LINK_TYPES = [ 
        'NOFOLLOW' => 'Nofollow',
        'DOFOLLOW' => 'Dofollow',
    ];

    protected $fillable = [
        'url',
        'name',
        'description',
        'obs',
        'admin_obs',
        'client_obs',
        'da',
        'dr',
        'traffic',
        'tf',
        'language_id',
        'country_id',
        'category_id',
        'link_type',
        'gambling',
        'cdb',
        'cripto',
        'sponsor',
        'broken',
        'ssl',
        'cost',
        'sale',
        'cost_coin',
        'sale_coin',
        'last_posted',
        'owner_name',
        'owner_whatsapp',
        'inserted_at',
    ];

    protected $casts = [
        'da' => 'integer',
        'dr' => 'integer',
        'traffic' => 'integer',
        'tf' => 'integer',

        'gambling' => 'boolean',
        'cdb' => 'boolean',
        'cripto' => 'boolean',
        'sponsor' => 'boolean',
        'ssl' => 'boolean',
        'broken' => 'boolean',
        
        'cost' => 'integer',
        'sale' => 'integer',

        // 'last_posted' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($site) {
            if(blank($site->inserted_at)) {
                $site->inserted_at = now();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Client::class, 'favorites')->withTimestamps();
    }

    public function checks()
    {
        return $this->morphMany(Check::class, 'checkable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeAuthFavorites($query)
    {
        return $query->whereHas('favorites', function ($query) {
            $query->where('clients.id', auth()->id());
        });
    }
}
