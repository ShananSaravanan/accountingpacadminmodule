<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;
    protected $table ='transaction';
    public function subscription(){
        return $this->hasMany(Subscription::class,'TransactionID','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($transaction) {
            // Delete related subscriptions
            $transaction->subscription()->get()->each(function ($subscription) {
                $subscription->delete();
            });
            
        });
    }
}
