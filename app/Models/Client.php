<?php

namespace App\Models;

use App\Notifications\Client\ResetPasswordNotification;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable implements HasLocalePreference
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full',
        'locale',
        'coin',
        'notify_updates_via_email',
        'company_name',
        'website_url',
        'address_country',
        'address_state',
        'address_street_name',
        'address_street_number',
        'address_number',
        'address_zipcode',
        'phone',
        'invoice_company_name',
        'invoice_vat_number',
        'invoice_paypal',
        'invoice_address_country',
        'invoice_address_state',
        'invoice_address_street_name',
        'invoice_address_street_number',
        'invoice_address_number',
        'invoice_address_zipcode',
        'contact',
        'contact_where',
        'contact_id',
        'contact_how',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'full' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale ?? 'en';
    }

    protected static function booted()
    {
        static::creating(function ($client) {
            $client->name = trim($client->name);
        });

        static::updating(function ($client) {
            $client->name = trim($client->name);
        });
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'owner');
    }

    public function favorites()
    {
        return $this->belongsToMany(Site::class, 'favorites')->withTimestamps();
    }

    public function getFavoritesIdsAttribute()
    {
        return $this->favorites->pluck('id')->toArray();
    }

    public function interests()
    {
        return $this->belongsToMany(Site::class, 'interests')->withTimestamps();
    }

    public function getInterestsIdsAttribute()
    {
        return $this->interests->pluck('id')->toArray();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
