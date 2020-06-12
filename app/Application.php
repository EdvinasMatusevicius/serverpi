<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'applicationName',
        'slug',
        'language',
        'database'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
