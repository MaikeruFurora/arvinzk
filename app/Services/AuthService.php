<?php

namespace App\Services;

// use App\Helper\Helper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService{
    
    public function login($request){
        
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $request->merge([

            $fieldType => $request->input('username')

        ]);
        

        $credentials = $request->only($fieldType, 'password');

        return $this->userRoute(

            $credentials,

            ['_token','_method'],

            ['msg'=>'Please try again','action'=>'warning']
        );

    }

    public function userRoute($credentials,$data,$errorM){

        if (Auth::guard('web')->attempt($credentials)) {
           
            // Helper::auditLog('Logged In','Logged In');
            if (auth()->user()->is_active=='YES') {
                switch (auth()->user()->type) {
                    case 'bdo':
                            return redirect()->route('authenticate.activity');
                        break;
                    case 'supervisor':
                            return redirect()->route('authenticate.supervisor');
                        break;
                    case 'admin':
                            return redirect()->route('authenticate.admin');
                        break;
                    
                    default:
                    // return redirect()->route('authenticate.activity');
                        break;
                }
            }else{
                Auth::guard('web')->logout();
                return back()->with(['msg'=>'Your account is not active','action'=>'warning']);
            }

        }else{
            
            return back()->with($errorM);

        }

    }

    public function signOut(){

        if (Auth::guard('web')->check()) {

            // Helper::auditLog('Logged In','Logged Out');

            Auth::guard('web')->logout();

            return redirect()->route('auth.signin');

        }

    }

    public function list($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = User::select([
            'users.name',
            'wrhs',
            'type',
            'username',
            'users.id',
            'is_active',
            'wrhs_id',
            'groups.name as group_name',
            'groups.id as group_id'
        ])->leftjoin('groups','users.group_id','groups.id');
    
        if (!empty($filter)) {
            $query
            ->orWhere('users.name', 'like', '%'.$filter.'%')
            ->orWhere('wrhs', 'like', '%'.$filter.'%')
            ->orWhere('type', 'like', '%'.$filter.'%')
            ->orWhere('username', 'like', '%'.$filter.'%'); 
        }
    
        $recordsTotal = $query->count();
    
        $query->take($length)->skip($start);
    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "id"            => $value->id,
                    "name"          => $value->name,
                    "wrhs"          => $value->wrhs,
                    "wrhs_id"       => $value->wrhs_id,
                    "type"          => $value->type,
                    "group_name"    => $value->group_name,
                    "group_id"      => $value->group_id,
                    "is_active"     => $value->is_active,
                    "username"      => $value->username,
                ];
        }

        return $json;
    }

}