<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'ssl',
        'broken',
        'cost',
        'sale',
        'last_posted',
        'owner_name',
        'owner_whatsapp',
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
        
        // 'cost',
        // 'sale',

        // 'last_posted' => 'date',
    ];

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
}
