<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
Use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $fillable = [
        'id',
        'FirstName',
        'LastName',
        'HonorificCodeID',
        'RoleID',
        'contactNo',
        'Status',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at'

    ];
    
    public function honorific()
    {
        return $this->belongsTo(HonorificCode::class, 'HonorificCodeID', 'id');
    }
    public function roles(){
        return $this->belongsTo(Role::class,'RoleID', 'id');
    }
    public function businessuser(){
        return $this->hasOne(BusinessUser::class,'userID','id');
    }
    public function address(){
        return $this->hasMany(Address::class,'id');
    }
    public function firmuser(){
        return $this->hasOne(FirmUser::class,'userID','id');
    }
    public function subscriptions(){
        return $this->hasMany(Subscription::class, 'userID','id');
    }
    public static $relationships = ['honorific','roles'];
    public static function getRelationships()
    {
        return self::$relationships;
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected static function boot()
{
    parent::boot();

    static::deleting(function ($user) {
        // Delete related subscriptions
        $user->subscriptions()->get()->each(function ($sub) {
            $sub->delete();
        });
        $user->businessuser()->get()->each(function ($buser) {
            $buser->delete();
        });
        $user->firmuser()->get()->each(function ($fuser) {
            $fuser->delete();
        });
        $user->address()->get()->each(function ($address) {
            $address->delete();
        });
        
        // ... other deletions
    });
    static::restoring(function ($user) {
        // Restore related assignments
        $user->honorific()->withTrashed()->get()->each(function ($honorific) {
            $honorific->restore();
        });
        $user->roles()->withTrashed()->get()->each(function ($roles) {
            $roles->restore();
        });
        
    });
}
}
