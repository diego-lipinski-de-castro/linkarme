<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'last_updated_at',
        'seller_id',
        'team_id',
        'status',
        'deleted_why',
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

        // 
        'owner_role',
        'promo',
        'restrict',
        'restrict_detail',
        'archive_article',
        'archive_due',
        'links',
        'embed',
        'images',
        'example_article',
        'rules',
        'google_news',
        'global_account',
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

        'menu' => 'boolean',
        'banner' => 'boolean',

        'inserted_at' => 'date',
        'last_updated_at' => 'date',

        'promo' => 'boolean',
        'restrict' => 'boolean',
        'archive_article' => 'boolean',
        'embed' => 'boolean',
        'google_news' => 'boolean',
    ];

    protected $appends = [
        'formatted_status',
        'formatted_inserted_at',
        'formatted_last_updated_at',
        'formatted_updated_at',
        'real_url',
    ];

    protected static function booted()
    {
        static::creating(function ($site) {
            $site->url = trim($site->url);
            
            if (blank($site->inserted_at)) {
                $site->inserted_at = now();
            }
        });

        static::updating(function ($site) {
            $site->url = trim($site->url);

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

    public function team()
    {
        return $this->belongsTo(Team::class);
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

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_site')->withTimestamps();
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class)
            ->using(SiteType::class)
            ->withTimestamps()
            ->withPivot([
                'cost',
                'sale',
                'cost_coin',
                'sale_coin',
            ]);
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

    public function scopeAuthProject($query, $project)
    {
        return $query->whereHas('projects', function ($query) use ($project) {
            $query->ofClient(auth()->id())->where('projects.id', $project);
        });
    }

    public function scopeOfTypes($query, $types)
    {
        $types = explode(',', $types);

        foreach($types as $type) {
            $query->whereHas('types', function ($query) use ($type) {
                $query->where('types.id', $type);
            });
        }

        return $query;
    }

    public function getFormattedStatusAttribute()
    {
        if (blank($this->status)) {
            return null;
        }

        return self::STATUSES[$this->status];
    }

    public function getRealUrlAttribute()
    {
        if(auth()->user() instanceof Client && !auth()->user()->full) {
            return str_repeat('*', strlen($this->url));
        }

        return $this->url;
    }

    public function getPopularAttribute()
    {
        return $this->orders()->count() > 6;
    }

    public function getFormattedInsertedAtAttribute()
    {
        return $this->inserted_at->format('d/m/Y');
    }

    public function getFormattedLastUpdatedAtAttribute()
    {
        if(blank($this->last_updated_at)) return null;
        
        return $this->last_updated_at->format('d/m/Y');
    }
     
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('d/m/Y');
    }
}
