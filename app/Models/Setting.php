<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    public $timestamps = false;
}
