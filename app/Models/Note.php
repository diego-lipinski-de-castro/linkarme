<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'client_id',
        'text',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
