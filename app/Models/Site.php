<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Site extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public const STATUSES = [
        'PENDING' => 'Pendente',
        'APPROVED' => 'Aprovado',
        'REJECTED' => 'Rejeitado',
    ];

    public const LINK_TYPES = [
        'NOFOLLOW' => 'Nofollow',
        'DOFOLLOW' => 'Dofollow',
    ];

    protected $fillable = [
        'url',
        'name',
        'description',
        'obs',
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
        'menu',
        'banner',
        'broken',
        'ssl',
        'cost',
        'sale',
        'cost_coin',
        'sale_coin',
        'last_posted',
        'inserted_at',
        'seller_id',
        'team',
        'status',
        'deleted_why',
        'suggested',
        'owner_name',
        'owner_email',
        'owner_phone',
        'bank',
        'pix',
        'phone',
        'paypal',
        'instagram',
        'facebook',
        
        'deleted_at',
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

        'suggested' => 'integer',

        'menu' => 'boolean',
        'banner' => 'boolean',

        'inserted_at' => 'date',
        'last_updated_at' => 'date',
    ];

    protected $appends = [
        'formatted_status',
        'formatted_suggested',
        'formatted_diff',
        'formatted_inserted_at',
        'formatted_updated_at',
        'positive',
    ];

    protected static function booted()
    {
        static::creating(function ($site) {
            $site->url = trim($site->url);
            
            if (blank($site->inserted_at)) {
                $site->inserted_at = now();
            }

            $site->suggested = ($site->cost) + ($site->cost * 0.30) + (675 * 100);
        });

        static::updating(function ($site) {
            $site->url = trim($site->url);

            $site->suggested = ($site->cost) + ($site->cost * 0.30) + (675 * 100);

            if ($site->isDirty('sale')) {
                $site->sale_updated_at = now();
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        // old
        if (Arr::has($data, 'old_values.gambling')) {
            $data['old_values']['gambling'] = (bool) $data['old_values']['gambling'];
        }

        if (Arr::has($data, 'old_values.cdb')) {
            $data['old_values']['cdb'] = (bool) $data['old_values']['cdb'];
        }

        if (Arr::has($data, 'old_values.cripto')) {
            $data['old_values']['cripto'] = (bool) $data['old_values']['cripto'];
        }

        if (Arr::has($data, 'old_values.sponsor')) {
            $data['old_values']['sponsor'] = (bool) $data['old_values']['sponsor'];
        }

        if (Arr::has($data, 'old_values.menu')) {
            $data['old_values']['menu'] = (bool) $data['old_values']['menu'];
        }

        if (Arr::has($data, 'old_values.banner')) {
            $data['old_values']['banner'] = (bool) $data['old_values']['banner'];
        }

        if (!Arr::has($data, 'old_values.cost_coin')) {
            $data['old_values']['cost_coin'] = $this->cost_coin;
        }

        if (!Arr::has($data, 'old_values.sale_coin')) {
            $data['old_values']['sale_coin'] = $this->sale_coin;
        }

        // new
        if (Arr::has($data, 'new_values.gambling')) {
            $data['new_values']['gambling'] = (bool) $data['new_values']['gambling'];
        }

        if (Arr::has($data, 'new_values.cdb')) {
            $data['new_values']['cdb'] = (bool) $data['new_values']['cdb'];
        }

        if (Arr::has($data, 'new_values.cripto')) {
            $data['new_values']['cripto'] = (bool) $data['new_values']['cripto'];
        }

        if (Arr::has($data, 'new_values.sponsor')) {
            $data['new_values']['sponsor'] = (bool) $data['new_values']['sponsor'];
        }

        if (Arr::has($data, 'new_values.menu')) {
            $data['new_values']['menu'] = (bool) $data['new_values']['menu'];
        }

        if (Arr::has($data, 'new_values.banner')) {
            $data['new_values']['banner'] = (bool) $data['new_values']['banner'];
        }

        if (!Arr::has($data, 'new_values.cost_coin')) {
            $data['new_values']['cost_coin'] = $this->cost_coin;
        }

        if (!Arr::has($data, 'new_values.sale_coin')) {
            $data['new_values']['sale_coin'] = $this->sale_coin;
        }

        return $data;
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
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

    public function interests()
    {
        return $this->belongsToMany(Client::class, 'interests')->withTimestamps();
    }

    public function checks()
    {
        return $this->morphMany(Check::class, 'checkable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAuthNew($query)
    {
        return $query
            ->where('inserted_at', '>', now()->subDays(60)->endOfDay())
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            });
    }

    public function scopeAuthUsed($query, $used = true)
    {
        $where = $used ? 'whereHas' : 'whereDoesntHave';

        return $query->{$where}('orders', function ($query) {
            $query->ofClient(auth()->id());
        });
    }

    public function scopeAuthFavorites($query)
    {
        return $query->whereHas('favorites', function ($query) {
            $query->where('clients.id', auth()->id());
        });
    }

    public function scopeAuthInterests($query)
    {
        return $query->whereHas('interests', function ($query) {
            $query->where('clients.id', auth()->id());
        });
    }

    public function scopeAuthRecommended($query)
    {
        return $query
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            });
    }

    public function scopeOfSeller($query, $seller)
    {
        return $query->where('seller_id', $seller);
    }

    public function getFormattedStatusAttribute()
    {
        if (blank($this->status)) {
            return null;
        }

        return self::STATUSES[$this->status];
    }

    public function getFormattedSuggestedAttribute()
    {
        return 'R$ '.number_format($this->suggested / 100, 2, ',', '.');
    }

    public function getPositiveAttribute()
    {
        return $this->suggested < $this->sale;
    }

    public function getFormattedDiffAttribute()
    {
        $diff = $this->suggested - $this->sale;

        return 'R$ '.number_format($diff / 100, 2, ',', '.');
    }

    public function getPopularAttribute()
    {
        return $this->orders()->count() > 6;
    }

    public function getFormattedInsertedAtAttribute()
    {
        return $this->inserted_at->format('d/m/Y');
    }
    
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('d/m/Y H:i');
    }
}
