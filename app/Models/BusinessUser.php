<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='businessuser';
    public function business(){
        return $this->belongsTo(Business::class,'businessID','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'userID', 'id');
    }
    public function assignment(){
        return $this->hasMany(Assignee::class,'AssignorID', 'id');
    }
    public static $relationships = ['business','user'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($buser) {
            // Delete related subscriptions
            $buser->assignment()->get()->each(function ($assignment) {
                $assignment->delete();
            });
        });
        static::restoring(function ($buser) {
            // Restore related assignments
            $buser->business()->withTrashed()->get()->each(function ($business) {
                $business->restore();
            });
            $buser->user()->withTrashed()->get()->each(function ($user) {
                $user->restore();
            });
            
           
        });
    }
}
