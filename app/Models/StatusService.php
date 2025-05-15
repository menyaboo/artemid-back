<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusService extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function category() {
        return $this->belongsTo(CategoryService::class);
    }
}
