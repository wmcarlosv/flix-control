<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;

class Customer extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    protected $table = 'customers';
}
