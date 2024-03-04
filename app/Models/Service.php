<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;
use Auth;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    public function imageName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "<img src='".asset(str_replace('public','storage',$this->cover))."' class='img-thumbnail' style='width:75px; height:75px;'> ".$this->name
        );
    }

    public function accounts(){
        return $this->hasMany('App\Models\Account');
    }

    public function accountCount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->accounts->count()
        );
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
