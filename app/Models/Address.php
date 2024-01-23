<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='address';
    public function user(){
        return $this->belongsTo(User::class,'userID','id');
    }
    public function addressType(){
        return $this->belongsTo(AddressType::class,'addressTypeID','id');
    }
    public function postcode(){
        return $this->belongsTo(PostCode::class,'postCodeID','id');
    }
    public function firm(){
        return $this->hasMany(Firm::class,'addressID','id');
    }
    public static $relationships = ['user','addresstype','postcode'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($address) {
            // Delete related subscriptions
            
            $address->firm()->get()->each(function ($firm) {
                $firm->delete();
            });
            
        });
        static::restoring(function ($address) {
            // Restore related assignments
            $address->addressType()->withTrashed()->get()->each(function ($addressType) {
                $addressType->restore();
            });
            $address->user()->withTrashed()->get()->each(function ($user) {
                $user->restore();
            });
            $address->postcode()->withTrashed()->get()->each(function ($postcode) {
                $postcode->restore();
            });
        });
    }
}
