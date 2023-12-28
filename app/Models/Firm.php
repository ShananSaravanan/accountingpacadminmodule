<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Firm extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='firm';
    public function firmType(){
        return $this->belongsTo(FirmType::class,'firmTypeID');
    }
}
