<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='businessuser';
    public function business(){
        return $this->belongsTo(Business::class,'businessID');
    }
    public function user(){
        return $this->belongsTo(User::class,'userID');
    }
}
