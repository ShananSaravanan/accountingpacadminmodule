<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateCode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='statecode';
    public function postcode(){
        return $this->belongsTo(PostCode::class,'id');
    }
}
