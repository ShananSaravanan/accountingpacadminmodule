<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostOffice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='postoffice'; 
    public function postcode(){
        return $this->hasMany(PostCode::class,'postOfficeID','id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($postoffice) {
            // Delete related subscriptions
            $postoffice->postcode()->get()->each(function ($postcode) {
                $postcode->delete();
            });
            
        });
    }
}
