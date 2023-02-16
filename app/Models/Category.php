<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'title',
        'subtitle',
    ];

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function getTitleAttribute()
    {
        return trim(explode('(', $this->name)[0]);
    }

    public function getSubtitleAttribute()
    {
        if(!isset(explode('(', $this->name)[1])) return null;

        return rtrim(trim(explode('(', $this->name)[1]), ')');
    }
}
