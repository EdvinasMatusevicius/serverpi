<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminApplication extends Model
{
    protected $fillable = [
        'applicationName',
        'slug',
        'language',
        'database'
    ];
    public function admin():BelongsTo
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
