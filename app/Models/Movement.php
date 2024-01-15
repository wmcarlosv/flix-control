<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $table = 'movements';

    public function formatAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "$ ".number_format($this->amount, 2, '.',',')
        );
    }
}
