<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordResetable;

use Cviebrock\EloquentSluggable\Sluggable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, Sluggable, SoftDeletes, PasswordResetable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'birth_date',
        'gender',
        'major',
        'phone',
        'blocked',
        'subscribed',
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscribed' => 'boolean',
        'blocked' => 'boolean'
    ];

    protected $appends = ['flat_permissions', 'flat_roles'];

    public function sluggable(): array
    {
        return [
            'username' => [
                'source' => ['firstname', 'lastname']
            ]
        ];
    }

    // RELATIONSHIPS
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'members');
    }

    // public function member()
    // {
    //     // return $this->belongsToMany(Team::class, 'members');
    //     return $this->hasManyThrough(Team::class, Member::class, 'user_id', 'id', 'id', 'team_id');
    // }

    public function social()
    {
        return $this->hasMany(Social::class);
    }

    // APPENDED ATTRIBUTES
    public function getFlatPermissionsAttribute()
    {
        $permissions = $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->flatten()
            ->toArray();
        return $permissions;
    }

    public function getFlatRolesAttribute()
    {
        $roles = $this->roles()->get()
            ->pluck('name')
            ->flatten()
            ->toArray();
        return $roles;
    }

    // HELPER FUNCTIONS
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function hasRole($role)
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function hasPermission($permission)
    {
        $roles = $this->belongsToMany(Role::class)->get();

        $res = false;
        foreach ($roles as $role) {
            $res = $role->includesPermission($permission);
            if ($res) break;
        }

        return $res;
    }

    public function getSocialAccount($user)
    {
        return $this->social->where('user_id', $user->id)->first();
    }

    public function hasSocialLinked($service)
    {
        return (bool) $this->social->where('service', $service)->count();
    }
}
