<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "businesstype";

    public function business(){
        return $this->belongsTo(Business::class,'id');
    }
}
