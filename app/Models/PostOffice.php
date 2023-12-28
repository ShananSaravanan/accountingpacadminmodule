<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostOffice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='postoffice'; 
    public function postcode(){
        return $this->belongsTo(PostCode::class,'id');
    }
}
