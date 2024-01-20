<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;
use Auth;

class Movement extends Model
{
    use HasFactory;

    protected $table = 'movements';

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    public function formatAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "$ ".number_format($this->amount, 2, '.',',')
        );
    }

    public static function createMovement($data){
        $mv = new Movement();
        $mv->type = $data['type'];
        $mv->description = $data['description'];
        $mv->datemovement = date('Y-m-d');
        $mv->amount = $data['amount'];
        $mv->user_id = Auth::user()->id;
        $mv->save();
    }
}
