<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateCode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='statecode';
    public function postcode(){
        return $this->hasMany(PostCode::class,'stateCodeID','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($statecode) {
            // Delete related subscriptions
            $statecode->postcode()->get()->each(function ($postcode) {
                $postcode->delete();
            });
            
        });
    }
}
