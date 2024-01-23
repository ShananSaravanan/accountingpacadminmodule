<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirmUser extends Model
{
    use HasFactory,SoftDeletes;
    protected $table ='firmuser';
    protected $with = ['firm', 'user'];
    public function firm(){
        return $this->belongsTo(Firm::class,'firmID','id');
    }
    public function firmowner(){
        return $this->hasOne(Firm::class,'firmOwnerID','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'userID','id');
    }
    public function assignment(){
        return $this->hasMany(Assignee::class,'AssigneeID','id');
    }
    public static $relationships = ['firm','user'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($fuser) {
            // Delete related subscriptions
            $fuser->assignment()->get()->each(function ($assignment) {
                $assignment->delete();
            });
            
        
        });
        static::restoring(function ($fuser) {
            // Restore related assignments
            $fuser->firm()->withTrashed()->get()->each(function ($firm) {
                $firm->restore();
            });
            $fuser->user()->withTrashed()->get()->each(function ($user) {
                $user->restore();
            });
            
        });
    }
    
    public function isFirmOwner()
    {
        return !is_null($this->firmowner);
    }
}
