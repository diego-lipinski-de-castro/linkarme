<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;

class Seller extends Model
{
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'comission',
        'comission_coin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'comission' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected static function booted()
    {
        static::creating(function ($seller) {
            $seller->name = trim($seller->name);
        });

        static::updating(function ($seller) {
            $seller->name = trim($seller->name);
        });
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'owner');
    }
}
