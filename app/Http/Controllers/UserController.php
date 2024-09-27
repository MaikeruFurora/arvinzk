<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jmrashed\Zkteco\Lib\ZKTeco;
use App\Traits\InitializesZKTeco;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use InitializesZKTeco;


    public function index(Request $request)
    {
        $checkUtilityNeeded = $this->checkUtilityNeeded();
        return view('app.user.index', ['checkUtilityNeeded' => $checkUtilityNeeded]);
    }


    public function attendanceLog(Request $request)
    {
        $config = Config::where('user_id','=',auth()->id())->where('active_device',1)->first();
        $this->zk = new ZKTeco($config->ip, $config->port); // Initialize ZKTeco instance
        if (!$this->zk->connect()) {
            Config::disconnectDevice();
            return response()->json(['error' => 'Device not configured or connection failed'], 500);
        }

      // Fetch attendance logs
            $attendanceLog = $this->zk->getAttendance();
            $userList = $this->zk->getUser();

            // Create a map of userId to user details for quick lookup
            $userMap = [];
            foreach ($userList as $user) {
                // Check actual field names in userList
                $userId = $user['userid'] ?? null; // Ensure 'userid' exists
                if ($userId) {
                    $userMap[$userId] = $user;  // Store the user data by 'userid'
                }
            }

            // Get start and end dates
            $startDate = $request->from ?? Carbon::now()->format('Y-m-d'); // Example start date
            $endDate = $request->to ?? Carbon::now()->format('Y-m-d');     // Example end date

            $filteredRecords = [];
            foreach ($attendanceLog as $record) {
                // Parse the timestamp once to extract date and time
                $recordDate = substr($record['timestamp'], 0, 10); // Check format of timestamp
                if ($recordDate >= $startDate && $recordDate <= $endDate) {
                    // Perform user lookup only when the record is in the date range
                    $userId = $record['id'];
                    if (isset($userMap[$userId])) {
                        $user = $userMap[$userId];
                        
                        // Add user info to the record
                        $record['user_name']     = $user['name'];
                        $record['user_emp_id']   = $user['userid'];
                        $record['user_role']     = $user['role'];
                        $record['user_password'] = $user['password'];
                        $record['user_cardno']   = $user['cardno'];
                    }

                    // Mark attendance status based on type
                    switch ($record['type']) {
                        case 0:
                            $record['checklog'] = 'Duty On';  // Mark as IN
                            break;
                        case 1:
                            $record['checklog'] = 'Duty Off'; // Mark as OUT
                            break;
                        case 4:
                            $record['checklog'] = 'Overtime In'; // Mark as OUT
                            break;
                        case 5:
                            $record['checklog'] = 'Overtime Out'; // Mark as OUT
                            break;
                        default:
                            $record['checklog'] = $record['type']; // Handle unknown types
                            break;
                    }

                    // Parse and add date and time once after filtering
                    $record['date'] = Carbon::parse($record['timestamp'])->format('Y-m-d');
                    $record['time'] = Carbon::parse($record['timestamp'])->format('h:i:s A');
                    $record['datetime'] = Carbon::parse($record['timestamp'])->format('Y-m-d h:i:s A');
                    
                    // Add to filtered records
                    $filteredRecords[] = $record;
                }
            }

            // Return the filtered records in a JSON response (for DataTables)
            return response()->json(['data' => $filteredRecords]);

       
    }

    public function uploadAttendanceLog(Request $request){
       
        $config = Config::where('user_id', auth()->id())
        ->where('active_device', 1)
        ->first();

        if (!$config) {
            Config::disconnectDevice();
            return response()->json(['error' => 'No active device found'], 400);
        }

        // Adjust the date range
        $to = Carbon::parse($request->to)->addDay();
        $from = $request->from;
        $dataFromDevice = json_decode($request->data);

        // Array to store logs for batch insert
        $dataPushLog = [];

        // Get existing logs in bulk
        $existingLogs = DB::table('tbllogs')
            ->where('srnum', $config->serial_number)
            ->whereBetween(DB::raw('CONVERT(TimeStampLog, DATETIME)'), [$from, $to])
            ->whereIn('IndRegID', collect($dataFromDevice)->pluck('id'))
            ->get();

        // Convert existing logs to a more searchable format (e.g., using key pairs for quick lookups)
        $existingLogMap = $existingLogs->mapWithKeys(function ($log) {
            return [$log->IndRegID . '_' . $log->TimeStampLog => $log];
        });

        // Process new data and push logs if not already existing
        foreach ($dataFromDevice as $value) {
        $logKey = $value->id . '_' . $value->datetime;

            if (!isset($existingLogMap[$logKey])) {
                $dataPushLog[] = [
                    'srnum'         => $config->serial_number,
                    'MachineNumber' => 1,
                    'IndRegID'      => $value->id,
                    'TimeStampLog'  => $value->datetime,
                    'checklog'      => $value->checklog,
                ];
            }
        }

        // Batch insert logs if there are any new ones
        if (!empty($dataPushLog)) {
            Log::insert($dataPushLog);
        }

        return response()->json(['message' => 'Logs uploaded successfully'],200);
    
    }
}
