<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Models\Log;
use Auth;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $roles = Role::orderBy('id','DESC')->paginate(10);
        
        return view('roles.index',compact('roles'))
        ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);


        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));

        return redirect()->route('roles.index')
        ->with('success','Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role = $role;
        $rolePermissions = $role->permissions;

        return view('roles.show', compact('role', 'rolePermissions'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $role = $role;
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::get();

        return view('roles.edit', compact('role', 'rolePermissions', 'permissions'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role, Request $request)
    {
        

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permission' => 'required',
        ]);

        $old_role = $role->toArray();
        
        $role->update($request->only('name'));

        $role->syncPermissions($request->get('permission'));

      
        return redirect()->route('roles.index')
        ->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role_id = $role->id;
        $role_name = $role->name;
        $role->delete();

        return redirect()->route('roles.index')
        ->with('success','Role deleted successfully');
    }

     // Roles List.

    public function roleLists(Permission $permission, Request $request) {

        try{

            $columns = ['id', 'name', 'created_at'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumnIndex = $request->input('order.0.column', 0); 
            $order = $columns[$orderColumnIndex] ?? 'id';              
            $dir = $request->input('order.0.dir', 'desc'); 

            $search = $request->input('search.value');

            $select = Role::select('roles.*');

            if (!empty($search)) {
                $select->where(function($q) use ($search) {
                    $q->where('roles.name', 'LIKE', '%'.$search.'%');                 
                });
            }

            $filteredData = $select->count();

            $totalData = Role::count();

            $RoleData = $select->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)->get();

            $Role_data = [];
            if( $RoleData ){
                foreach ( $RoleData as $Role_item ){
                   /* $Role_item->Role_view = 'no';
                    $Role_item->Role_edit = 'no';
                    $Role_item->Role_delete = 'no';
                    if( hg_get_all_Role_by_user( 'Role.show' ) ){
                        $Role_item->Role_view = 'yes';
                    }if( hg_get_all_Role_by_user( 'Role.edit' ) ){
                        $Role_item->Role_edit = 'yes';
                    }if( hg_get_all_Role_by_user( 'Role.destroy' ) ){
                        $Role_item->Role_delete = 'yes';
                    }*/
                    $Role_data[] = $Role_item;
                }
            }

            if ( $RoleData->count() > 0 ) {
                return response()->json([
                    'message' => 'Roles List Get Successfully!',
                    'status' => 'success',
                    'draw' => intval($request->input('draw')),
                    'recordsTotal' => $totalData,
                    'recordsFiltered' => $filteredData,
                    'data' => $RoleData,
                ],200);
            } else {
                return response()->json([
                    'message' => 'Roles List Get Unsuccessfully!',
                    'status' => 'error',
                ],200);
            }

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }
    }

    // Delete role.

    public function deleteRole(Request $request) {

         try{

            $id = $request->id;

            if( is_array($id) ){
                Role::whereIn('id', $id)->delete();
            }else{
                $customer = Role::find($id);
                $customer->delete();
            }

            return response()->json([
                'status' => 'Role successfully deleted.'
            ],200);

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }
    }

}