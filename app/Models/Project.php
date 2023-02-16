<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'color',
    ];

    public function scopeOfClient($query, $client)
    {
        return $query->where('client_id', $client);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'project_site')->withTimestamps();
    }
}
