<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function checkUtilityNeeded()
    {
        // Retrieve the first configuration that matches the criteria
        $config = Config::where('user_id', auth()->user()->id)
                        ->where('active', 1)
                        ->whereNotNull('ip')
                        ->whereNotNull('port')
                        ->first();
    
        // Check if the configuration was found
        if (empty($config)) {
            return [
                'title'   => 'Configuration Not Found',
                'message' => 'No configuration found. Please check your configurations.',
                'status'  => false,
            ];
        }
    
        // Check if the 'socket_create' function exists
        if (!function_exists('socket_create')) {
            return [
                'title'   => 'Socket Not Supported',
                'message' => 'Socket not supported. Please Check your Socket Configuration (PHP extension).',
                'status'  => false,
            ];
        }
    
        // If everything is okay, return a success response
        return [
            'title'   => 'Configuration Valid and Socket Supported',
            'message' => 'Configuration is valid and socket is supported.',
            'status'  => true,
        ];
    }
    
    public function index()
    {
        $checkUtilityNeeded = $this->checkUtilityNeeded();
        
        return view('app.user.index', ['checkUtilityNeeded' => $checkUtilityNeeded]);
    }


    public function userDevice(){
        $checkUtilityNeeded = $this->checkUtilityNeeded();
        return view('app.user.device.index', ['checkUtilityNeeded' => $checkUtilityNeeded]);
    }

}
