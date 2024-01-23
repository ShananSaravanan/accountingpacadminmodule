<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='addresstype'; 
    public function address(){
        return $this->hasOne(Address::class,'addressTypeID','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($addresstype) {
            // Delete related subscriptions
           
            $addresstype->address()->get()->each(function ($address) {
                $address->delete();
            });
        });
        
    }
}
