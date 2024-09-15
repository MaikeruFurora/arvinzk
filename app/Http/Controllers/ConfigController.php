<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    protected $ip;
    protected $port;

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

        if (empty($ipRequest)) {
            $this->ip = $ipRequest;
        }else{
            $this->ip = '192.168.0.0/22'; // This covers 192.168.0.0 to 192.168.3.255
        }

        if (empty($portRequest)) {
            $this->port = $portRequest;
        }else{
            $this->port = 4370; // Port to scan
        }
        // Define the Nmap command with optimizations
        $command = "nmap -p $this->port -T4 -n --open $this->ip -oG -";
    
        // Execute the command and capture the output
        exec($command, $output, $return_var);
    
        // Check if the command was successful
        if ($return_var !== 0) {
            
            return response()->json([
                'message' => 'Error executing Nmap command.',
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
                'message' => 'No devices with port '.$this->port.' open found.',
                'status'  => false,
            ], 400);
        } else {
            return response()->json([
                'message' => 'Found devices with port '.$this->port.' open.',
                'status'  => true,
                'data'    => [
                    'ip'   => $foundIPs,
                    'port' => $this->port
                ]
            ],200);
            // echo "Found devices with port $this->port open:\n";
            // foreach ($foundIPs as $ip) {
            //     echo "IP: $ip\n";
            // }
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



    public function createUserToDevice(Request $request){

    }
        
}
