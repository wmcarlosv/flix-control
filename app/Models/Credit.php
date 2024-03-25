<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;
use Auth;
use User;

class Credit extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    protected $table = "credits";

    public function parent(){
        return $this->belongsTo('App\Models\User','parent_user_id','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
