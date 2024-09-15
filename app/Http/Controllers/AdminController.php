<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('app.admin.index');
    }

    public function user(){
        $groups = Group::active()->get(['id','name']);
        $user_types = UserType::active()->get(['id','name']);
        return view('app.admin.user.index',compact('groups','user_types'));
    }

    public function userStore(Request $request){
       
        $data = (empty($request->id)) 

        ? User::storeUser($request)

        : User::updateUser($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function userList(Request $request){
        
        $query = User::query()->select('users.*', 'groups.name as group_name','user_types.name as user_type_name')
                ->join('user_types', 'users.user_type_id', 'user_types.id')
                ->leftJoin('groups', 'users.group_id', 'groups.id');

        // Handle filtering
        if ($request->has('search') && $request->search['value']) {
            $query->where('users.name', 'like', '%' . $request->search['value'] . '%');
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
}
