<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='address';
    public function user(){
        return $this->belongsTo(User::class,'userID');
    }
    public function addressType(){
        return $this->belongsTo(AddressType::class,'addressTypeID');
    }
    public function postcode(){
        return $this->belongsTo(PostCode::class,'postCodeID');
    }
}
