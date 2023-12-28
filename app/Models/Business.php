<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='business';    

    public function btype(){
        return $this->belongsTo(BusinessType::class,'businessType');
    }
    public function businessuser(){
        return $this->belongsTo(BusinessUser::class,'id');
    }
}
