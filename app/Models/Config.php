<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;

class Config extends Model
{
    use HasFactory;

    protected $table = 'configs';

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }
}
