<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(){
        return view('app.admin.group.index');
    }

    public function store(StoreGroupRequest $request)
    {
        if (empty($request->id)) {
            // Create a new group record with validated data
            Group::create($request->validated());
            // Return a success message
            return response()->json([
                'message' => 'Group created successfully',
                'status'  => true
            ], 201);
        }else{
            $group = Group::find($request->id);
            $this->update($group, $request->all());
        }

    }


    public function update($group,$request)
    {
        
        // Update the existing group record with validated data
        $group->update($request);

         // Return a success message
        return response()->json([
            'message' => 'Group updated successfully',
            'status'  => true
        ], 201);
    }

    public function list(Request $request){
        
        $query = Group::query();

        // Handle filtering
        if ($request->has('search') && $request->search['value']) {
            $query->where('name', 'like', '%' . $request->search['value'] . '%');
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
