<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagePrice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='packageprice';
    public function package(){
        return $this->belongsTo(Package::class,'PackageID','id');
    }
    public function subscription(){
        return $this->hasMany(Subscription::class,'packagepriceid','id');
    }
    public static $relationships = ['package'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pBase) {
            // Delete related subscriptions
            $pBase->subscription()->get()->each(function ($sub) {
                $sub->delete();
            });
            
        });
        static::restoring(function ($packageprice) {
            // Restore related assignments
            $packageprice->package()->withTrashed()->get()->each(function ($package) {
                $package->restore();
            });
            
           
        });
    }
}
