<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Jmrashed\Zkteco\Lib\ZKTeco;

class MainController extends Controller
{

public function checkUtilityNeeded()
{
    // Retrieve the first configuration that matches the criteria
    $config = Config::where('user_id', /* specify the group_id value here */)
                    ->where('active', true)
                    ->where('active_device',true)
                    ->whereNotNull('ip')
                    ->whereNotNull('port')
                    ->first();

    // Check if the configuration was found
    if (empty($config)) {
        return [
            'message' => 'No configuration found. Please check your configurations.',
            'status'  => false,
        ];
    }

    // Check if the 'socket_create' function exists
    if (!function_exists('socket_create')) {
        return [
            'message' => 'Socket not supported. Please Check your Socket Configuration (PHP extension).',
            'status'  => false,
        ];
    }

    // If everything is okay, return a success response
    return [
        'message' => 'Configuration is valid and socket is supported.',
        'status'  => true,
    ];
}

public function index()
{
    $checkUtilityNeeded = $this->checkUtilityNeeded();
    
    return view('app.user.index', ['checkUtilityNeeded' => $checkUtilityNeeded]);

        
        // $this->zk = new ZKTeco('192.168.3.34');
        // $connection = $this->zk->connect();

        // if ($connection) {
        //     echo "connted na siya";
        // }else{
        //     echo "Connection failed";
        // }

        // if (function_exists('socket_create')) {


        //     $zk = new ZKTeco('192.168.3.34');
        //     $zk->connect();
    
        //     // Get attendance log
        //     $attendanceLog = $zk->getAttendance();
        //     // dd($attendanceLog); // Check structure of attendance records
    
        //     // Get user information
        //      $userList = $zk->getUser();
        //     // dd($userList); // Check structure of user list
    
        //     // Convert user list to associative array for quick lookup
        //     $userMap = [];
        //     foreach ($userList as $user) {
        //         // Check actual field names in userList
        //         $userId = $user['uid'] ?? null; // Ensure 'userid' exists
        //         if ($userId) {
        //             $userMap[$userId] = $user;
        //         }
        //     }
    
        //     // Define start and end dates for the range
        //     $startDate = '2024-09-01'; // Example start date
        //     $endDate = '2024-09-30';   // Example end date
    
        //     // Filter attendance records for the date range
        //     $filteredRecords = [];
        //     foreach ($attendanceLog as $record) {
        //         // Extract the date from the timestamp
        //         $recordDate = substr($record['timestamp'], 0, 10); // Check format of timestamp
    
        //         // Check if the date falls within the specified range
        //         if ($recordDate >= $startDate && $recordDate <= $endDate) {
        //             // Add user details to the record
        //             $record['userid'] ?? null; // Ensure 'userid' exists
        //             if ($userId && isset($userMap[$userId])) {
        //                 $record['user_name']     = $userMap[$userId]['name'];
        //                 $record['user_emp_id']    = $userMap[$userId]['userid'];
        //                 $record['user_role']     = $userMap[$userId]['role'];
        //                 $record['user_password'] = $userMap[$userId]['password'];
        //                 $record['user_cardno']   = $userMap[$userId]['cardno'];
        //             } else {
        //                 $record['user_details'] = null; // User details not found
        //             }
    
        //             $filteredRecords[] = $record;
        //         }
        //     }
    
        //     dd($filteredRecords); // Output the records with user details
    
        // } else {
        //     echo "Sockets extension is not enabled.";
        // }
    }
    
    
    
    
    // Example method to get employee name by ID (implement this as needed)
    // private function getEmployeeNameById($employeeId) {
    //     // Implement your logic to fetch employee names
    //     // This could be a database query or a lookup from an external source
    //     // Example:
    //     // $employee = Employee::find($employeeId);
    //     // return $employee ? $employee->name : 'Unknown';
    // }
    
        
}
