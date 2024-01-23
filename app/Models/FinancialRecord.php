<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialRecord extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='financialrecords';
    public static $relationships = ['business'];
    public function business(){
        return $this->belongsTo(Business::class,'businessID','id');
    }
    
    // Add more relationships as needed

    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete related subscriptions
            // No table uses this as a parent table
        });
        static::restoring(function ($frecord) {
            // Restore related assignments
            $frecord->business()->withTrashed()->get()->each(function ($business) {
                $business->restore();
            });
            
            
           
        });
    }
}
