<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jmrashed\Zkteco\Lib\ZKTeco;

class MainController extends Controller
{

    protected $zk;
    protected $ip;

    public function __construct($zk, $ip,$port)
    {
        $this->zk  = new ZKTeco($ip,$port);
    }
     
    public function index()
    {

        $connection = $this->zk->connect();

        if ($connection) {
            echo "connted na siya";
        }else{
            echo "Connection failed";
        }

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
    

    public function searchIP()
    {
        // Define network range and port
        // $network = '192.168.3.0/24';
        $network = '192.168.0.0/22'; // This covers 192.168.0.0 to 192.168.3.255

        $port = 4370; // Port to scan
    
        // Define the Nmap command with optimizations
        $command = "nmap -p $port -T4 -n --open $network -oG -";
    
        // Execute the command and capture the output
        exec($command, $output, $return_var);
    
        // Check if the command was successful
        if ($return_var !== 0) {
            echo "Error executing Nmap command.";
            exit;
        }
    
        // Process the output
        $foundIPs = [];
        foreach ($output as $line) {
            // Check if the line contains the open port information
            if (strpos($line, "/open/") !== false) {
                // Extract the IP address from the line
                preg_match('/(\d+\.\d+\.\d+\.\d+)/', $line, $matches);
                if (isset($matches[1])) {
                    $foundIPs[] = $matches[1];
                }
            }
        }
    
        // Output the results
        if (empty($foundIPs)) {
            echo "No devices with port $port open found.";
        } else {
            echo "Found devices with port $port open:\n";
            foreach ($foundIPs as $ip) {
                echo "IP: $ip\n";
            }
        }
    }
    
        
}
