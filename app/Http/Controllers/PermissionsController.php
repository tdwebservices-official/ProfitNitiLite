<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Models\Log;
use Auth;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $permissions = Permission::all();

        return view('permissions.index', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Show form for creating permissions
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {   
        return view('permissions.create');
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
            'name' => 'required|unique:permissions,name'
        ]);

        $permissions = Permission::create($request->only('name'));

        return redirect()->route('permissions.index')
        ->withSuccess(__('Permission created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Permission  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', [
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission){
        $old_permission = $permission->toArray();
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$permission->id
        ]);

        $permission->update($request->only('name'));

        return redirect()->route('permissions.index')
        ->withSuccess(__('Permission updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission_id = $permission->id;
        $permission_name = $permission->name;
        $permission->delete();

        return redirect()->route('permissions.index')
        ->withSuccess(__('Permission deleted successfully.'));
    }

    // User List.

    public function permissionLists(Permission $permission, Request $request) {

        try{

            $columns = ['id', 'name'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $columnIndex = $request->input('order.0.column', 0); 
            $order = isset($columns[$columnIndex]) ? $columns[$columnIndex] : 'id';

            $dir = $request->input('order.0.dir', 'asc');

            $search = $request->input('search.value');

// $users = $user;
            $select = Permission::select('permissions.*');

            if (!empty($search)) {
                $select->where(function($q) use ($search) {
                    $q->where('permissions.name', 'LIKE', '%'.$search.'%');                 
                });
            }

            $filteredData = $select->count();

            $totalData = Permission::count();

            $PermissionData = $select->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)->get();

            $Permission_data = [];
            if( $PermissionData ){
                foreach ( $PermissionData as $Permission_item ){
                   /* $Permission_item->Permission_view = 'no';
                    $Permission_item->Permission_edit = 'no';
                    $Permission_item->Permission_delete = 'no';
                    if( hg_get_all_permission_by_user( 'Permission.show' ) ){
                        $Permission_item->Permission_view = 'yes';
                    }if( hg_get_all_permission_by_user( 'Permission.edit' ) ){
                        $Permission_item->Permission_edit = 'yes';
                    }if( hg_get_all_permission_by_user( 'Permission.destroy' ) ){
                        $Permission_item->Permission_delete = 'yes';
                    }*/
                    $Permission_item->destroy_item_link = route('permissions.destroy', $Permission_item->id);
                    $Permission_data[] = $Permission_item;
                }
            }

            if ( $PermissionData->count() > 0 ) {
                return response()->json([
                    'message' => 'Permission List Get Successfully!',
                    'status' => 'success',
                    'draw' => intval($request->input('draw')),
                    'recordsTotal' => $totalData,
                    'recordsFiltered' => $filteredData,
                    'data' => $PermissionData,
                ],200);
            } else {
                return response()->json([
                    'message' => 'Permission List Get Unsuccessfully!',
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

    // Delete permission.

    public function deletePermission(Request $request) {
        try {
            $id = $request->id;

            if (is_array($id)) {
                Permission::whereIn('id', $id)->delete();
            } else {
                $customer = Permission::find($id);
                if ($customer) {
                    $customer->delete();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Permission successfully deleted.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



}