<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'user_type_id',
        'group_id',
        'password',
    ];

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
    ];

    public function user_type(){
        return $this->belongsTo(UserType::class,'user_type_id');
    }

    public function scopeStoreUser($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateUser($q,$request){
        return $q->whereId($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        $oldpass = Static::find($request->id);
        return [
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'user_type_id'  => $request->user_type_id,
            'group_id'      => $request->group_id,
            'active'        => $request->active,
            'password'      => !empty($request->password)?Hash::make($request->password):$oldpass->password,
        ];

    }
}
