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
        return $this->hasOne(Firm::class,'firmTypeID','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($ftype) {
            // Delete related subscriptions
            $ftype->firm()->get()->each(function ($firm) {
                $firm->delete();
            });
            
        });
    }
}
