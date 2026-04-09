<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMeta;
use App\Models\Log;
use Illuminate\Support\Facades\Validator;
use Hash, DB;
use Session;

class UsersController extends Controller
{
    /**
     * Display all users
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
         $roles = Role::all();
         return view('users.index', compact('roles'));
    }

    /**
     * Show form for creating user
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $industry_list = DB::table('industry')->get();
        $industry_arr = [];

        if( $industry_list ){
            foreach ( $industry_list as $i_key => $i_value ) {
                $industry_arr[$i_value->category][] = $i_value;
            }
        }
        return view( 'users.create', [ 'industry_list' => $industry_arr ] );
    }

    /**
     * Store a newly created user
     * 
     * @param User $user
     * @param StoreUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, StoreUserRequest $request) 
    {
        //For demo purposes only. When creating user or inviting a user
        // you should create a generated random password and email it to the user


        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
        ]);

        
        $user_data = User::create(array_merge($request->validated(), [
            'password' => $request->get('password')
        ]));        
        
       if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/images/users/' . $user_data->id);

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $user_data->image = $filename;
            $user_data->save();
        }

        update_user_meta( $user_data->id, 'industry_id', $request->industry_id );

        return redirect()->route('users.index')
        ->withSuccess(__('User created successfully.'));
    }

    /**
     * Show user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) 
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    // User List.

    public function userLists(User $user, Request $request) {

        try{

             $columns = ['id', 'name', 'username', 'email', 'rolename'];
            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumnIndex = $request->input('order.0.column', 0); 
            $order = $columns[$orderColumnIndex] ?? 'id';              
            $dir = $request->input('order.0.dir', 'desc'); 

            $search = $request->input('search.value');

            // $users = $user;
            $select = User::leftjoin('model_has_roles','users.id','=','model_has_roles.model_id')
            ->leftjoin('roles','model_has_roles.role_id','=','roles.id')
            ->select('users.*','roles.name as rolename');

            if (!empty($search)) {
                $select->where(function($q) use ($search) {
                    $q->where('users.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('users.email', 'LIKE', '%'.$search.'%')
                    ->orWhere('users.username', 'LIKE', '%'.$search.'%')
                    ->orWhere('roles.name', 'LIKE', '%'.$search.'%');
                });
            }

            $filteredData = $select->count();

          $totalData = \App\Models\User::count();
          
            $usersData = $select->offset($start)
                   ->limit($limit)
                   ->orderBy($order, $dir)->get();

            $users_data = [];
            if( $usersData ){
                foreach ( $usersData as $users_item ){
                    $users_item->users_view = 'no';
                    $users_item->users_edit = 'no';
                    $users_item->users_delete = 'no';
                    if( hg_get_all_permission_by_user( 'users.show' ) ){
                        $users_item->users_view = 'yes';
                    }if( hg_get_all_permission_by_user( 'users.edit' ) ){
                        $users_item->users_edit = 'yes';
                    }if( hg_get_all_permission_by_user( 'users.destroy' ) ){
                        $users_item->users_delete = 'yes';
                    }
                    $users_data[] = $users_item;
                }
            }

            if ( $usersData->count() > 0 ) {
                return response()->json([
                    'message' => 'User List Get Successfully!',
                    'status' => 'success',
                    'draw' => intval($request->input('draw')),
                    'recordsTotal' => $totalData,
                    'recordsFiltered' => $filteredData,
                    'data' => $usersData,
                ],200);
            } else {
                return response()->json([
                    'message' => 'User List Get Unsuccessfully!',
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

    // Show box.

    public function showUser(User $user, Request $request) {

        try{

            // $users = $user;
            $users = User::find($request->id);
            $html = '';
            ob_start();
            echo view('users/show',compact('users'));
            $html = ob_get_clean();
            // print_r($html);
            return response()->json(['html'=>$html],200);

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) 
    {

        $industry_id = get_user_meta( $user->id, 'industry_id', true ) ;

        $industry_list = DB::table('industry')->get();
        $industry_arr = [];

        if( $industry_list ){
            foreach ( $industry_list as $i_key => $i_value ) {
                $industry_arr[$i_value->category][] = $i_value;
            }
        }

        return view('users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get(),
            'industry_list' => $industry_arr,  
            'industry_id' => $industry_id,  
        ]);
    }


    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function editProfile() 
    {
        $user = User::find(auth()->user()->id);

        $industry_id = get_user_meta( $user->id, 'industry_id', true );

        $industry_list = DB::table('industry')->get();
        $industry_arr = [];

        if( $industry_list ){
            foreach ( $industry_list as $i_key => $i_value ) {
                $industry_arr[$i_value->category][] = $i_value;
            }
        }

        return view('users.edit-profile', [
            'user' => $user,   
            'industry_list' => $industry_arr,  
            'industry_id' => $industry_id,  
        ]);
    }

    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function changePassword() 
    {
        return view('users.change-password');
    }

    /**
     * Update password
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateChangePassword(Request $request) {
        $request->validate([
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:new_password',
        ]);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->withErrors([
                'old_password' => "Old Password doesn't match!"
            ]);
        }


          User::whereId(auth()->user()->id)->update([
            'password' => bcrypt($request->new_password)
        ]);

        return back()->with('success', 'Password successfully changed!');
    }

    /**
     * Update Edit Profile
     * 
     * @param User $user
     * @param UpdateUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateEditProfile(Request $request) 
    {
        $user = User::find(auth()->user()->id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'required|unique:users,username,'.$user->id,    
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',      
        ]);

        // ✅ Update basic fields
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->username = $request->username;

        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $image->getClientOriginalName());
            $destinationPath = public_path('assets/images/users/' . $user->id);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old image if exists
            if ($user->image) {
                $oldImagePath = $destinationPath . '/' . $user->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image->move($destinationPath, $filename);
            $user->image = $filename;
        }

        $user->save();

        update_user_meta( $user->id, 'industry_id', $request->industry_id );
        
        return redirect()->route('user.edit-profile')
        ->withSuccess(__('Profile updated successfully.'));
    }


    /**
     * Update user data
     * 
     * @param User $user
     * @param UpdateUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UpdateUserRequest $request) 
    {
        $old_users = $user->toArray();
        
        $validate = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'required|unique:users,username,'.$user->id,   
            'new_password' => 'nullable|confirmed|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',      
        ];

           // ✅ Update basic fields
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->username = $request->username;

        
     if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $image->getClientOriginalName());
        $destinationPath = public_path('assets/images/users/' . $user->id);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Delete old image if exists
        if ($user->image) {
            $oldImagePath = $destinationPath . '/' . $user->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $image->move($destinationPath, $filename);
        $user->image = $filename;
    }
    if ($request->filled('new_password')) {
       $user->update(
        array_merge($request->validated(), [
            'password' => $request->get('new_password')
        ])
    );
   }

        $user->save();


        // Sync role correctly
        $roleId = $request->get('role');
        $role = Role::find($roleId);
        if ($role) {
            $user->syncRoles([$role]); // or $role->name
        }

        update_user_meta( $user->id, 'industry_id', $request->industry_id );
        
        return redirect()->route('users.index')
        ->withSuccess(__('User updated successfully.'));
    }


    /**
     * Delete user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) 
    {   
        $user_name = $user->name;
        $user_id = $user->id;
        $user->delete();
        return redirect()->route('users.list')
        ->withSuccess(__('User deleted successfully.'));
    }

     // Delete user data

    public function deleteUser(Request $request)
    {
        try {


            $user = auth()->user();
            $userId = $user->id;
            $isAdmin = $user->hasRole('Super Admin');
    
            $id = $request->get('id');
        
            // Super Admin can delete any user(s)
            if ($isAdmin) {
                if (is_array($id)) {
                    User::whereIn('id', $id)->delete();
                    UserMeta::whereIn('user_id', $id)->delete();                    
                } else {
                    $targetUser = User::find($id);

                    if ($targetUser) {
                        $targetUser->delete();
                        UserMeta::whereIn('user_id', [$id])->delete();
                    }
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'User(s) successfully deleted.'
                ], 200);
            }
    
            // Normal users can only delete themselves
            if (is_array($id)) {
                $item = User::where('id', $userId)->whereIn('id', $id)->first();
                if ($item) {
                    User::where('id', $userId)->whereIn('id', $id)->delete();
                    UserMeta::whereIn('user_id', $userId)->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Your account has been deleted.'
                    ], 200);
                }
            } else {
                if ((int)$id === $userId) {
                    $user->delete();
                    UserMeta::whereIn('user_id',[ $userId ])->delete();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Your account has been deleted.'
                    ], 200);
                }
            }
    
            return response()->json([
                'status' => 'error',
                'message' => "You don't have permission to delete this user."
            ], 403);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



//   public function bulkAssignRole(Request $request)
    // {
    //     try {
    //         $authUser = auth()->user();
    //         $authUserId = $authUser->id;
    //         $isSuperAdmin = $authUser->hasRole('Super Admin');
    
    //         // Only Super Admin can assign roles to other users
    //         if (!$isSuperAdmin) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => "You don't have permission to assign roles."
    //             ], 403);
    //         }
    
    //         // Validate request
    //         $request->validate([
    //             'user_ids' => 'required|array',
    //             'role_id' => 'required|exists:roles,id',
    //         ]);
    
    //         $role = Role::findById($request->role_id);
    
    //         foreach ($request->user_ids as $id) {
    //             $targetUser = User::find($id);
    //             if ($targetUser) {
    //                 $targetUser->syncRoles([$role]);
    //             }
    //         }
    
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Role assigned to selected users successfully.'
    //         ], 200);
    
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }





}