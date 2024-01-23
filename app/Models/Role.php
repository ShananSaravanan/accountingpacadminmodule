<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="role";
    public function users()
    {
        return $this->hasMany(User::class, 'RoleID', 'id');
    }
    public static $relationships = [];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            // Delete related subscriptions
            $role->users()->get()->each(function ($user) {
                $user->delete();
            });
        });
    }
}
