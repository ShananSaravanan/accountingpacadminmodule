<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirmType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='firmtype'; 
    public function firm(){
        return $this->belongsTo(Firm::class,'id');
    }
}
