<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Firm extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='firm';
    public function firmType(){
        return $this->belongsTo(FirmType::class,'firmTypeID','id');
    }
    public function firmowner(){
        return $this->belongsTo(FirmUser::class,'firmOwnerID','id');
    }
    public function firmuser(){
        return $this->hasMany(FirmUser::class,'firmID','id');
    }
    
    public function address(){
        return $this->belongsTo(Address::class,'addressID','id');
    }
    public static $relationships = ['firmType','firmowner','address'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($firm) {
            // Delete related subscriptions
            $firm->firmuser()->get()->each(function ($fuser) {
                $fuser->delete();
            });
            $firm->firmowner()->get()->each(function ($fuser) {
                $fuser->delete();
            });
            
        });
        static::restoring(function ($firm) {
            // Restore related assignments
            $firm->firmType()->withTrashed()->get()->each(function ($firmType) {
                $firmType->restore();
            });
            $firm->address()->withTrashed()->get()->each(function ($address) {
                $address->restore();
            });
           
        });
    }
}
