<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminApplication extends Model
{
    protected $fillable = [
        'admin_id',
        'applicationName',
        'slug',
        'language',
        'deployed',
        'database'
    ];
    public function owner():BelongsTo
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
