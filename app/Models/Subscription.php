<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="subscription";
    public function user(){
        return $this->belongsTo(User::class,'userID','id');
    }
    public function packageprice(){
        return $this->belongsTo(PackagePrice::class,'packagepriceID','id');
    }
    public function transaction(){
        return $this->belongsTo(Transaction::class,'TransactionID','id');
    }
    public static $relationships = ['transaction','user','packageprice'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($sub) {
            // Delete related 
            // No tables use this as parent table
        });
        static::restoring(function ($sub) {
            // Restore related assignments
            $sub->user()->withTrashed()->get()->each(function ($user) {
                $user->restore();
            });
            $sub->packageprice()->withTrashed()->get()->each(function ($packageprice) {
                $packageprice->restore();
            });
            $sub->transaction()->withTrashed()->get()->each(function ($transaction) {
                $transaction->restore();
            });
            
           
        });
    }
}
