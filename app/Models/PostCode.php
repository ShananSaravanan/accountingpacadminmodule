<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='postcode'; 
    public function address(){
        return $this->hasOne(Address::class,'postCodeID','id');
    }
    public function postoffice(){
        return $this->belongsTo(PostOffice::class,'postOfficeID','id');
    }
    public function statecode(){
        return $this->belongsTo(StateCode::class,'stateCodeID','id');
    }
    public static $relationships = ['statecode','postoffice'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($postcode) {
            // Delete related subscriptions
            $postcode->address()->get()->each(function ($address) {
                $address->delete();
            });
           
        });
        static::restoring(function ($postcode) {
            // Restore related assignments
            $postcode->statecode()->withTrashed()->get()->each(function ($statecode) {
                $statecode->restore();
            });
            $postcode->postoffice()->withTrashed()->get()->each(function ($postoffice) {
                $postoffice->restore();
            });
            
           
        });
    }
}
