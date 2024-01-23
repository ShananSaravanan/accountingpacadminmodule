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
        return $this->hasMany(Business::class,'businessType','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($btype) {
            // Delete related subscriptions
            $btype->business()->get()->each(function ($business) {
                $business->delete();
            });
            
        });
    }
}
