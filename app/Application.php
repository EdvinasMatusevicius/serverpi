<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'applicationName'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'owner_id');
    }
    public function admin():BelongsTo
    {
        return $this->belongsTo(Admin::class,'owner_id');
    }
}
