<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'applicationName',
        'slug',
        'language',
        'deployed',
        'giturl',
        'database',
        'share'
    ];
    public function owner():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
