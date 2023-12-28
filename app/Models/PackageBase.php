<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageBase extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='packageprice';
    public function package(){
        return $this->belongsTo(Package::class,'PackageID');
    }
}
