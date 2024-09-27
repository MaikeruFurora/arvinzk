<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Config extends Model
{
    use HasFactory;

    protected $guarded = [];
    
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

    public function scopeDisconnectDevice($q){
        return $q->where('user_id',  Auth::id())->update(['active_device' => 0]);
    }

    public function scopeConnectDevice($q){
       return $q->where('user_id',  Auth::id())->where('active_device', 1);
    }

    public static function cleanDeviceInfo($input)
    {
        // Step 1: Remove the leading tilde (~)
        $cleanedString = str_replace('~', '', $input);

        // Step 2: Split the string by '=' to get an array
        $parts = explode('=', $cleanedString);

        // Step 3: Get the value part (index 1 of the array)
        $deviceName = isset($parts[1]) ? $parts[1] : null;

        $cleanedInput = preg_replace('/[^A-Za-z0-9 ]/', '',  $deviceName);
        // Output the device name
        return $cleanedInput;  // Output: K40/ID
    }
}
