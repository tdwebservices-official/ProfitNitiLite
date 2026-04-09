<?php

namespace App\Http\Controllers;

use App\Models\KPIRecords;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Illuminate\Support\Facades\Validator;
use Hash;
use Session,DB;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class KPIDataControlller extends Controller
{
    public $kpi_bl_pl_items;

    function __construct() {
        $this->kpi_bl_pl_items = config('chart-of-accounts.kpi_bl_pl_items');
    }



    /**
     * Show form for creating chart account
     */
    public function create() 
    {
        $user_id = auth()->user()->id;
        $user = auth()->user(); // Get the currently authenticated user
        
        return view('kpiRecords.create',[        
            'kpi_bl_pl_items' => $this->kpi_bl_pl_items,       
        ]);
    }

    /**
     * Show form for creating chart account
     */
    public function quickcreate() 
    {
        $user_id = auth()->user()->id;
        $user = auth()->user(); // Get the currently authenticated user
        
        return view('kpiRecords.quick-create',[        
            'kpi_bl_pl_items' => $this->kpi_bl_pl_items,       
        ]);
    }

    /**
     * Display all trial balances
     */
    public function index() 
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('kpiRecords.index', [
            'kpi_bl_pl_items' =>  $this->kpi_bl_pl_items,
            'all_users' => $all_users,
        ]);
    }


     /**
     * Store a new kpi record
     */
    public function storeKPIRecord(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $kpi_items = $request->get('kpi_item');

            foreach ( $kpi_items as $d_key => $k_value ) {

                foreach ( $k_value as $kpi_key => $kpi_value) {
                    
                    $kpi_value = trim($kpi_value);
                    if( !empty($kpi_value) ){
                       $kpi_value = (float)str_replace(',', '', $kpi_value);
                    }

                    if( $kpi_key && array_key_exists( $kpi_key, $this->kpi_bl_pl_items ) ){
                          KPIRecords::create([
                            'tbdate' => date('Y-m-d',strtotime($d_key)),
                            'kpi_name' => $kpi_key,
                            'amount' => $kpi_value,                        
                            'status' => 0,
                            'user_id' => $user_id,
                            'assign_by' => $user_id,
                        ]);
                    }
                }
            }
            return response()->json([
                'message' => 'KPI Records Successfully Created.',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * load kpi records
     */
    public function loadKPIRecord(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $kpi_items = $request->get('kpi_item');

            $file = $request->file('csvFile');
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            $spreadsheet = $reader->load($file->getRealPath());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
           
            return response()->json([
                'message' => 'KPI Records Load Successfully.',
                'status' => 'success',
                'sheetData' => $sheetData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new quick kpi record
     */
    public function storeQuickKPIRecord(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $fromDate = $request->get('fromDate');
            $type = $request->get('type');
            $amount = $request->get('amount');
            KPIRecords::create([
                'tbdate' => date('Y-m-d',strtotime($fromDate)),
                'kpi_name' => $type,
                'amount' => $amount,                        
                'status' => 0,
                'user_id' => $user_id,
                'assign_by' => $user_id,
            ]);
            return response()->json([
                'message' => 'KPI Records Successfully Created.',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get list of trial balances (datatable)
     */
    public function kpiRecordLists(KPIRecords $kpiRecords, Request $request) 
    {
        try {
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $columns = ['id', 'tbdate','kpi_name', 'amount' ];
            $limit = $request->input('length');
            $start = $request->input('start');
            $columnIndex = $request->input('order.0.column');
            $order = $columns[$columnIndex] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'asc';
            $search = $request->input('search.value');
            $assignUserId = $request->input('columns')[4]['search']['value'] ?? null;
            $assignType = $request->input('columns')[2]['search']['value'] ?? null;
            $date_filter = $request->input('columns')[1]['search']['value'] ?? null;

            $query = KPIRecords::query();

            if ($isAdmin && $assignUserId != null ) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $query->where('kpi_records.user_id', $userId);
            }
            if( $assignType != null && $assignType != 0 ){
                $query->where('kpi_records.kpi_name', '=' , $assignType);
            }
          
            if (  !empty( $date_filter ) ) {
                $query->where(
                    function ($query) use ($date_filter) {
                        $between_date = explode( '/', $date_filter );
                        $start_date = trim( $between_date[0] );
                        $end_date = trim( $between_date[1] );
                        $query->whereBetween('kpi_records.tbdate', [$start_date." 00:00:00", $end_date." 23:59:59"]);
                    });
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('kpi_records.kpi_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('kpi_records.amount', 'LIKE', '%' . $search . '%');
                });
            }

            $filteredData = $query->count();
              $totalsQuery = clone $query;
            $listQuery = clone $query;

            $totalsQuery->getQuery()->columns = null;

            $totals = $totalsQuery->selectRaw("
                SUM(kpi_records.amount) as totalKPI
            ")->first();

            $filteredData = $listQuery->count();
            $KPIRECData = $query->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalData = KPIRecords::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('kpi_records.user_id', $userId);
            })->count();

            foreach ($KPIRECData as $account) {
                $account->kpi_name = isset($this->kpi_bl_pl_items[$account->kpi_name]) ? $this->kpi_bl_pl_items[$account->kpi_name] : $account->kpi_name;
                $account->tbdate = date('d/m/Y', strtotime($account->tbdate));
                $account->kpi_record_edit = hg_check_permission_flag_by_user('kpi-record.edit') ? 'yes' : 'no';
                $account->kpi_record_delete = hg_check_permission_flag_by_user('kpi-record.delete') ? 'yes' : 'no';
            }

            return response()->json([
                'message' => 'KPI Records List Retrieved Successfully!',
                'status' => 'success',
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $filteredData,
                'data' => $KPIRECData,
                'totalKPI' => $totals->totalKPI ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update trial balance
     */
    public function updateKPIRecord(Request $request)
    {
        try {
            $user = auth()->user();
            $userId = $user->id;
            $isAdmin = $user->hasRole('Super Admin');

            $kpi_record_id = $request->get('kpi_record_id');
            
            $kpiRec = KPIRecords::when(!$isAdmin, fn($q) => $q->where('user_id', $userId))
                ->where('id', $kpi_record_id)->first();

            if (!$kpiRec) {
                return response()->json([
                    'message' => 'KPI Record not found or access denied.',
                    'status' => 'error',
                ], 404);
            }
            
            $kpiRec->update([
                'amount' => $request->get('amount'),
                'assign_by' => $userId
            ]);
            return response()->json([
                'message' => 'KPI Record successfully updated.',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete trial balance
     */
    public function deleteKPIRecord(Request $request)
    {
        try {
            $user = auth()->user();
            $userId = $user->id;
            $isAdmin = $user->hasRole('Super Admin');

            $id = $request->get('id');

            if ($isAdmin) {
                if( is_array($id) ){
                    KPIRecords::whereIN('id', $id)->delete();   
                }else{
                    KPIRecords::where('id', $id)->delete();
                }
                return response()->json([
                    'message' => 'KPI Record successfully deleted.',
                    'status' => 'success'
                ], 200);
            }

            if( is_array($id) ){
                $item = KPIRecords::where('user_id', $userId)->whereIN('id', $id)->first();
                if ($item) {                   
                    KPIRecords::where('user_id', $userId)->whereIN('id', $id)->delete();
                    return response()->json([
                        'message' => 'KPI Record successfully deleted.',
                        'status' => 'success'
                    ], 200);
                }
            }else{
                $item = KPIRecords::where('user_id', $userId)->where('id', $id)->first();
                if ($item) {
                    $item->delete();
                    return response()->json([
                        'message' => 'KPI Record successfully deleted.',
                        'status' => 'success'
                    ], 200);
                }
            }
          
            return response()->json([
                'message' => "You don't have permission to delete this trial balance.",
                'status' => 'error',
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
