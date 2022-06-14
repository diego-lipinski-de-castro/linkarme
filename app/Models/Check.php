<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Check extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'checkable_id',
        'checkable_type',
        'url',
        'code',
        'ssl',
    ];

    protected $casts = [
    ];

    public function checkable()
    {
        return $this->morphTo();
    }
}
