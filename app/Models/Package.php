<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="package";
    public function packageBase(){
        return $this->hasMany(PackagePrice::class,'PackageID','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($package) {
            // Delete related subscriptions
            $package->packageBase()->get()->each(function ($packageprice) {
                $packageprice->delete();
            });
            
        });
    }
}
