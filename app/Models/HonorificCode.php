<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HonorificCode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="honorificcode";
    
    public function users()
    {
        return $this->hasMany(User::class, 'HonorificCodeID', 'id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($hcode) {
            // Delete related subscriptions
            $hcode->users()->get()->each(function ($user) {
                $user->delete();
            });
            
        });
    }
}
