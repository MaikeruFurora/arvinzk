<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Config;
use App\Traits\InitializesZKTeco;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Throwable;

class ConfigController extends Controller
{

    use InitializesZKTeco;

    public function index(){
        return view('app.user.config.index');
    }

    public function store(Request $request){
        $request->validate([
            'ip'    => 'required',
            'port'  => 'required',
        ]);
    }

    public function searchIPWithPort(Request $request){
    
        // Define network range and port
        // $network = '192.168.3.0/24';
        $ipRequest   = $request->ip;
        $portRequest = $request->port;
        $foundIPs    = [];

        $ip = $ipRequest ?? '192.168.0.0/22'; // This covers 192.168.0.0 to 192.168.3.255
        $port = $portRequest ?? 4370; // Port to scan



        // Define the Nmap command with optimizations
        $command = "nmap -p $port -T4 -n --open $ip -oG -";
    
        // Execute the command and capture the output
        exec($command, $output, $return_var);
    
        // Check if the command was successful
        if ($return_var !== 0) {
            
            return response()->json([
                'message' => 'Error executing Nmap command.'. implode("\n", $output),
                'status'  => false,
            ], 400);
            
            exit;
        }
    
        // Process the output
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
            return response()->json([
                'message' => 'No devices with port '.$port.' open found.',
                'status'  => false,
            ], 400);
        } else {
           // Insert found IPs into the database
           try {
               foreach ($foundIPs as $key => $value) {
                   if (!Config::where('user_id', auth()->id())->where('ip', $value)->exists()) {
                       Config::create([
                           'user_id' => auth()->id(),
                           'ip'      => $value,
                           'port'    => $port,
                       ]);
                   }
               }
               return response()->json([
                'message' => 'Found devices with port '.$port.' open.',
                'status'  => true,
                'data'    => [
                    'ip'   => $foundIPs,
                    'port' => $port
                ]
            ],200);

           } catch (Throwable $e) {
               return response()->json([
                   'message' => 'Error occurred while inserting found IPs into the database.',
                   'status'  => false,
                   'error'   => $e->getMessage(),
               ], 500);
           }
        }
    }



    public function list(Request $request){

         $query = Config::query()
         ->select('configs.*','users.name as user_name','groups.name as group_name')
         ->leftjoin('users','configs.user_id','users.id')
         ->leftjoin('groups','users.group_id','groups.id');

        // Handle filtering
        if ($request->has('search') && $request->search['value']) {
            $query->where('configs.name', 'like', '%' . $request->search['value'] . '%');
            $query->orWhere('groups.name', 'like', '%' . $request->search['value'] . '%');
        }
    
        // Handle sorting
        if ($request->has('order')) {
            $column = $request->columns[$request->order[0]['column']]['data'];
            $direction = $request->order[0]['dir'];
            $query->orderBy($column, $direction);
        }
    
        // Handle pagination
        $data = $query->paginate($request->length);
    
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $data->total(),
            'recordsFiltered' => $data->total(),
            'data' => $data->items()
        ]);  


    }

    public function connect(Request $request){

        $deviceInfo=[];
        $config    =Config::query()->where('user_id', auth()->id())->whereId($request->id)->first();
        // $zk        =new ZKTeco('192.168.3.34');
        $zk        =new ZKTeco($config->ip, $config->port);
                    Config::disconnectDevice();
      
        if ($zk->connect()) {
            $deviceInfo =[
                'ip'           => $config->ip,
                'port'         => $config->port,
                'serial_number'=> Config::cleanDeviceInfo($zk->serialNumber()),
                'name'         => Config::cleanDeviceInfo($zk->deviceName()),
                'active_device'=> !$config->active_device,
            ];

            $config->update($deviceInfo);

            return response()->json([
                'message' => ((!$config->active_device)? 'Disconnected ' : 'Connected '). 'successfully',
                'status'  => true,
            ],200);
        
        }else{
            return response()->json([
                'message' => 'Connection failed. Cannot connect to device.',
                'status'  => false,
            ],400);
        }
    }

    public function userDevice()
    {
        $checkUtilityNeeded = $this->checkUtilityNeeded();
        return view('app.user.device.index', ['checkUtilityNeeded' => $checkUtilityNeeded]);
    }

    public function userDeviceStore(Request $request){
        $config    = Config::query()->where('user_id', auth()->id())->where('active_device', 1)->first();
        $zk        = new ZKTeco($config->ip, $config->port);
        $uid       = null;
        if ($zk->connect()) {

            $userList = $zk->getUser();

            if (!empty($userList)) { 
                $lastUser = end($userList); 
                $lastUid = $lastUser['uid']; 
                $uid =  $lastUid + 1;
            } else {
                $uid = count($userList)+1;
            }
           
            $setUserResult = $zk->setUser($uid, $request->userid, $request->name, null,0,0);

            return response()->json([
                'message' => 'Successfully Set User to Device.',
                'status'  => true,
            ],200);

        }else{
            return response()->json([
                'message' => 'Connection failed. Cannot connect to device.',
                'status'  => false,
            ],400);
        }
    }

    public function userDeviceList(Request $request){
        
        $config    =Config::query()->where('user_id', auth()->id())->where('active_device', 1)->first();
        $zk        =new ZKTeco($config->ip, $config->port);
        
        if ($zk->connect()) {

            $userList = $zk->getUser();
            $userMap = [];
            foreach ($userList as $user) {
                $userId = $user['uid'] ?? null; // Ensure 'userid' exists
                if ($userId) {
                    $userMap[] = $user;
                }
            }
            
            return response()->json(['data' => $userMap]);

        }else{
            return response()->json([
                'message' => 'Connection failed. Cannot connect to device.',
                'status'  => false,
            ],400);
        }

    }
        
}
