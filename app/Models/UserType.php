<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasOne(User::class);
    }

    public function scopeActive()
    {
        return $this->where('active',1);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    protected static function boot()
    {
        parent::boot();

        // Set created_by and modified_by during creation
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->modified_by = Auth::id();
        });

        // Set modified_by during update
        static::updating(function ($model) {
            $model->modified_by = Auth::id();
        });
    }
    
}
