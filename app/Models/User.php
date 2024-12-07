<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $appends = ['last_days','role_and_name'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function lastDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDays()
        );
    }

    public function roleAndName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->name." (".$this->role.")"
        );
    }
    
    public function getDays(){
        $now = time();
        $total = "-";
        if($this->role == 'admin'){
            $your_date = strtotime($this->date_to);
            $datediff = ($your_date-$now);
            $total = floor($datediff / (60 * 60 * 24)) + 1;
        }
        
        return $total;
    }

    public function parent(){
        return $this->belongsTo('App\Models\User','parent_user_id','id');
    }

    public function scopeByRole($query){
        if(Auth::user()->role == "admin"){
            return $query->whereIn('role',['admin','reseller']);
        }
    }
}
