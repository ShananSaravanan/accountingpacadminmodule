<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='business';    
    

    public function btype(){
        return $this->belongsTo(BusinessType::class, 'businessType', 'id');
    }
    public function businessuser()
    {
        return $this->hasMany(BusinessUser::class, 'businessID', 'id');
    }
    public function financial(){
        return $this->hasMany(FinancialRecord::class,'businessID', 'id');
    }
    public static $relationships = ['btype'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($business) {
            // Delete related subscriptions
            $business->financial()->get()->each(function ($financial) {
                $financial->delete();
            });
            $business->businessuser()->get()->each(function ($buser) {
                $buser->delete();
            });
            
        });
        static::restoring(function ($business) {
            // Restore related assignments
            $business->btype()->withTrashed()->get()->each(function ($btype) {
                $btype->restore();
            });
            
           
        });
    }
}
