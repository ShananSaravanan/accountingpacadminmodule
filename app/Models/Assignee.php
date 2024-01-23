<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignee extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='assignee';
    public function businessuser(){
        return $this->belongsTo(BusinessUser::class,'AssignorID','id');
    }
    public function firmuser(){
        return $this->belongsTo(FirmUser::class,'AssigneeID','id');
    }
    public static $relationships = ['businessuser','firmuser'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete related subscriptions
           //No table uses this a parent table
        });
        static::restoring(function ($assignee) {
            // Restore related assignments
            $assignee->businessuser()->withTrashed()->get()->each(function ($buser) {
                $buser->restore();
            });
            $assignee->firmuser()->withTrashed()->get()->each(function ($fuser) {
                $fuser->restore();
            });
           
        });
    }
}
