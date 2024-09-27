<?php

namespace App\Traits;

use App\Models\Config;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Auth;

trait InitializesZKTeco
{
    protected $zk;
    protected $con;

    public function initializeZKTeco($user_id)
    {
        $this->con = Config::where('user_id','=',$user_id)->where('active_device',1)->first();
        if ($this->con) {
            $this->zk = new ZKTeco($this->con->ip, $this->con->port); // Initialize ZKTeco instance
        } else {
            // Handle the case when no matching record is found
            dd('No configuration found for this user.');
        }
    }

    public function checkUtilityNeeded()
    {
        // Retrieve the first configuration that matches the criteria
        $config = Config::where('user_id', Auth::id())
                        ->where('active', true)
                        ->where('active_device',true)
                        ->whereNotNull('ip')
                        ->whereNotNull('port')
                        ->first();
 
        // Check if the configuration was found
        if (!$config) {
            Config::disconnectDevice();
            return [
                'title' => 'Device Config Not Found',
                'message' => 'No configuration found. Please check your configurations.',
                'status'  => false,
            ];
        }
        
        $this->zk = new ZKTeco($config->ip, $config->port); // Initialize ZKTeco instance 
        if (!$this->zk->connect()) {
            Config::disconnectDevice();
            return [
                'title' => 'Device Not Connected',
                'message' => 'Please check your device connection.',
                'status' => false,
            ];
        }
       
        // Check if the 'socket_create' function exists
        if (!function_exists('socket_create')) {
            Config::disconnectDevice();
            return [
                'title' => 'Socket Not Supported',
                'message' => 'Socket not supported. Please Check your Socket Configuration (PHP extension).',
                'status'  => false,
            ];
        }

        return [
            'title' => 'Connected and Configured',
            'message' => 'Connnected and Configured.',
            'status'  => true,
        ];

       
    }

}
