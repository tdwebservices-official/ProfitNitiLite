<?php
namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Illuminate\Support\Facades\Validator;
use Hash;
use Session;

class NoteController extends Controller
{

    public $note_statuses;

    function __construct() {

        $this->note_statuses = array(
             1 => 'Open',
             2 => 'Partially Closed',             
             3 => 'Closed',             
        );

    }


    /**
     * Display all notes
     * 
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('note.index', [
            'all_users' => $all_users
        ]);
    }

    /**
     * Show form for creating note
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        return view('note.create',[
            'note_statuses' => $this->note_statuses,
        ]);
    }

    public function storeNote(Request $request) {
        try {    
            $user_id = auth()->user()->id;
            $t_month = $request->get('t_month');            
            $target = $request->get('target');            
            $action_steps = $request->get('action_steps');            
            $person_responsible = $request->get('person_responsible');            
            $target_date = $request->get('target_date');            
            $status = $request->get('status');            
            $remarks = $request->get('remarks'); 
            $date_flag = 0;
            $t_month_c = convertDateToYMD($t_month, 'dd-mm-yyyy');
            if ($t_month_c) {
                $t_month = $t_month_c;
            }else{
                $date_flag = 1;
            }

            $target_date_c = convertDateToYMD($target_date, 'dd-mm-yyyy');
            if ($target_date_c) {
                $target_date = $target_date_c;
            }else{
                $date_flag = 1;
            }

            if( $date_flag == 0 ){
                 $note_id = Note::create([
                    't_month' => $t_month,
                    'target' => $target,
                    'action_steps' => $action_steps,
                    'person_responsible' => $person_responsible,
                    'target_date' => $target_date,
                    'status' => $status,
                    'user_id' => $user_id,
                    'remarks' => $remarks,
                ]); 
 
             }else{
                return response()->json([
                    'message' => 'Date Formate is Wrong.',                
                    'status' => 'error',                
                ], 500);
             }
          
            if( !empty($note_id) ){
                 return response()->json([
                    'message' => 'Note Successfully Created.',                
                    'status' => 'success',                
                ], 200);
            }else{
                 return response()->json([
                    'message' => 'Note Not Created.',                
                    'status' => 'error',                
                ], 500);
            }           
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }  
    }

    // Note List
    public function noteLists(Note $note, Request $request) {
        try {
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $columns = ['id', 'target', 'action_steps', 'person_responsible' ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumnIndex = $request->input('order.0.column', 0); 
            $order = $columns[$orderColumnIndex] ?? 'id';              
            $dir = $request->input('order.0.dir', 'desc');
            $search = $request->input('search.value');

            $assignUserId = $request->input('columns')[4]['search']['value'] ?? null;
            $query = Note::query();

            if ($isAdmin && $assignUserId != null ) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $query->where('user_id', $assignUserId);
                }
            } else {
                $query->where('user_id', $userId);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('target', 'LIKE', '%' . $search . '%')
                      ->orWhere('action_steps', 'LIKE', '%' . $search . '%')
                      ->orWhere('person_responsible', 'LIKE', '%' . $search . '%');
                });
            }

            $filteredData = $query->count();

            $noteData = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalData = Note::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('user_id', $userId);
            })->count();

            foreach ($noteData as $note) {
                $note->note_edit = hg_check_permission_flag_by_user('note.edit') ? 'yes' : 'no';
                $note->note_delete = hg_check_permission_flag_by_user('note.delete') ? 'yes' : 'no';
            }

            return response()->json([
                'message' => 'Note List Retrieved Successfully!',
                'status' => 'success',
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $filteredData,
                'data' => $noteData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit note data
     */
    public function edit(Note $note) 
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Super Admin');

        if (!$isAdmin && $note->user_id !== $user->id) {
           return view('notAccess.accessDenied');
        }

        return view('note.edit', [
            'note' => $note,
            'note_statuses' => $this->note_statuses,
        ]);
    }
  
    /**
     * Update note data
     */
    public function updateNote(Request $request) {
        try {
            $user = auth()->user();
            $userId = $user->id;
            $isAdmin = $user->hasRole('Super Admin');

            $noteId = $request->get('note_id');
            $note = Note::when(!$isAdmin, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })->where('id', $noteId)->first();

            if (!$note) {
                return response()->json([
                    'message' => 'Note not found or access denied.',
                    'status' => 'error',
                ], 404);
            }

            $t_month = $request->get('t_month');            
            $target = $request->get('target');            
            $action_steps = $request->get('action_steps');            
            $person_responsible = $request->get('person_responsible');            
            $target_date = $request->get('target_date');            
            $status = $request->get('status');            
            $remarks = $request->get('remarks'); 
            $date_flag = 0;
            $t_month_c = convertDateToYMD($t_month, 'dd-mm-yyyy');
            if ($t_month_c) {
                $t_month = $t_month_c;
            }else{
                $date_flag = 1;
            }

            $target_date_c = convertDateToYMD($target_date, 'dd-mm-yyyy');
            if ($target_date_c) {
                $target_date = $target_date_c;
            }else{
                $date_flag = 1;
            }

            if( $date_flag == 0 ){
                $note->update([
                    't_month' => $t_month,
                    'target' => $target,
                    'action_steps' => $action_steps,
                    'person_responsible' => $person_responsible,
                    'target_date' => $target_date,
                    'status' => $status,
                    'remarks' => $remarks,
                ]);
            }else{
                return response()->json([
                    'message' => 'Date format is wrong.',                
                    'status' => 'error',                
                ], 500);
            }

            return response()->json([
                'message' => 'Note successfully updated.',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Delete note data
    public function deleteNote(Request $request) {
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $isAdmin = $user->hasRole('Super Admin');

            $ids = $request->get('id');

            if ($isAdmin) {
                if (is_array($ids)) {
                    Note::whereIn('id', $ids)->delete();
                } else {
                    Note::where('id', $ids)->delete();
                }
                return response()->json([
                    'message' => "Note(s) successfully deleted.",
                    'status' => 'success'
                ], 200);
            } else {
                if (is_array($ids)) {
                    $notes = Note::where('user_id', $user_id)->whereIn('id', $ids)->get();
                    if ($notes->count()) {
                        Note::where('user_id', $user_id)->whereIn('id', $ids)->delete();
                        return response()->json([
                            'message' => "Note(s) successfully deleted.",
                            'status' => 'success'
                        ], 200);
                    }
                } else {
                    $note = Note::where('user_id', $user_id)->where('id', $ids)->first();
                    if ($note) {
                        $note->delete();
                        return response()->json([
                            'message' => "Note successfully deleted.",
                            'status' => 'success'
                        ], 200);
                    }
                }

                return response()->json([
                    'message' => "You don't have permission to delete this note.",
                    'status' => 'error',
                ], 200);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
