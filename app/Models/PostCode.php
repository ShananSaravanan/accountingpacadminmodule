<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='postcode'; 
    public function address(){
        return $this->belongsTo(Address::class,'id');
    }
    public function postoffice(){
        return $this->belongsTo(PostOffice::class,'postOfficeID');
    }
    public function statecode(){
        return $this->belongsTo(StateCode::class,'stateCodeID');
    }
}
