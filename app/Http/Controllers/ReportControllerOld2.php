<?php

namespace App\Http\Controllers;
use App\Models\ChartAccount;
use App\Models\TrialBalance;
use App\Models\CostCenter;
use App\Http\Controllers\DashboardController; 
use App\Models\Branch;
use App\Models\Options;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Services\DeepSeekService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfService;


use DB;
use Validator;
use File;

class ReportController extends Controller
{
    public $financial_summary_list;
    public $balance_sheet_list;
    public $balance_sheet_list_keys;
    public $power_profit_report_list;
    public $cash_management_report_list;
    public $capex_report_list;
    public $balancesheet_report_list;
    public $new_financial_summary_report_list;
    public $financing_report_list;
    public $profitloss_report_list;
    public $impact_of_change_report_list;
    public $cashflow_quality_report_list;

    protected $deepseek;

    function __construct(DeepSeekService $deepseek, PdfService $pdf)
    {

        $this->deepseek = $deepseek;
        $this->pdf = $pdf;

        $this->financial_summary_list = config('chart-of-accounts.financial_summary_list');
        $this->power_profit_report_list = config('chart-of-accounts.power_profit_report_list');
        $this->balancesheet_report_list = config('chart-of-accounts.balancesheet_report_list');
        $this->cash_management_report_list = config('chart-of-accounts.cash_management_report_list');
        $this->capex_report_list = config('chart-of-accounts.capex_report_list');
        $this->profitloss_report_list = config('chart-of-accounts.profitloss_report_list');
        $this->cashflow_quality_report_list = config('chart-of-accounts.cashflow_quality_report_list');
        $this->impact_of_change_report_list = config('chart-of-accounts.impact_of_change_report_list');
        $this->balance_sheet_list = config('chart-of-accounts.default');
        $this->balance_sheet_list_keys = config('chart-of-accounts.default_keys');
        $this->new_financial_summary_report_list = config('chart-of-accounts.new_financial_summary_report_list');
        $this->financing_report_list = config('chart-of-accounts.financing_report_list');
    }

    /**
     * Profit & Balance Report View
     */
    public function plReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.PLReport', [
            'all_users' => $all_users,
        ]);
    }

    /**
     * AI Report View
     */
    public function aiReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }

        if ($user->hasRole('Super Admin')) {
            $all_branches = Branch::get();
        } else {
            $all_branches = Branch::where('id', $user->id)->get();
        }

      
        return view('reports.AIReport', [
            'all_users' => $all_users,
            'all_branches' => $all_branches,
        ]);
    }

    public function checkDataCSV()
    {
        // $csv_url = 'https://frms.gchprojects.xyz/public/assets/bs-category.csv';
        // $array = [];
        // $i = 0;
        // if (($open = fopen($csv_url, "r")) !== false) {
        //     while (($data = fgetcsv($open, 1000, ",")) !== false) {
        //         if( $i > 0 ){
        //             $array[] = $data;
        //         }
        //         $i++;
        //     }

        //     fclose($open);
        // }
        // $data_obj = [];
        // $m = 0;
        // foreach ( $array as $key => $value ) {
        //     $j = 0;
        //     $k = 0;
        //     $data_obj[$m]['name'] = $value[0];
        //     unset($value[0]);
        //     $value = array_values($value);
        //     foreach ( $value as $key => $s_value ) {
        //         $data_obj[$m]['data'][$k][$j] = $s_value;
        //         if( $j == 4 ){
        //             $j = 0;    
        //             $k++;
        //         }else{
        //             $j++;  
        //         }      

        //     }            
        //     $m++;
        // }
        // $ratio_arr = array(
        //     'Current Ratio',
        //     'Quick Ratio',
        //     'Debt-to-Equity',
        //     'Asset Turnover',
        //     'ROE',
        //     'ROA',
        //     'Gross Margin',
        //     'Net Margin',
        //     'Interest Coverage',
        //     'Receivables Days',
        //     'Operating CF Margin',
        //     'CF Coverage',
        //     'CF to Debt',
        //     'Capex Coverage',
        //     'Payable Days',
        //     'Working Capital Days',
        // );
        // $nratio_arr = [];
        // if( $data_obj ){
        //     foreach ( $data_obj as $key => $dd_value ) {
        //         $ratio_name = $dd_value['name'];
        //         $data1 = DB::table('industry')->where('name','like', $ratio_name)->first();                
        //         unset($dd_value['data'][16]);
        //         foreach ( $dd_value['data'] as $key => $ss_value ) {
        //             $rtname = explode('/', $ss_value[0]);
        //             $rtname = explode('-', $rtname[1]);
        //             $rtname[0] = str_replace(['x','%'], '', $rtname[0]);
        //             $rtname[1] = str_replace(['x','%'], '', $rtname[1]);

        //             $ss_value[1] = str_replace(['x','%'], '', $ss_value[1]);
        //             $ss_value[2] = str_replace(['x','%'], '', $ss_value[2]);
        //             $ss_value[3] = str_replace(['x','%'], '', $ss_value[3]);
        //             $ss_value[4] = str_replace(['x','%'], '', $ss_value[4]);

        //             $nratio_arr[] = array(
        //                 'industry_id' => $data1->id,
        //                 'ratio_name' => $ratio_arr[$key],
        //                 'default_ratio' => $ss_value[0],
        //                 'min_ratio' => $rtname[0],
        //                 'max_ratio' => $rtname[1],
        //                 'wt' => $ss_value[1],
        //                 'strong' => $ss_value[2],
        //                 'steady' => $ss_value[3],
        //                 'shaky' => $ss_value[4]
        //             );

        //             DB::table('bs_category_ratio')->insert([
        //                'industry_id' => $data1->id,
        //                'ratio_name' => $ratio_arr[$key],
        //                'default_ratio' => $ss_value[0],
        //                'min_ratio' => $rtname[0],
        //                'max_ratio' => $rtname[1],
        //                'wt' => $ss_value[1],
        //                'strong' => $ss_value[2],
        //                'steady' => $ss_value[3],
        //                'shaky' => $ss_value[4]
        //             ]);
        //         }
        //     }
        // }

        echo "string";
        die;
    }

    /**
     * Balance Sheet Report View
     */
    public function blReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.BLReport', [
            'all_users' => $all_users,
        ]);
    }

    /**
     * Cashflow Report View
     */
    public function cashflowReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.CashflowReport', [
            'all_users' => $all_users,
        ]);
    }

    /**
     * Financial Summery Report View
     */
    public function fnsReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.FNSReport', [
            'all_users' => $all_users,
        ]);
    }

    /**
     * Cash Management Report View
     */
    public function cashMngReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.CashMngReport', [
            'all_users' => $all_users,
        ]);
    }

    /* Capex Report View
     *
     */
    public function CapexReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.CapexReport', [
            'all_users' => $all_users,
        ]);
    }

    /* Financial Report View
     *
     */
    public function FinancingReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.FinancingReport', [
            'all_users' => $all_users,
        ]);
    }

    /* Impact Of Change Report View
     *
     */
    public function ImpactOfChangeReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.ImpactOfChangeReport', [
            'all_users' => $all_users,
        ]);
    }

    /* BS Category Report View
     *
     */
    public function BSCategoryReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }      

        return view('reports.BSCategoryReport', [
            'all_users' => $all_users,
        ]);
    }


    /* Cashflow Quality Report View
     *
     */
    public function CashFlowQualityReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.CashFlowQualityReport', [
            'all_users' => $all_users,
        ]);
    }

    /**
     * Profit Power Report View
     */
    public function profitPowerReports()
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin')) {
            $all_users = User::get();
        } else {
            $all_users = User::where('id', $user->id)->get();
        }
        return view('reports.ProfitPowerReport', [
            'all_users' => $all_users,
        ]);
    }


    public function getProfitPowerReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $report_type = $request->get('report_type');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }


            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->power_profit_report_list;
            $column_width_list = [
                'A' => 250
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';


            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            // $start_col = hg_get_next_excel_cell_names( '', str_replace( $start_row , '', $start_col ), 1 ); 
            //  $qt_data_column_range = [];
            //  if( is_array($quater_grouped) && count( $quater_grouped ) > 0 ){
            //      $qt_d_cnt = count( $quater_grouped );
            //      $qt_d_cnt  = $qt_d_cnt  - 1;
            //      $quater_list_arr = array_values( $quater_grouped );
            //      $quater_header_cell = hg_generate_excel_cell_names( $start_row, $qt_d_cnt, $start_col ); 
            //      if( $quater_header_cell ){
            //          $start_col = end($quater_header_cell);                        
            //          foreach( $quater_header_cell as $qt_header_index => $qtr_range_index ){
            //              if( isset( $quater_list_arr[$qt_header_index] ) && !empty( $quater_list_arr[$qt_header_index] ) ){   
            //                  $tb_header[ $qtr_range_index ] = [
            //                      'label' => $quater_list_arr[$qt_header_index]['label'],
            //                      'style' => hg_comman_customer_header_style( 'bfbfbf' )      
            //                  ];    
            //                  $column_width_list[str_replace( $start_row , '', $qtr_range_index )] = 125;
            //                  $qt_data_column_range[ $quater_list_arr[$qt_header_index]['label'] ] = array(
            //                      'column' => str_replace( $start_row , '', $qtr_range_index ),
            //                  ); 
            //              }
            //          }
            //      }
            //  }

            //  $start_col = hg_get_next_excel_cell_names( '', str_replace( $start_row , '', $start_col ), 0 ); 
            //  $year_data_column_range = [];
            //  if( is_array($year_grouped) && count( $year_grouped ) > 0 ){
            //      $yt_d_cnt = count( $year_grouped );
            //      $yt_d_cnt  = $yt_d_cnt  - 1;
            //      $year_list_arr = array_values( $year_grouped );
            //      $year_header_cell = hg_generate_excel_cell_names( $start_row, $yt_d_cnt, $start_col ); 
            //      if( $year_header_cell ){
            //          $start_col = end($year_header_cell);                        
            //          foreach( $year_header_cell as $yt_header_index => $yt_range_index ){
            //              if( isset( $year_list_arr[$yt_header_index] ) && !empty( $year_list_arr[$yt_header_index] ) ){   
            //                  $tb_header[ $yt_range_index ] = [
            //                      'label' => $year_list_arr[$yt_header_index]['label'],
            //                      'style' => hg_comman_customer_header_style( 'bfbfbf' )      
            //                  ];    
            //                  $column_width_list[str_replace( $start_row , '', $yt_range_index )] = 125;
            //                  $year_data_column_range[ $year_list_arr[$yt_header_index]['label'] ] = array(
            //                      'column' => str_replace( $start_row , '', $yt_range_index ),
            //                  ); 
            //              }
            //          }
            //      }
            //  }


            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/pf-pw-report/';

            $rp_po_path = 'pf-pw-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Profit Power Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = $all_item_rows = $final_all_total = $dataColCell = [];
            $i = 0;

            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {
                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }
                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                        $dataColCell[$p_index][$tbdate_column_group['column']] = $cell_val;
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }
                            // if( $qt_data_column_range ){
                            //     foreach( $qt_data_column_range as $qt_index => $qt_column_group ){  
                            //         $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                            //         $q_cell_val = round($q_cell_val,2);
                            //         if( !isset($sub_items['download_tr_hide']) ){                                        
                            //            $spreadsheet->getActiveSheet()->getCell( $qt_column_group['column'].$p_index )->setValue( convert_decimal_format( $q_cell_val ) )->getStyle( $qt_column_group['column'].$p_index )->applyFromArray($subitemStyle);
                            //            $dataColCell[$p_index][$qt_column_group['column']] = $q_cell_val;
                            //         }
                            //     }
                            // }
                            // if( $year_data_column_range ){
                            //     foreach( $year_data_column_range as $yrt_index => $yrt_column_group ){  
                            //         $r_yrt_index = str_replace('YTD-','', $yrt_index);
                            //         $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                            //         $y_cell_val = round($y_cell_val,2);
                            //         if( !isset($sub_items['download_tr_hide']) ){                                        
                            //             $spreadsheet->getActiveSheet()->getCell( $yrt_column_group['column'].$p_index )->setValue( convert_decimal_format( $y_cell_val ) )->getStyle( $yrt_column_group['column'].$p_index )->applyFromArray($subitemStyle);
                            //             $dataColCell[$p_index][$yrt_column_group['column']] = $y_cell_val;
                            //         }
                            //     }
                            // }
                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                // $s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;
                                // $s_cell_val = round($s_cell_val,2);

                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);

                                    $dataColCell[$p_index][$s_tbdate_column_group['column']] = $s_cell_val;
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        // if( $qt_data_column_range ){
                        //     foreach( $qt_data_column_range as $qt_index => $qt_column_group ){  
                        //         $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                        //         $q_cell_val = round($q_cell_val,2);
                        //         if( !isset($bl_key['data']['total']['hide_tr']) ){
                        //             $spreadsheet->getActiveSheet()->getCell( $qt_column_group['column'].$p_index )->setValue( convert_decimal_format( $q_cell_val ) )->getStyle( $qt_column_group['column'].$p_index )->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        //             $dataColCell[$p_index][$qt_column_group['column']] = $q_cell_val;
                        //         }
                        //     }
                        // }
                        // if( $year_data_column_range ){
                        //     foreach( $year_data_column_range as $yrt_index => $yrt_column_group ){  
                        //         $r_yrt_index = str_replace('YTD-','', $yrt_index);
                        //         $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;
                        //         $y_cell_val = round($y_cell_val,2);
                        //         if( !isset($bl_key['data']['total']['hide_tr']) ){
                        //             $spreadsheet->getActiveSheet()->getCell( $yrt_column_group['column'].$p_index )->setValue( convert_decimal_format( $y_cell_val ) )->getStyle( $yrt_column_group['column'].$p_index )->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        //             $dataColCell[$p_index][$yrt_column_group['column']] = $y_cell_val;
                        //         }
                        //     }
                        // }
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }

                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        if (!isset($final_items['hide_tr'])) {
                                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val,$decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                            $dataColCell[$p_index][$n_tbdate_column_group['column']] = $n_cell_val;
                                        }

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                // if( $qt_data_column_range ){
                                //     foreach( $qt_data_column_range as $qt_index => $qt_column_group ){  
                                //         $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;
                                //         $q_cell_val = round($q_cell_val,2);
                                //         if( !isset($final_items['hide_tr']) ){
                                //             $spreadsheet->getActiveSheet()->getCell( $qt_column_group['column'].$p_index )->setValue( convert_decimal_format( $q_cell_val ) )->getStyle( $qt_column_group['column'].$p_index )->applyFromArray(($final_items['bold'] == true)  ? $itemStyle : $subitemStyle);
                                //             $dataColCell[$p_index][$qt_column_group['column']] = $q_cell_val;
                                //         }
                                //     }
                                // }
                                // if( $year_data_column_range ){
                                //     foreach( $year_data_column_range as $yrt_index => $yrt_column_group ){  
                                //         $r_yrt_index = str_replace('YTD-','', $yrt_index);
                                //         $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                //         $y_cell_val = round($y_cell_val,2);
                                //         if( !isset($final_items['hide_tr']) ){
                                //             $spreadsheet->getActiveSheet()->getCell( $yrt_column_group['column'].$p_index )->setValue( convert_decimal_format( $y_cell_val ) )->getStyle( $yrt_column_group['column'].$p_index )->applyFromArray(($final_items['bold'] == true)  ? $itemStyle : $subitemStyle);
                                //             $dataColCell[$p_index][$yrt_column_group['column']] = $y_cell_val;
                                //         }
                                //     }
                                // }
                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                    //$all_item_rows[] = 'A'.$p_index;
                }
                //$all_item_rows[] = 'A'.$p_index;  
            }

            if (isset($dataColCell[4])) {
                $first_index = 0;
                $revenue_growth = array_values($dataColCell[4]);
                foreach ($dataColCell[4] as $m_key => $m_value) {
                    if ($first_index > 0) {
                        $data_value = comman_growth_formula($revenue_growth[$first_index - 1], $revenue_growth[$first_index], 2);                       
                        $spreadsheet->getActiveSheet()->getCell($m_key . '5')->setValue(convert_decimal_format($data_value))->getStyle($m_key . '5')->applyFromArray($subitemStyle);
                        $dataColCell[5][$m_key] = $data_value;
                    }
                    $first_index++;
                }
            }

            if (isset($dataColCell[6])) {
                $cg_index = 0;
                $cogs_growth = array_values($dataColCell[6]);
                foreach ($dataColCell[6] as $m_key => $m_value) {
                    if ($cg_index > 0) {
                        $data_value = comman_growth_formula($cogs_growth[$cg_index - 1], $cogs_growth[$cg_index], 2);
                        $spreadsheet->getActiveSheet()->getCell($m_key . '7')->setValue(convert_decimal_format($data_value))->getStyle($m_key . '7')->applyFromArray($subitemStyle);
                        $dataColCell[7][$m_key] = $data_value;
                    }
                    $cg_index++;
                }
            }

            if (isset($dataColCell[10])) {
                $og_index = 0;
                $overheads_growth = array_values($dataColCell[10]);
                foreach ($dataColCell[6] as $m_key => $m_value) {
                    if ($og_index > 0) {
                        $data_value = comman_growth_formula($overheads_growth[$og_index - 1], $overheads_growth[$og_index], 2);
                        $spreadsheet->getActiveSheet()->getCell($m_key . '12')->setValue(convert_decimal_format($data_value))->getStyle($m_key . '12')->applyFromArray($subitemStyle);
                        $dataColCell[12][$m_key] = $data_value;
                    }
                    $og_index++;
                }
            }

            if (isset($final_all_total['Interest & Bank Charges']) && count($final_all_total['Interest & Bank Charges']) > 0) {
                //Operating Profit              
                foreach ($dataColCell[9] as $m_key => $m_value) {
                    $bank_chng = isset($final_all_total['Interest & Bank Charges'][$m_key]) ? $final_all_total['Interest & Bank Charges'][$m_key] : 0;
                    $opt_chng = isset($final_all_total['Operating Profit'][$m_key]) ? $final_all_total['Operating Profit'][$m_key] : 0;
                    $d_value = 0;
                    if ($opt_chng > 0) {
                        $d_value = round(($bank_chng / $opt_chng) * 100, 2);

                    }
                    $spreadsheet->getActiveSheet()->getCell($m_key . '19')->setValue(convert_decimal_format($d_value))->getStyle($m_key . '19')->applyFromArray($subitemStyle);
                    $dataColCell[19][$m_key] = $d_value;
                }
            }

            if (isset($dataColCell[9])) {
                $compare_data_list = [
                    ['cell_no' => 9, 'check_index' => [4, 8]],
                    ['cell_no' => 11, 'check_index' => [4, 10]],
                    ['cell_no' => 14, 'check_index' => [4, 13]],
                    ['cell_no' => 14, 'check_index' => [4, 13]],
                    ['cell_no' => 17, 'check_index' => [4, 16]],
                    ['cell_no' => 20, 'check_index' => [9, 10]],
                ];

                foreach ($compare_data_list as $key => $compare_value) {
                    foreach ($dataColCell[9] as $m_key => $m_value) {
                        $d_value = comman_module_formula($dataColCell[$compare_value['check_index'][0]][$m_key], $dataColCell[$compare_value['check_index'][1]][$m_key], 2);
                        $spreadsheet->getActiveSheet()->getCell($m_key . $compare_value['cell_no'])->setValue(convert_decimal_format($d_value))->getStyle($m_key . $compare_value['cell_no'])->applyFromArray($subitemStyle);
                        $dataColCell[$compare_value['cell_no']][$m_key] = $d_value;
                    }
                }
            }

            if (is_array($all_item_rows) && count($all_item_rows) > 0) {
                foreach ($all_item_rows as $a_key => $a_value) {
                    $color = ($a_key % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                    $spreadsheet->getActiveSheet()
                        ->getStyle($a_value)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB($color);

                    $cnt_index = str_replace('A', '', $a_value);

                    if ($data_column_range) {
                        foreach ($data_column_range as $at_index => $at_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($at_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    // if( $qt_data_column_range ){
                    //     foreach( $qt_data_column_range as $nn_qt_index => $nn_column_group ){  
                    //         $spreadsheet->getActiveSheet()
                    //         ->getStyle($nn_column_group['column'].$cnt_index)
                    //         ->getFill()
                    //         ->setFillType(Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB($color);
                    //     }
                    // }

                    // if( $year_data_column_range ){
                    //     foreach( $year_data_column_range as $nn_syrt_index => $nn_syrt_column_group ){  
                    //         $spreadsheet->getActiveSheet()
                    //         ->getStyle($nn_syrt_column_group['column'].$cnt_index)
                    //         ->getFill()
                    //         ->setFillType(Fill::FILL_SOLID)
                    //         ->getStartColor()
                    //         ->setARGB($color);
                    //     }
                    // }

                }
            }


            $last_cell_index = $p_index - 1;
            $sheet->getStyle('A3:' . $end_cell . $last_cell_index)->getNumberFormat()->setFormatCode('0.00')->applyFromArray($comman_all_border);

            if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            }

            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'Profit Power Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/pf-pw-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();


                return response()->json([
                    'message' => 'Profit Power Report Generated Successfully!',
                    'status' => 'success',
                    'table_html' => $html,
                ], 200);

            }

        } catch (\Exception $e) {
            if ($return_data == 1) {
                return [];
            }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getCashMngReport(Request $request, $return_data = 0 )
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $report_type = $request->get('report_type');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->cash_management_report_list;

            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),

                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/cash-mng-report/';

            $rp_po_path = 'cash-mng-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Cash Management Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {
                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }
                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {

                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }


                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }



                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);

                                        if (!isset($final_items['hide_tr'])) {
                                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val,$decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                        }

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }


                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                }

            }

            $p_index = 4;
            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue('Accounts Receivable Days')->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Inventory Days')->getStyle('A5')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Accounts Payable Days')->getStyle('A6')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Working Capital Days')->getStyle('A7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Working Capital')->getStyle('A8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A9')->setValue('Working Capital per ₹100')->getStyle('A9')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A10')->setValue('Working Capital Turnover')->getStyle('A10')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A11')->setValue('Marginal Cash Flow')->getStyle('A11')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A12')->setValue('Current Ratio')->getStyle('A12')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A13')->setValue('Quick Ratio')->getStyle('A13')->applyFromArray($subitemStyle);
            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                    $n_cell_val1 = comman_cashmg_formula($final_all_total['Sales'][$n_tbdate_column_group['column']], $final_all_total['Accounts Receivable'][$n_tbdate_column_group['column']], 1, 1, 2);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '4')->setValue(convert_decimal_format($n_cell_val1))->getStyle($n_tbdate_column_group['column'] . '4')->applyFromArray($itemStyle);

                    $n_cell_val2 = comman_cashmg_formula($final_all_total['Total Direct Expenses'][$n_tbdate_column_group['column']], $final_all_total['Closing Stock'][$n_tbdate_column_group['column']], 1, 1, 2);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '5')->setValue(convert_decimal_format($n_cell_val2))->getStyle($n_tbdate_column_group['column'] . '5')->applyFromArray($itemStyle);

                    $n_cell_val3 = comman_cashmg_formula($final_all_total['Total Direct Expenses'][$n_tbdate_column_group['column']], $final_all_total['Accounts Payable'][$n_tbdate_column_group['column']], 1, 1, 2);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '6')->setValue(convert_decimal_format($n_cell_val3))->getStyle($n_tbdate_column_group['column'] . '6')->applyFromArray($itemStyle);

                    $n_cell_val4 = ($n_cell_val1 + $n_cell_val2) - $n_cell_val3;
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '7')->setValue(convert_decimal_format($n_cell_val4))->getStyle($n_tbdate_column_group['column'] . '7')->applyFromArray($itemStyle);

                    $n_cell_val5 = ($final_all_total['Accounts Receivable'][$n_tbdate_column_group['column']] + $final_all_total['Closing Stock'][$n_tbdate_column_group['column']]) - $final_all_total['Accounts Payable'][$n_tbdate_column_group['column']];
                    $n_cell_val5 = round($n_cell_val5);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '8')->setValue(convert_decimal_format($n_cell_val5,0))->getStyle($n_tbdate_column_group['column'] . '8')->applyFromArray($itemStyle);

                    $denominator = $final_all_total['Sales'][$n_tbdate_column_group['column']] * 1 * (1 / 100);
                    if ($final_all_total['Sales'][$n_tbdate_column_group['column']] != 0) {
                        $n_cell_val6 = ($n_cell_val5 / $final_all_total['Sales'][$n_tbdate_column_group['column']]) * 100;
                        $n_cell_val6 = round($n_cell_val6, 2);
                    } else {
                        $n_cell_val6 = 0;
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '9')->setValue(convert_decimal_format($n_cell_val6))->getStyle($n_tbdate_column_group['column'] . '9')->applyFromArray($itemStyle);

                    if ($n_cell_val5 != 0) {
                        //$n_cell_val7 = ((($final_all_total['Sales'][$n_tbdate_column_group['column']] * count($data_column_range)) * (1 / count($data_column_range))) / $n_cell_val5) * count($data_column_range);

                        $n_cell_val7 = ($final_all_total['Sales'][$n_tbdate_column_group['column']] / $n_cell_val5) * (12 / 1);
                        $n_cell_val7 = round($n_cell_val7, 2);
                    } else {
                        $n_cell_val7 = 0;
                    }


                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '10')->setValue(convert_decimal_format($n_cell_val7))->getStyle($n_tbdate_column_group['column'] . '10')->applyFromArray($itemStyle);

                    $gross_margin = comman_module_formula($final_all_total['Sales'][$n_tbdate_column_group['column']], $final_all_total['Gross Profit'][$n_tbdate_column_group['column']], 2);

                    $cashflow_mrg = $gross_margin - $n_cell_val6;

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '11')->setValue(convert_decimal_format($cashflow_mrg))->getStyle($n_tbdate_column_group['column'] . '11')->applyFromArray($itemStyle);
                    $n_cell_val10 = 0;
                    if ($final_all_total['Current Liabilities'][$n_tbdate_column_group['column']] != 0) {
                        $n_cell_val10 = round($final_all_total['Current Assets'][$n_tbdate_column_group['column']] / $final_all_total['Current Liabilities'][$n_tbdate_column_group['column']], 2);
                    }
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '12')->setValue(convert_decimal_format($n_cell_val10))->getStyle($n_tbdate_column_group['column'] . '12')->applyFromArray($itemStyle);

                    $n_cell_val11 = 0;
                    if ($final_all_total['Current Liabilities'][$n_tbdate_column_group['column']] != 0) {
                        $n_cell_val11 = ($final_all_total['Current Assets'][$n_tbdate_column_group['column']] - $final_all_total['Closing Stock'][$n_tbdate_column_group['column']]) / $final_all_total['Current Liabilities'][$n_tbdate_column_group['column']];
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '13')->setValue(convert_decimal_format($n_cell_val11))->getStyle($n_tbdate_column_group['column'] . '13')->applyFromArray($itemStyle);

                }
            }


            for ($ih = 4; $ih <= 13; $ih++) {
                $color = ($ih % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                $spreadsheet->getActiveSheet()
                    ->getStyle('A' . $ih)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB($color);

                if ($data_column_range) {
                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                        $spreadsheet->getActiveSheet()
                            ->getStyle($n_tbdate_column_group['column'] . $ih)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB($color);
                    }
                }
            }

            $sheet->getStyle('A3:' . $end_cell . '13')->applyFromArray($comman_all_border)->getNumberFormat()->setFormatCode('0.00');

            if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            }

            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);

                return response()->json([
                    'message' => 'Cash Management Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/cash-mng-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();

                return response()->json([
                    'message' => 'Cash Management Report Generated Successfully!',
                    'status' => 'success',
                    'table_html' => $html,
                ], 200);

            }


        } catch (\Exception $e) {
            if ($return_data == 1) {                
                return [];
            }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }
    public function getFinancingReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $report_type = $request->get('report_type');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->financing_report_list;

            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),

                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/financing-report/';

            $rp_po_path = 'financing-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Financing Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {
                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }
                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {
                                        $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',', '', $final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }


                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {

                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);

                                        if (!isset($final_items['hide_tr'])) {
                                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val,$decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                        }

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }


                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                }

            }
            $p_index = 4;

            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue('Marginal Cash Flow')->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Operating Cash Flow')->getStyle('A5')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Operating Cash Profit')->getStyle('A6')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Net Cash Flow')->getStyle('A7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Net Debt')->getStyle('A8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A9')->setValue('Total Debt')->getStyle('A9')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A10')->setValue('Debt to Equity')->getStyle('A10')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A11')->setValue('Debt to Capital')->getStyle('A11')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A12')->setValue('Interest Cover')->getStyle('A12')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A13')->setValue('Debt Payback')->getStyle('A13')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A14')->setValue('Total Funding')->getStyle('A14')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A15')->setValue('Operating CF Margin')->getStyle('A15')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A16')->setValue('Cash Flow Coverage')->getStyle('A16')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A17')->setValue('Cash Flow to Debt')->getStyle('A17')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A18')->setValue('Capex Coverage')->getStyle('A18')->applyFromArray($subitemStyle);

            $old_cell_no = 'A';


            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {

                    $gross_margin = comman_module_formula($final_all_total['Sales'][$n_tbdate_column_group['column']], $final_all_total['Gross Profit'][$n_tbdate_column_group['column']], 2);

                    $working_capital = ($final_all_total['Accounts Receivable'][$n_tbdate_column_group['column']] + $final_all_total['Closing Stock'][$n_tbdate_column_group['column']]) - $final_all_total['Accounts Payable'][$n_tbdate_column_group['column']];
                    $working_capital = round($working_capital);

                    $working_capital_100 = 0;
                    $wrk_denominator = $final_all_total['Sales'][$n_tbdate_column_group['column']] * 1 * (count($data_column_range) / 100);
                    if ($wrk_denominator != 0) {
                        $working_capital_100 = $working_capital / $wrk_denominator;
                        $working_capital_100 = round($working_capital_100);
                    }

                    $n_cell_val1 = $gross_margin - $working_capital_100;
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '4')->setValue(convert_decimal_format($n_cell_val1,0))->getStyle($n_tbdate_column_group['column'] . '4')->applyFromArray($itemStyle);

                    $operating_cash_profit = $final_all_total['Operating Profit'][$n_tbdate_column_group['column']] + $final_all_total['Depreciation & Amortization'][$n_tbdate_column_group['column']];
                    $operating_cash_profit = round($operating_cash_profit);

                    $acc_rec = $final_all_total['Accounts Receivable'][$n_tbdate_column_group['column']] - (isset($final_all_total['Accounts Receivable'][$old_cell_no]) ? $final_all_total['Accounts Receivable'][$old_cell_no] : 0);
                    $closing_st = $final_all_total['Closing Stock'][$n_tbdate_column_group['column']] - (isset($final_all_total['Closing Stock'][$old_cell_no]) ? $final_all_total['Closing Stock'][$old_cell_no] : 0);
                    $acc_pay = $final_all_total['Accounts Payable'][$n_tbdate_column_group['column']] - (isset($final_all_total['Accounts Payable'][$old_cell_no]) ? $final_all_total['Accounts Payable'][$old_cell_no] : 0);

                    $operating_cash_flow = $operating_cash_profit - $acc_rec - $closing_st + ($acc_pay);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '5')->setValue(convert_decimal_format($operating_cash_flow,0))->getStyle($n_tbdate_column_group['column'] . '5')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '6')->setValue(convert_decimal_format($operating_cash_profit,0))->getStyle($n_tbdate_column_group['column'] . '6')->applyFromArray($itemStyle);

                    $bankLoanCrrent = isset($final_all_total['Bank Loans - Current'][$old_cell_no]) ? $final_all_total['Bank Loans - Current'][$old_cell_no] : 0;
                    $bankLoanNonCrrent = isset($final_all_total['Bank Loans - Non Current'][$old_cell_no]) ? $final_all_total['Bank Loans - Non Current'][$old_cell_no] : 0;
                    $cashBank = isset($final_all_total['Cash & Bank'][$old_cell_no]) ? $final_all_total['Cash & Bank'][$old_cell_no] : 0;

                    $netcashflow = (($bankLoanCrrent + $bankLoanNonCrrent) - $cashBank) - (($final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']]) - $final_all_total['Cash & Bank'][$n_tbdate_column_group['column']]);

                    $netcashflow = round($netcashflow);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '7')->setValue(convert_decimal_format($netcashflow,0))->getStyle($n_tbdate_column_group['column'] . '7')->applyFromArray($itemStyle);

                    $total_debt = $final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']];
                    $total_debt = round($total_debt);

                    $n_cell_val5 = $total_debt - $final_all_total['Cash & Bank'][$n_tbdate_column_group['column']];
                    $n_cell_val5 = round($n_cell_val5);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '8')->setValue(convert_decimal_format($n_cell_val5,0))->getStyle($n_tbdate_column_group['column'] . '8')->applyFromArray($itemStyle);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '9')->setValue(convert_decimal_format($total_debt,0))->getStyle($n_tbdate_column_group['column'] . '9')->applyFromArray($itemStyle);

                    $debt_equity = 0;
                    if ($total_debt > 0) {
                        $debt_equity = round($total_debt / $final_all_total['Equity'][$n_tbdate_column_group['column']],2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '10')->setValue(convert_decimal_format($debt_equity))->getStyle($n_tbdate_column_group['column'] . '10')->applyFromArray($itemStyle);


                    $debt_capital = 0;
                    if (($final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']]) > 0) {
                        $debt_capital = round(($final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']]) / ($final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']] + $final_all_total['Equity'][$n_tbdate_column_group['column']]),2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '11')->setValue(convert_decimal_format($debt_capital))->getStyle($n_tbdate_column_group['column'] . '11')->applyFromArray($itemStyle);

                    $interest_cover = 0;
                    if ($final_all_total['Operating Profit'][$n_tbdate_column_group['column']] > 0) {
                        $interest_cover = round(($final_all_total['Interest & Bank Charges'][$n_tbdate_column_group['column']] / $final_all_total['Operating Profit'][$n_tbdate_column_group['column']]) * 100,2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '12')->setValue(convert_decimal_format($interest_cover))->getStyle($n_tbdate_column_group['column'] . '12')->applyFromArray($itemStyle);

                    $debt_payback = 0;
                    if ($n_cell_val5 > 0) {
                        $debt_payback = round($n_cell_val5 / $operating_cash_profit,2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '13')->setValue(convert_decimal_format($debt_payback))->getStyle($n_tbdate_column_group['column'] . '13')->applyFromArray($itemStyle);

                    $total_funding = $total_debt + ($final_all_total['Equity'][$n_tbdate_column_group['column']] - $final_all_total['Cash & Bank'][$n_tbdate_column_group['column']]);
                    $total_funding = round($total_funding);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '14')->setValue(convert_decimal_format($total_funding,0))->getStyle($n_tbdate_column_group['column'] . '14')->applyFromArray($itemStyle);

                    $fixed_assets = isset($final_all_total['Fixed Assets'][$n_tbdate_column_group['column']]) ? $final_all_total['Fixed Assets'][$n_tbdate_column_group['column']] : 0;
                    $Current_Liabilities = isset($final_all_total['Current Liabilities'][$n_tbdate_column_group['column']]) ? $final_all_total['Current Liabilities'][$n_tbdate_column_group['column']] : 0;
                    $revenue = isset($final_all_total['Sales'][$n_tbdate_column_group['column']]) ? $final_all_total['Sales'][$n_tbdate_column_group['column']] : 0;

                    $Operating_CF_Margin = ($revenue > 0) ? round( $operating_cash_flow / $revenue, 2 ) : 0;
                    $Cash_Flow_Coverage = ($Current_Liabilities > 0) ?  round($operating_cash_flow / $Current_Liabilities,2) : 0;
                    $Cash_Flow_Debt = ($total_debt > 0) ? round($operating_cash_flow / $total_debt,2) : 0;
                    $Capex_Coverage = ($fixed_assets > 0) ? round($operating_cash_flow / $fixed_assets,2) : 0;

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '15')->setValue(convert_decimal_format($Operating_CF_Margin))->getStyle($n_tbdate_column_group['column'] . '15')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '16')->setValue(convert_decimal_format($Cash_Flow_Coverage))->getStyle($n_tbdate_column_group['column'] . '16')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '17')->setValue(convert_decimal_format($Cash_Flow_Debt))->getStyle($n_tbdate_column_group['column'] . '17')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '18')->setValue(convert_decimal_format($Capex_Coverage))->getStyle($n_tbdate_column_group['column'] . '18')->applyFromArray($itemStyle);



                    $old_cell_no = $n_tbdate_column_group['column'];
                }
            }


            for ($ih = 4; $ih <= 18; $ih++) {
                $color = ($ih % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                $spreadsheet->getActiveSheet()
                    ->getStyle('A' . $ih)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB($color);

                if ($data_column_range) {
                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                        $spreadsheet->getActiveSheet()
                            ->getStyle($n_tbdate_column_group['column'] . $ih)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB($color);
                    }
                }
            }

            $sheet->getStyle('A3:' . $end_cell . '18')->applyFromArray($comman_all_border)->getNumberFormat()->setFormatCode('0.00');

             if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            }


            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'Financing Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/financing-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();


                return response()->json([
                    'message' => 'Financing Report Generated Successfully!',
                    'status' => 'success',
                    'table_html' => $html,
                ], 200);

            }



        } catch (\Exception $e) {
             if ($return_data == 1) {
                return [];
             }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }
    public function getImpactOfChangeReport(Request $request)
    {
        try {
            $report_type = $request->get('report_type');
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $profit_power_list = $this->getProfitPowerReport($request, 1);

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->orWhere(function ($query) {
                    $query->whereIn('ca.summery', [
                        10,
                        17,
                        19
                    ])->orwhereIn('ca.type', [
                                'accounts_receivable',
                                'closing_stock',
                                'accounts_payable',
                                'sales',
                                'opening_stock',
                                'direct_manufacturing_expenses',
                                'cost_of_goods_sold',
                                'employee_benefit_expenses',
                                'selling_general_and_administrative_expenses',
                                'other_expenses'
                            ]);
                })
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->impact_of_change_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/cashflow-quality-report/';

            $rp_po_path = 'cashflow-quality-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('CashFlow Quality Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {

                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];

                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);
                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);
                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }
                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                }
            }



            $p_index = 4;
            $old_cell_no = 'A';
            $total_overheads = $total_cogs = $total_acc_rec_days = $total_acc_pay_days = $total_inventory_days = $final_net_cashflow = $grossProfitPercent = $netCashFlow = $operatingProfit = $grossProfit = $netMargin = $totalRevenue = 0;
            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                    $bankLoanCrrent = isset($final_all_total['Bank Loans - Current'][$old_cell_no]) ? $final_all_total['Bank Loans - Current'][$old_cell_no] : 0;
                    $bankLoanNonCrrent = isset($final_all_total['Bank Loans - Non Current'][$old_cell_no]) ? $final_all_total['Bank Loans - Non Current'][$old_cell_no] : 0;
                    $cashBank = isset($final_all_total['Cash & Bank'][$old_cell_no]) ? $final_all_total['Cash & Bank'][$old_cell_no] : 0;
                    $netcashflow = (($bankLoanCrrent + $bankLoanNonCrrent) - $cashBank) - (($final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']]) - $final_all_total['Cash & Bank'][$n_tbdate_column_group['column']]);
                    $final_net_cashflow += $netcashflow;
                    $old_cell_no = $n_tbdate_column_group['column'];
                    $total_overheads += (float) str_replace(',', '', $final_all_total['Overheads'][$n_tbdate_column_group['column']]);
                    $totalRevenue += (float) str_replace(',', '', $profit_power_list[4][$n_tbdate_column_group['column']]);
                    $grossProfit += (float) str_replace(',', '', $profit_power_list[8][$n_tbdate_column_group['column']]);
                    $grossProfitPercent += (float) str_replace(',', '', $profit_power_list[9][$n_tbdate_column_group['column']]);
                    $operatingProfit += (float) str_replace(',', '', $profit_power_list[13][$n_tbdate_column_group['column']]);
                    $netMargin += (float) str_replace(',', '', $profit_power_list[17][$n_tbdate_column_group['column']]);

                    $total_cogs += (float) str_replace(',', '', $final_all_total['Total Direct Expenses'][$n_tbdate_column_group['column']]);

                    $n_cell_val1 = comman_cashmg_formula($final_all_total['Sales'][$n_tbdate_column_group['column']], $final_all_total['Accounts Receivable'][$n_tbdate_column_group['column']], 1, 1, 2);

                    $total_acc_rec_days += $n_cell_val1;
                    $n_cell_val2 = comman_cashmg_formula($final_all_total['Total Direct Expenses'][$n_tbdate_column_group['column']], $final_all_total['Closing Stock'][$n_tbdate_column_group['column']], 1, 1, 2);
                    $total_inventory_days += $n_cell_val2;
                    $n_cell_val3 = comman_cashmg_formula($final_all_total['Total Direct Expenses'][$n_tbdate_column_group['column']], $final_all_total['Accounts Payable'][$n_tbdate_column_group['column']], 1, 1, 2);
                    $total_acc_pay_days += $n_cell_val3;
                }
            }


            return response()->json([
                'message' => 'Impact Of Change Report  Generated Successfully!',
                'status' => 'success',
                'final_net_cashflow' => round($final_net_cashflow, 0),
                'totalRevenue' => round($totalRevenue, 0),
                'grossProfit' => round($grossProfit, 0),
                'grossProfitPercent' => round($grossProfitPercent, 0),
                'operatingProfit' => round($operatingProfit, 0),
                'netMargin' => round($netMargin, 0),
                'total_cogs' => round($total_cogs, 0),
                'total_acc_rec_days' => round($total_acc_rec_days, 0),
                'total_inventory_days' => round($total_inventory_days, 0),
                'total_acc_pay_days' => round($total_acc_pay_days, 0),
                'total_overheads' => round($total_overheads, 0),
                'month_count' => count($data_column_range),
                'table_html' => '',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getImpactOfChangeReportOld(Request $request)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $report_type = $request->get('report_type');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->financing_report_list;

            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A7' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 7;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),

                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/impact-of-change-report/';

            $rp_po_path = 'impact-of-change-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // if( $header_arr ){
            //     foreach( $header_arr as $header_index => $header_cell ){
            //         $spreadsheet->getActiveSheet()->getCell( $header_index )->setValue( $header_cell['label'] )->getStyle( $header_index )->applyFromArray($header_cell['style']);                
            //     }
            // }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Impact Of Change Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            // if( $column_width_list ){
            //     foreach( $column_width_list as $col_key => $column_width ){
            //         $spreadsheet->getActiveSheet()->getColumnDimension( $col_key )->setWidth( $column_width, 'px' );        
            //     }
            // }

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth('200', 'px');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth('200', 'px');
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth('200', 'px');
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth('200', 'px');


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {
                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }



                                    $cell_val = round($cell_val, 2);
                                    if ($sub_items['label'] == 'Current Year Profit') {
                                        $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',', '', $final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }

                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {

                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);

                                        if (!isset($final_items['hide_tr'])) {
                                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                        }

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }


                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                }

            }
            $all_operating_profit = (isset($final_all_total['Operating Profit']) && is_array($final_all_total['Operating Profit'])) ? array_sum($final_all_total['Operating Profit']) : 0;
            $monthlyRevenue = (isset($final_all_total['Sales']) && is_array($final_all_total['Sales'])) ? array_sum($final_all_total['Sales']) : 0;
            $monthlyCogs = (isset($final_all_total['Total Direct Expenses']) && is_array($final_all_total['Total Direct Expenses'])) ? array_sum($final_all_total['Total Direct Expenses']) : 0;
            $monthlyoverhead = (isset($final_all_total['Overheads']) && is_array($final_all_total['Overheads'])) ? array_sum($final_all_total['Overheads']) : 0;
            $current_receivables = (isset($final_all_total['Accounts Receivable']) && is_array($final_all_total['Accounts Receivable'])) ? end($final_all_total['Accounts Receivable']) : 0;
            $current_payable = (isset($final_all_total['Accounts Payable']) && is_array($final_all_total['Accounts Payable'])) ? end($final_all_total['Accounts Payable']) : 0;
            $current_inventory = (isset($final_all_total['Closing Stock']) && is_array($final_all_total['Closing Stock'])) ? end($final_all_total['Closing Stock']) : 0;

            $p_index = 7;

            $spreadsheet->getActiveSheet()->getCell('A4')->setValue('Your Current Position')->getStyle('A4')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('C3')->setValue('Net Cash Flow')->getStyle('C3')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('C4')->setValue(0);
            $spreadsheet->getActiveSheet()->getCell('D3')->setValue('Operating Profit')->getStyle('D3')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D4')->setValue(convert_decimal_format($all_operating_profit));

            $final_cash_flow = 0;
            $old_cell_no = 'A';
            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                    $bankLoanCrrent = isset($final_all_total['Bank Loans - Current'][$old_cell_no]) ? $final_all_total['Bank Loans - Current'][$old_cell_no] : 0;
                    $bankLoanNonCrrent = isset($final_all_total['Bank Loans - Non Current'][$old_cell_no]) ? $final_all_total['Bank Loans - Non Current'][$old_cell_no] : 0;
                    $cashBank = isset($final_all_total['Cash & Bank'][$old_cell_no]) ? $final_all_total['Cash & Bank'][$old_cell_no] : 0;
                    $netcashflow = (($bankLoanCrrent + $bankLoanNonCrrent) - $cashBank) - (($final_all_total['Bank Loans - Current'][$n_tbdate_column_group['column']] + $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group['column']]) - $final_all_total['Cash & Bank'][$n_tbdate_column_group['column']]);
                    $netcashflow = round($netcashflow, 2);
                    $final_cash_flow = $final_cash_flow + $netcashflow;
                }
            }

            $n = count($data_column_range);

            $spreadsheet->getActiveSheet()->getCell('C4')->setValue(convert_decimal_format($final_cash_flow));
            $spreadsheet->getActiveSheet()->mergeCells('A6:B6');
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Power of Change')->getStyle('A6')->applyFromArray(hg_comman_customer_header_style('bfbfbf'));
            $spreadsheet->getActiveSheet()->getCell('C6')->setValue('Impact on Cash Flow')->getStyle('C6')->applyFromArray(hg_comman_customer_header_style('bfbfbf'));
            $spreadsheet->getActiveSheet()->getCell('D6')->setValue('Impact on Operating Profit')->getStyle('D6')->applyFromArray(hg_comman_customer_header_style('bfbfbf'));

            $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Price Increase')->getStyle('A7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Volume Increase')->getStyle('A8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A9')->setValue('COGS Reduction')->getStyle('A9')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A10')->setValue('Overheads Reduction')->getStyle('A10')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A11')->setValue('Reduction in Receivables Days')->getStyle('A11')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A12')->setValue('Reduction in Inventory Days')->getStyle('A12')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A13')->setValue('Increase in payable days')->getStyle('A13')->applyFromArray($subitemStyle);

            $priceIncreasePercent = $request->get('price_increase');
            $VolumeIncreasePercent = $request->get('volume_increase');
            $CogsDeductPercent = $request->get('cogs_reduction');
            $overheadDeductPercent = $request->get('overheads_reduction');
            $day_reduction = $request->get('receivable_days');
            $day_inventory = $request->get('inventory_days');
            $day_paybale = $request->get('payable_days');


            $spreadsheet->getActiveSheet()->getCell('B7')->setValue(number_format($priceIncreasePercent, 2) . '%')->getStyle('B7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('B8')->setValue(number_format($VolumeIncreasePercent, 2) . '%')->getStyle('B8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('B9')->setValue(number_format($CogsDeductPercent, 2) . '%')->getStyle('B9')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('B10')->setValue(number_format($overheadDeductPercent, 2) . '%')->getStyle('B10')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('B11')->setValue(number_format($day_reduction, 2))->getStyle('B11')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('B12')->setValue(number_format($day_inventory, 2))->getStyle('B12')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('B13')->setValue(number_format($day_paybale, 2))->getStyle('B13')->applyFromArray($subitemStyle);

            $operatingProfitImpact = $monthlyRevenue * $n * ($priceIncreasePercent / 100);
            $operatingProfitImpact = round($operatingProfitImpact, 0);
            $working_capital_impact = 0;
            if ($current_receivables > 0) {
                $working_capital_impact = $operatingProfitImpact * ($current_receivables / ($monthlyRevenue * 12));
            }
            $working_capital_impact = round($working_capital_impact, 2);

            $cash_flow_impact = $operatingProfitImpact - $working_capital_impact;
            $cash_flow_impact = round($cash_flow_impact, 2);


            $spreadsheet->getActiveSheet()->getCell('C7')->setValue(convert_decimal_format($cash_flow_impact))->getStyle('C7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D7')->setValue(convert_decimal_format($operatingProfitImpact))->getStyle('D7')->applyFromArray($subitemStyle);

            $volumeoperatingProfitImpact = ($monthlyRevenue - $monthlyCogs) * $n * ($VolumeIncreasePercent / 100);
            $volumeoperatingProfitImpact = round($volumeoperatingProfitImpact, 2);

            $Receivables = $current_receivables * ($n / 12) * ($VolumeIncreasePercent / 100);
            $Receivables = round($Receivables, 2);
            $Inventory = $current_inventory * ($n / 12) * ($VolumeIncreasePercent / 100);
            $Inventory = round($Inventory, 2);
            $Payables = $current_payable * ($n / 12) * ($VolumeIncreasePercent / 100);
            $Payables = round($Payables, 2);

            $volume_working_capital_impact = ($Receivables + $Inventory) - $Payables;
            $volume_working_capital_impact = round($volume_working_capital_impact, 2);

            $volumecash_flow_impact = $volumeoperatingProfitImpact - $volume_working_capital_impact;
            $volumecash_flow_impact = round($volumecash_flow_impact, 2);

            $spreadsheet->getActiveSheet()->getCell('C8')->setValue(convert_decimal_format($volumecash_flow_impact))->getStyle('C8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D8')->setValue(convert_decimal_format($volumeoperatingProfitImpact))->getStyle('D8')->applyFromArray($subitemStyle);


            $cogsoperatingProfitImpact = $monthlyCogs * $n * ($CogsDeductPercent / 100);
            $cogsoperatingProfitImpact = round($cogsoperatingProfitImpact, 2);

            $cogsPayables = $current_payable * ($n / 12) * ($CogsDeductPercent / 100);
            $cogsPayables = round($cogsPayables, 2);
            $cogsInventory = $current_inventory * ($n / 12) * ($CogsDeductPercent / 100);
            $cogsInventory = round($cogsInventory, 2);

            $cogs_working_capital_impact = $cogsInventory - $cogsPayables;
            $cogs_working_capital_impact = round($cogs_working_capital_impact, 2);

            $cogscash_flow_impact = $cogsoperatingProfitImpact - $cogs_working_capital_impact;
            $cogscash_flow_impact = round($cogscash_flow_impact, 2);

            $spreadsheet->getActiveSheet()->getCell('C9')->setValue(convert_decimal_format($cogscash_flow_impact))->getStyle('C9')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D9')->setValue(convert_decimal_format($cogsoperatingProfitImpact))->getStyle('D9')->applyFromArray($subitemStyle);


            $overheadoperatingProfitImpact = $monthlyoverhead * $n * ($overheadDeductPercent / 100);
            $overheadoperatingProfitImpact = round($overheadoperatingProfitImpact, 2);

            $day_reduction = 15.00;
            $day_inventory = 15.00;
            $day_paybale = 15.00;

            $day_cashflow = (($monthlyRevenue * 12) / 365) * ($day_reduction / 100);
            $day_cashflow = round($day_cashflow, 2);

            $inventory_cashflow = (($monthlyCogs * 12) / 365) * ($day_inventory / 100);
            $inventory_cashflow = round($inventory_cashflow, 2);

            $payble_cashflow = (($monthlyCogs * 12) / 365) * ($day_paybale / 100);
            $payble_cashflow = round($payble_cashflow, 2);

            $spreadsheet->getActiveSheet()->getCell('C10')->setValue(convert_decimal_format($overheadoperatingProfitImpact))->getStyle('C10')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D10')->setValue(convert_decimal_format($overheadoperatingProfitImpact))->getStyle('D10')->applyFromArray($subitemStyle);

            $spreadsheet->getActiveSheet()->getCell('C11')->setValue(convert_decimal_format($day_cashflow))->getStyle('C11')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D11')->setValue(0.00)->getStyle('D11')->applyFromArray($subitemStyle);

            $spreadsheet->getActiveSheet()->getCell('C12')->setValue(convert_decimal_format($inventory_cashflow))->getStyle('C12')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D12')->setValue(0.00)->getStyle('D12')->applyFromArray($subitemStyle);

            $spreadsheet->getActiveSheet()->getCell('C13')->setValue(convert_decimal_format($payble_cashflow))->getStyle('C13')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('D13')->setValue(0.00)->getStyle('D13')->applyFromArray($subitemStyle);


            $spreadsheet->getActiveSheet()->getCell('A15')->setValue('Power of Change Impact')->getStyle('A15')->applyFromArray(['font' => $itemStyle['font']]);

            $power_casflow = $cash_flow_impact + $volumecash_flow_impact + $cogscash_flow_impact + $overheadoperatingProfitImpact + $day_cashflow + $inventory_cashflow + $payble_cashflow;
            $power_operatingflow = $operatingProfitImpact + $volumeoperatingProfitImpact + $cogsoperatingProfitImpact + $overheadoperatingProfitImpact;

            $spreadsheet->getActiveSheet()->getCell('C15')->setValue(convert_decimal_format($power_casflow))->getStyle('C15');
            $spreadsheet->getActiveSheet()->getCell('D15')->setValue(convert_decimal_format($power_operatingflow))->getStyle('D15');

            $spreadsheet->getActiveSheet()->getCell('A17')->setValue('Your adjusted position')->getStyle('A17')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('C17')->setValue('Net Cash Flow')->getStyle('C17')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D17')->setValue('Operating Profit')->getStyle('D17')->applyFromArray(['font' => $itemStyle['font']]);



            $adust = (float) $final_cash_flow + (float) $power_casflow;
            $adust2 = (float) $all_operating_profit + (float) $power_operatingflow;

            $spreadsheet->getActiveSheet()->getCell('C18')->setValue(convert_decimal_format($adust))->getStyle('C18');
            $spreadsheet->getActiveSheet()->getCell('D18')->setValue(convert_decimal_format($adust2))->getStyle('D18');



            for ($ih = 7; $ih <= 13; $ih++) {
                $color = ($ih % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                $spreadsheet->getActiveSheet()
                    ->getStyle('A' . $ih)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB($color);

                $spreadsheet->getActiveSheet()->getStyle('B' . $ih)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $spreadsheet->getActiveSheet()->getStyle('C' . $ih)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $spreadsheet->getActiveSheet()->getStyle('D' . $ih)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            }

            $sheet->getStyle('A6:D13')->applyFromArray($comman_all_border);



            $spreadsheet->getActiveSheet()->getCell('A20')->setValue('Profit')->getStyle('A20')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('B20')->setValue('Improvement')->getStyle('B20')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D20')->setValue('Cash Flow')->getStyle('D20')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A21')->setValue('Volume')->getStyle('A21')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D21')->setValue('Volume')->getStyle('D21')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A22')->setValue(convert_decimal_format($volumeoperatingProfitImpact))->getStyle('A22')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D22')->setValue(convert_decimal_format($volumecash_flow_impact))->getStyle('D22')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A23')->setValue('Price')->getStyle('A23')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D23')->setValue('Price')->getStyle('D23')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A24')->setValue(convert_decimal_format($operatingProfitImpact))->getStyle('A24')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D24')->setValue(convert_decimal_format($cash_flow_impact))->getStyle('D24')->applyFromArray(['font' => $itemStyle['font']]);
            $volum2 = $volum1 = 0;
            if ($operatingProfitImpact > 0) {
                $volum1 = round($operatingProfitImpact / $volumeoperatingProfitImpact, 2);
            }
            if ($cash_flow_impact > 0) {
                $volum2 = round($cash_flow_impact / $volumecash_flow_impact, 2);
            }
            $spreadsheet->getActiveSheet()->getCell('A25')->setValue(convert_decimal_format($volum1))->getStyle('A25')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);
            $spreadsheet->getActiveSheet()->getCell('D25')->setValue(convert_decimal_format($volum2))->getStyle('D25')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            $spreadsheet->getActiveSheet()->getCell('A26')->setValue('Price is ' . convert_decimal_format($volum1) . ' more sensitive than Volume')->getStyle('A26')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);
            $spreadsheet->getActiveSheet()->getCell('D26')->setValue('Price is ' . convert_decimal_format($volum2) . ' more sensitive than Volume')->getStyle('D26')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);


            $spreadsheet->getActiveSheet()->getCell('A30')->setValue('Profit')->getStyle('A30')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('B30')->setValue('Improvement')->getStyle('B30')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D30')->setValue('Cash Flow')->getStyle('D30')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A31')->setValue('COGS')->getStyle('A31')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D31')->setValue('COGS')->getStyle('D31')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A32')->setValue(convert_decimal_format($cogsoperatingProfitImpact))->getStyle('A32')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D32')->setValue(convert_decimal_format($cogscash_flow_impact))->getStyle('D22')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A33')->setValue('Overheads')->getStyle('A33')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D33')->setValue('Overheads')->getStyle('D33')->applyFromArray(['font' => $itemStyle['font']]);

            $spreadsheet->getActiveSheet()->getCell('A34')->setValue(convert_decimal_format($overheadoperatingProfitImpact))->getStyle('A34')->applyFromArray(['font' => $itemStyle['font']]);
            $spreadsheet->getActiveSheet()->getCell('D34')->setValue(convert_decimal_format($overheadoperatingProfitImpact))->getStyle('D34')->applyFromArray(['font' => $itemStyle['font']]);
            $volum4 = $volum3 = 0;
            if ($cogsoperatingProfitImpact > 0) {
                $volum3 = round($cogsoperatingProfitImpact / $overheadoperatingProfitImpact, 2);
            }
            if ($cogscash_flow_impact > 0) {
                $volum4 = round($cogscash_flow_impact / $overheadoperatingProfitImpact, 2);
            }

            $spreadsheet->getActiveSheet()->getCell('A35')->setValue(convert_decimal_format($volum3))->getStyle('A35')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);
            $spreadsheet->getActiveSheet()->getCell('D35')->setValue(convert_decimal_format($volum4))->getStyle('D35')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            $spreadsheet->getActiveSheet()->getCell('A36')->setValue('COGS is  ' . convert_decimal_format($volum3) . ' more sensitive than Overheads')->getStyle('A36')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);
            $spreadsheet->getActiveSheet()->getCell('D36')->setValue('COGS is ' . convert_decimal_format($volum4) . ' more sensitive than Overheads')->getStyle('D36')->applyFromArray([
                'font' => $itemStyle['font'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            for ($mi = 20; $mi <= 36; $mi++) {
                $spreadsheet->getActiveSheet()->mergeCells('B' . $mi . ':C' . $mi);
            }

            $sheet->getStyle('A20:A26')->applyFromArray($comman_all_border);
            $sheet->getStyle('D20:D26')->applyFromArray($comman_all_border);
            $sheet->getStyle('A30:A36')->applyFromArray($comman_all_border);
            $sheet->getStyle('D30:D36')->applyFromArray($comman_all_border);



            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'Impact Of Change Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/impact-of-change-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);
            } else {

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();


                return response()->json([
                    'message' => 'Impact Of Change Report Generated Successfully!',
                    'status' => 'success',
                    'table_html' => $html,
                ], 200);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }
    public function getCapexReport(Request $request, $return_data = 0)
    {


        try {

            $report_type = $request->get('report_type');
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->capex_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/capex-report/';

            $rp_po_path = 'capex-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Capex Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {
                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }
                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {
                                        $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',', '', $final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }

                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }
                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {


                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }


                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        if (!isset($final_items['hide_tr'])) {
                                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val, $decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                        }

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }
                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                }
            }


            $p_index = 4;
            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue('Other Capital')->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Other Capital %')->getStyle('A5')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Other Capital Turnover')->getStyle('A6')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Net Operating Assets')->getStyle('A7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Net Operating Assets %')->getStyle('A8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A9')->setValue('Asset Turnover')->getStyle('A9')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A10')->setValue('Return on Capital %')->getStyle('A10')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A11')->setValue('Return on Total Assets')->getStyle('A11')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A12')->setValue('Return on Equity %')->getStyle('A12')->applyFromArray($subitemStyle);

            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {

                    $n_cell_val1 = ($final_all_total['Fixed Assets'][$n_tbdate_column_group['column']] + $final_all_total['Other Non Current Assets'][$n_tbdate_column_group['column']] + $final_all_total['Other Current Assets'][$n_tbdate_column_group['column']]) - ($final_all_total['Other Current Liabilities'][$n_tbdate_column_group['column']] - $final_all_total['Other Non Current Liabilities'][$n_tbdate_column_group['column']]);
                    $n_cell_val1 = round($n_cell_val1);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '4')->setValue(convert_decimal_format($n_cell_val1,0))->getStyle($n_tbdate_column_group['column'] . '4')->applyFromArray($itemStyle);

                    $celvv2 = ($final_all_total['Sales'][$n_tbdate_column_group['column']] * count($data_column_range)) * (1 / count($data_column_range));

                    $n_cell_val2 = 0;
                    if ($celvv2 != 0) {
                        $n_cell_val2 = ($n_cell_val1 / $celvv2) / (count($data_column_range) / 100);
                        $n_cell_val2 = round($n_cell_val2, 2);
                    }
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '5')->setValue(convert_decimal_format($n_cell_val2))->getStyle($n_tbdate_column_group['column'] . '5')->applyFromArray($itemStyle);

                    $n_cell_val3 = 0;
                    if ($n_cell_val1 != 0) {
                        $n_cell_val3 = ($final_all_total['Sales'][$n_tbdate_column_group['column']] * count($data_column_range)) / $n_cell_val1;
                        $n_cell_val3 = round($n_cell_val3, 2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '6')->setValue(convert_decimal_format($n_cell_val3))->getStyle($n_tbdate_column_group['column'] . '6')->applyFromArray($itemStyle);

                    $n_cell_val4 = ($final_all_total['Accounts Receivable'][$n_tbdate_column_group['column']] + $final_all_total['Closing Stock'][$n_tbdate_column_group['column']]) - $final_all_total['Accounts Payable'][$n_tbdate_column_group['column']];
                    $n_cell_val4 = $n_cell_val4 + $n_cell_val1;
                    $n_cell_val4 = round($n_cell_val4);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '7')->setValue(convert_decimal_format($n_cell_val4,0))->getStyle($n_tbdate_column_group['column'] . '7')->applyFromArray($itemStyle);


                    $nncll1 = ($final_all_total['Sales'][$n_tbdate_column_group['column']] * count($data_column_range)) * (1 / count($data_column_range));
                    $n_cell_val5 = 0;
                    if ($nncll1 != 0) {
                        $n_cell_val5 = ($n_cell_val4 / $nncll1) / (count($data_column_range) / 100);
                        $n_cell_val5 = round($n_cell_val5,2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '8')->setValue(convert_decimal_format($n_cell_val5))->getStyle($n_tbdate_column_group['column'] . '8')->applyFromArray($itemStyle);

                    $n_cell_val6 = 0;
                    if ($n_cell_val4 != 0) {
                        $n_cell_val6 = ($final_all_total['Sales'][$n_tbdate_column_group['column']] * count($data_column_range) / 1) / $n_cell_val4;
                        $n_cell_val6 = round($n_cell_val6,2);
                    }




                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '9')->setValue(convert_decimal_format($n_cell_val6))->getStyle($n_tbdate_column_group['column'] . '9')->applyFromArray($itemStyle);
                    $n_cell_val7 = 0;
                    if (($n_cell_val4 / 100) != 0) {
                        $n_cell_val7 = ($final_all_total['Operating Profit'][$n_tbdate_column_group['column']] * count($data_column_range) / 1) / ($n_cell_val4 / 100);
                        $n_cell_val7 = round($n_cell_val7,2);
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '10')->setValue(convert_decimal_format($n_cell_val7))->getStyle($n_tbdate_column_group['column'] . '10')->applyFromArray($itemStyle);

                    $n_cell_val8 = 0;
                    if ($final_all_total['Total Assets'][$n_tbdate_column_group['column']] / 100 != 0) {
                        $n_cell_val8 = ($final_all_total['Operating Profit'][$n_tbdate_column_group['column']] * count($data_column_range) / 1) / ($final_all_total['Total Assets'][$n_tbdate_column_group['column']] / 100);
                        $n_cell_val8 = round($n_cell_val8,2);
                    }
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '11')->setValue(convert_decimal_format($n_cell_val8))->getStyle($n_tbdate_column_group['column'] . '11')->applyFromArray($itemStyle);

                    $n_cell_val9 = 0;
                    if ($final_all_total['Equity'][$n_tbdate_column_group['column']] / 100 != 0) {
                        //$n_cell_val9  = ( $final_all_total['Profit after Tax'][$n_tbdate_column_group['column']] * count($data_column_range) / 1 ) / ($final_all_total['Equity'][$n_tbdate_column_group['column']]  / 100);

                        $n_cell_val9 = ($final_all_total['Profit after Tax'][$n_tbdate_column_group['column']] / $final_all_total['Equity'][$n_tbdate_column_group['column']]) * 100 * (12 / 1);
                        $n_cell_val9 = round($n_cell_val9,2);
                    }
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '12')->setValue(convert_decimal_format($n_cell_val9))->getStyle($n_tbdate_column_group['column'] . '12')->applyFromArray($itemStyle);


                }
            }



            for ($ih = 4; $ih <= 12; $ih++) {
                $color = ($ih % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                $spreadsheet->getActiveSheet()
                    ->getStyle('A' . $ih)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB($color);

                if ($data_column_range) {
                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                        $spreadsheet->getActiveSheet()
                            ->getStyle($n_tbdate_column_group['column'] . $ih)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB($color);
                    }
                }
            }


            $sheet->getStyle('A3:' . $end_cell . '12')->applyFromArray($comman_all_border)->getNumberFormat()->setFormatCode('0.00');
if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            }

            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'Capex Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/capex-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();


                return response()->json([
                    'message' => 'Capex Report Generated Successfully!',
                    'status' => 'success',
                    'table_html' => $html,
                ], 200);

            }



        } catch (\Exception $e) {
            if ($return_data == 1) {                
                return [];
            }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }


    public function getCashFlowQualityReport(Request $request)
    {


        try {

            $report_type = $request->get('report_type');
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $profit_power_list = $this->getProfitPowerReport($request, 1);

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->whereIn('ca.type', [
                    'accounts_receivable',
                    'closing_stock',
                    'accounts_payable',
                ])
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->cashflow_quality_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/cashflow-quality-report/';

            $rp_po_path = 'cashflow-quality-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('CashFlow Quality Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {

                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }


                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];

                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, $decimal_1);
                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, $decimal_2);
                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {
                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }
                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];
                                if (!isset($final_items['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                                if ($data_column_range) {
                                    foreach ($data_column_range as $ns_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, $decimal_3);

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($ns_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($ns_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }
                                if (!isset($final_items['hide_tr'])) {
                                    $p_index++;
                                }
                            }

                        }
                    }
                }
            }

            $p_index = 4;
            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue('Gross Margin')->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Overheads')->getStyle('A5')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Receivables')->getStyle('A6')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Inventory')->getStyle('A7')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Payables')->getStyle('A8')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A9')->setValue('IMPACT OF MANAGEMENT DECISION')->getStyle('A9')->applyFromArray($subitemStyle);
            $next_index = 'A';

            $revenue_arr = isset($profit_power_list[4]) ? $profit_power_list[4] : [];
            $cogs_arr = isset($profit_power_list[6]) ? $profit_power_list[6] : [];
            $gross_mrg_percentage_arr = isset($profit_power_list[9]) ? $profit_power_list[9] : [];
            $overheads_arr = isset($profit_power_list[10]) ? $profit_power_list[10] : [];

            $accounts_receivable_arr = isset($final_all_total['Accounts Receivable']) ? $final_all_total['Accounts Receivable'] : [];
            $closing_stock_arr = isset($final_all_total['Closing Stock']) ? $final_all_total['Closing Stock'] : [];
            $accounts_payable_arr = isset($final_all_total['Accounts Payable']) ? $final_all_total['Accounts Payable'] : [];

            $negativeStyle = $itemStyle;
            $negativeStyle['font']['color']['argb'] = 'FF0000';

            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {

                    $current_revenue = isset($revenue_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $revenue_arr[$n_tbdate_column_group['column']]) : 0;
                    $current_gsMrg = isset($gross_mrg_percentage_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $gross_mrg_percentage_arr[$n_tbdate_column_group['column']]) : 0;
                    $current_overhead = isset($overheads_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $overheads_arr[$n_tbdate_column_group['column']]) : 0;
                    $current_accrec = isset($accounts_receivable_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $accounts_receivable_arr[$n_tbdate_column_group['column']]) : 0;
                    $current_closest = isset($closing_stock_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $closing_stock_arr[$n_tbdate_column_group['column']]) : 0;
                    $current_accpay = isset($accounts_payable_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $accounts_payable_arr[$n_tbdate_column_group['column']]) : 0;
                    $current_cogs = isset($cogs_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $cogs_arr[$n_tbdate_column_group['column']]) : 0;

                    if ($next_index == 'A') {
                        $prev_gsMrg = $prev_revenue = $prev_accrec = $prev_closest = $prev_accpay = $prev_overhead = $prev_cogs = 0;
                    } else {
                        $prev_gsMrg = isset($gross_mrg_percentage_arr[$next_index]) ? str_replace(',', '', $gross_mrg_percentage_arr[$next_index]) : 0;
                        $prev_revenue = isset($revenue_arr[$next_index]) ? str_replace(',', '', $revenue_arr[$next_index]) : 0;
                        $prev_overhead = isset($overheads_arr[$next_index]) ? str_replace(',', '', $overheads_arr[$next_index]) : 0;
                        $prev_accrec = isset($accounts_receivable_arr[$next_index]) ? str_replace(',', '', $accounts_receivable_arr[$next_index]) : 0;
                        $prev_closest = isset($closing_stock_arr[$next_index]) ? str_replace(',', '', $closing_stock_arr[$next_index]) : 0;
                        $prev_accpay = isset($accounts_payable_arr[$next_index]) ? str_replace(',', '', $accounts_payable_arr[$next_index]) : 0;
                        $prev_cogs = isset($cogs_arr[$next_index]) ? str_replace(',', '', $cogs_arr[$next_index]) : 0;
                    }



                    // $gross_margin_total = ( ( (float)$current_revenue * (float)$current_gsMrg ) - ( (float)$current_revenue * (float)$prev_gsMrg ) ) / 1;
                    $gross_margin_total = (((float) $current_revenue * (float) $current_gsMrg) - ((float) $current_revenue * (float) $prev_gsMrg)) * (1 / 12);
                    $gross_margin_total = round($gross_margin_total, 2);

                    $overhead_total = ((float) $current_overhead - ((float) $prev_overhead * (((float) $prev_revenue > 0) ? (float) $current_revenue / (float) $prev_revenue : 0))) * 1 / 12;
                    $overhead_total = round($overhead_total, 2);
                    $receivables_total = ((float) $current_accrec - ((float) $prev_accrec * (((float) $prev_revenue > 0) ? (float) $current_revenue / (float) $prev_revenue : 0))) * 1 / 12;

                    $receivables_total = round($receivables_total, 2);

                    $inventory_total = ($current_closest - ($prev_closest * (((float) $prev_revenue > 0) ? (float) $current_cogs / (float) $prev_cogs : 0))) * 1 / 12;
                    $inventory_total = round($inventory_total, 2);
                    $payable_total = ($current_accpay - ($prev_accpay * (((float) $prev_revenue > 0) ? (float) $current_cogs / (float) $prev_cogs : 0))) * 1 / 12;
                    $payable_total = round($payable_total, 2);
                    $decision_total = $gross_margin_total + $overhead_total + $receivables_total + $inventory_total + $payable_total;
                    $decision_total = round($decision_total, 2);

                    if ($next_index == 'A') {
                        $gross_margin_total = $overhead_total = $receivables_total = $inventory_total = $payable_total = $decision_total = 0;
                    }

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '4')->setValue(convert_decimal_format($gross_margin_total,0))->getStyle($n_tbdate_column_group['column'] . '4')->applyFromArray(($gross_margin_total < 0) ? $negativeStyle : $itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '5')->setValue(convert_decimal_format($overhead_total,0))->getStyle($n_tbdate_column_group['column'] . '5')->applyFromArray(($overhead_total < 0) ? $negativeStyle : $itemStyle);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '6')->setValue(convert_decimal_format($receivables_total,0))->getStyle($n_tbdate_column_group['column'] . '6')->applyFromArray(($receivables_total < 0) ? $negativeStyle : $itemStyle);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '7')->setValue(convert_decimal_format($inventory_total,0))->getStyle($n_tbdate_column_group['column'] . '7')->applyFromArray(($inventory_total < 0) ? $negativeStyle : $itemStyle);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '8')->setValue(convert_decimal_format($payable_total,0))->getStyle($n_tbdate_column_group['column'] . '8')->applyFromArray(($payable_total < 0) ? $negativeStyle : $itemStyle);

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '9')->setValue(convert_decimal_format($decision_total,0))->getStyle($n_tbdate_column_group['column'] . '9')->applyFromArray(($decision_total < 0) ? $negativeStyle : $itemStyle);

                    $next_index = $n_tbdate_column_group['column'];
                }
            }



            for ($ih = 4; $ih <= 9; $ih++) {
                $color = ($ih % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                $spreadsheet->getActiveSheet()
                    ->getStyle('A' . $ih)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB($color);

                if ($data_column_range) {
                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                        $spreadsheet->getActiveSheet()
                            ->getStyle($n_tbdate_column_group['column'] . $ih)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB($color);
                    }
                }
            }


            $sheet->getStyle('A3:' . $end_cell . '9')->applyFromArray($comman_all_border);

            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'CashFlow Quality Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/cashflow-quality-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                //ob_start();
                //  $writer = new html($spreadsheet);
                //  $writer->save('php://output');
                // $html = ob_get_clean();

                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);

                return response()->json([
                    'message' => 'CashFlow Quality Report Generated Successfully!',
                    'status' => 'success',
                    //'table_html' =>  $html,
                    'cellArray' => $cellArray
                ], 200);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getBSCategoryReport(Request $request)
    {


        try {

            $report_type = $request->get('report_type');
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
           
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;
            $industry_id = get_user_meta( $user->id, 'industry_id', true );

            if ( empty($industry_id) || $industry_id == 0 || $industry_id == null ) {
                  $industry_id = 1;
            }

            $fns_list = $this->getFNSReport($request, 1);

            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];


            $start_row = 3;
            $start_col = 'B';

            $end_cell = str_replace($start_row, '', $start_col);


            $local_path = $path = public_path() . '/reports/cashflow-quality-report/';

            $rp_po_path = 'cashflow-quality-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $description_cols = isset($fns_list[3]) ? $fns_list[3] : '';

            if ($description_cols) {
                foreach ($description_cols as $header_index => $s_value) {
                    if ($s_value == null) {
                        break;
                    }
                    $column_width_list[$header_index] = 125;
                    $start_col = $header_index;
                    $spreadsheet->getActiveSheet()->getCell($header_index . '3')->setValue($s_value)->getStyle($header_index . '3')->applyFromArray(hg_comman_customer_header_style('bfbfbf'));
                    if ($header_index !== 'A') {
                        $data_column_range[] = $header_index;
                    }
                }
            }
            $final_all_total = [];
            foreach ($fns_list as $key => $s_value) {
                $first_col = $s_value['A'];
                unset($s_value['A']);
                $final_all_total[$first_col] = $s_value;
            }
            $end_cell = str_replace($start_row, '', $start_col);

            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('BS Category Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];

            $single_industry_list = DB::table('bs_category_ratio')->where('industry_id', $industry_id)->get();

            $single_industry_arr = [];
            if ($single_industry_list->count() > 0) {
                foreach ($single_industry_list as $s_key => $single_industry_item) {
                    $single_industry_arr[$single_industry_item->ratio_name] = $single_industry_item;
                }
            }

            $item_list = ['Current Ratio', 'Quick Ratio', 'Debt-to-Equity', 'Asset Turnover', 'ROE', 'ROA', 'Gross Margin', 'Net Margin', 'Interest Coverage', 'Receivables Days', 'Payable Days', 'Working Capital Days', 'Operating CF Margin', 'CF Coverage', 'CF to Debt', 'Capex Coverage', 'Total Score'];

            $p_index = 4;
            foreach ($item_list as $key => $p_value) {
                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($p_value)->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                $p_index++;
            }

            $next_index = 'A';

            // $revenue_arr = isset($profit_power_list[4]) ? $profit_power_list[4] : [];
            // $cogs_arr = isset($profit_power_list[6]) ? $profit_power_list[6] : [];
            // $gross_mrg_percentage_arr = isset($profit_power_list[9]) ? $profit_power_list[9] : [];
            // $overheads_arr = isset($profit_power_list[10]) ? $profit_power_list[10] : [];

            // $accounts_receivable_arr = isset($final_all_total['Accounts Receivable']) ? $final_all_total['Accounts Receivable'] : [];
            // $closing_stock_arr = isset($final_all_total['Closing Stock']) ? $final_all_total['Closing Stock'] : [];
            // $accounts_payable_arr = isset($final_all_total['Accounts Payable']) ? $final_all_total['Accounts Payable'] : [];

            // $negativeStyle = $itemStyle;
            // $negativeStyle['font']['color']['argb'] = 'FF0000';
            if ($data_column_range) {
                foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {


                    $current_ratio = 0;
                    $Current_Liabilities = isset($final_all_total['Current Liabilities'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Current Liabilities'][$n_tbdate_column_group]) : 0;
                    $Current_Assets = isset($final_all_total['Current Assets'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Current Assets'][$n_tbdate_column_group]) : 0;
                    $closing_stocks = isset($final_all_total['Closing Stock'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Closing Stock'][$n_tbdate_column_group]) : 0;
                    $bank_loan_current = isset($final_all_total['Bank Loans - Current'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Bank Loans - Current'][$n_tbdate_column_group]) : 0;
                    $bank_loan_non_current = isset($final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Bank Loans - Non Current'][$n_tbdate_column_group]) : 0;
                    $Equity = isset($final_all_total['Equity'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Equity'][$n_tbdate_column_group]) : 0;
                    $acc_rec = isset($final_all_total['Accounts Receivable'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Accounts Receivable'][$n_tbdate_column_group]) : 0;
                    $acc_pay = isset($final_all_total['Accounts Payable'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Accounts Payable'][$n_tbdate_column_group]) : 0;
                    $fixed_assets = isset($final_all_total['Fixed Assets'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Fixed Assets'][$n_tbdate_column_group]) : 0;
                    $other_non_current_assets = isset($final_all_total['Other Non Current Assets'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Other Non Current Assets'][$n_tbdate_column_group]) : 0;
                    $other_current_assets = isset($final_all_total['Other Current Assets'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Other Current Assets'][$n_tbdate_column_group]) : 0;
                    $other_current_liability = isset($final_all_total['Other Current Liabilities'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Other Current Liabilities'][$n_tbdate_column_group]) : 0;
                    $other_none_current_liability = isset($final_all_total['Other Non Current Liabilities'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Other Non Current Liabilities'][$n_tbdate_column_group]) : 0;
                    $revenue = isset($final_all_total['Sales'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Sales'][$n_tbdate_column_group]) : 0;
                    $cogs = isset($final_all_total['COGS'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['COGS'][$n_tbdate_column_group]) : 0;
                    $profit_after_tax = isset($final_all_total['Profit after Tax'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Profit after Tax'][$n_tbdate_column_group]) : 0;
                    $operating_profit = isset($final_all_total['Operating Profit'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Operating Profit'][$n_tbdate_column_group]) : 0;
                    $total_assets = isset($final_all_total['Total Assets'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Total Assets'][$n_tbdate_column_group]) : 0;
                    $gross_margin = isset($final_all_total['Gross Profit'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Gross Profit'][$n_tbdate_column_group]) : 0;
                    $interest_charge = isset($final_all_total['Interest & Bank Charges'][$n_tbdate_column_group]) ? str_replace(',', '', $final_all_total['Interest & Bank Charges'][$n_tbdate_column_group]) : 0;
                    $old_acc_rec = isset($final_all_total['Accounts Receivable'][$next_index]) ? str_replace(',', '', $final_all_total['Accounts Receivable'][$next_index]) : 0;
                    $old_closing_st = isset($final_all_total['Closing Stock'][$next_index]) ? str_replace(',', '', $final_all_total['Closing Stock'][$next_index]) : 0;
                    $old_acc_pay = isset($final_all_total['Accounts Payable'][$next_index]) ? str_replace(',', '', $final_all_total['Accounts Payable'][$next_index]) : 0;
                    $Depreciation = isset($final_all_total['Depreciation & Amortization'][$next_index]) ? str_replace(',', '', $final_all_total['Depreciation & Amortization'][$next_index]) : 0;
                    if ($Current_Liabilities != 0) {
                        $current_ratio = round((float) $Current_Assets / (float) $Current_Liabilities, 2);
                    }


                    $quick_ratio = 0;
                    if ($Current_Liabilities != 0) {
                        $quick_ratio = ((float) $Current_Assets - (float) $closing_stocks) / (float) $Current_Liabilities;
                    }



                    $total_debt = $bank_loan_current + $bank_loan_non_current;
                    $total_debt = round($total_debt, 2);
                    $debt_equity = 0;
                    if ($total_debt > 0) {
                        $debt_equity = round($total_debt / $Equity, 2);
                    }


                    $n_cell_val1 = ($fixed_assets + $other_non_current_assets + $other_current_assets) - ($other_current_liability - $other_none_current_liability);
                    $n_cell_val1 = round($n_cell_val1, 2);

                    $n_cell_val4 = ($acc_rec + $closing_stocks) - $acc_pay;
                    $n_cell_val4 = $n_cell_val4 + $n_cell_val1;
                    $n_cell_val4 = round($n_cell_val4, 2);
                    $asset_tunover = 0;
                    if ($n_cell_val4 != 0) {
                        $asset_tunover = ($revenue * count($data_column_range) / 1) / $n_cell_val4;
                        $asset_tunover = round($asset_tunover, 2);
                    }


                    $roe = 0;
                    if ((float) $Equity / 100 != 0) {
                        $roe = ((float) $profit_after_tax * count($data_column_range) / 1) / ((float) $Equity / 100);
                        $roe = round($roe, 2);
                    }

                    $rot = 0;
                    if ((float) $total_assets / 100 != 0) {
                        $rot = ($operating_profit * count($data_column_range) / 1) / ((float) $total_assets / 100);
                        $rot = round($rot, 2);
                    }

                    $gross_margin_per = comman_module_formula($revenue, $gross_margin, 2);
                    $net_margin_per = comman_module_formula($revenue, $profit_after_tax, 2);

                    $Interest_Cover = 0;
                    if ((float) $operating_profit > 0) {
                        $Interest_Cover = round(((float) $interest_charge / (float) $operating_profit) * 100, 2);
                    }

                    $rec_days = comman_cashmg_formula($revenue, $acc_rec, 1, count($data_column_range), 2);
                    $invt_days = comman_cashmg_formula($cogs, $closing_stocks, 1, count($data_column_range), 2);
                    $pay_days = comman_cashmg_formula($cogs, $acc_pay, 1, count($data_column_range), 2);
                    $work_days = ($rec_days + $invt_days) - $pay_days;

                    $operating_cash_profit = $operating_profit + $Depreciation;
                    $operating_cash_profit = round($operating_cash_profit, 2);

                    $cash_acc_rec = $acc_rec - $old_acc_rec;
                    $cash_closing_st = $closing_stocks - $old_closing_st;
                    $cash_acc_pay = $acc_pay - $old_acc_pay;

                    $operating_cash_flow = $operating_cash_profit - $cash_acc_rec - $cash_closing_st + ($cash_acc_pay);

                    $Operating_CF_Margin = ($revenue > 0) ? round($operating_cash_flow / $revenue, 2) : 0;
                    $Cash_Flow_Coverage = ($Current_Liabilities > 0) ? round($operating_cash_flow / $Current_Liabilities, 2) : 0;
                    $Cash_Flow_Debt = ($total_debt > 0) ? round($operating_cash_flow / $total_debt, 2) : 0;
                    $Capex_Coverage = ($fixed_assets > 0) ? round($operating_cash_flow / $fixed_assets, 2) : 0;


                    $current_ratio_res = give_bs_category_weight('Current Ratio', $current_ratio, $single_industry_arr);
                    $quick_ratio_res = give_bs_category_weight('Quick Ratio', $quick_ratio, $single_industry_arr);
                    $debt_equity_res = give_bs_category_weight('Debt-to-Equity', $debt_equity, $single_industry_arr, 1);
                    $asset_tunover_res = give_bs_category_weight('Asset Turnover', $asset_tunover, $single_industry_arr);
                    $roe_res = give_bs_category_weight('ROE', $roe, $single_industry_arr);
                    $rot_res = give_bs_category_weight('ROA', $rot, $single_industry_arr);
                    $gross_margin_per_res = give_bs_category_weight('Gross Margin', $gross_margin_per, $single_industry_arr);
                    $net_margin_per_res = give_bs_category_weight('Net Margin', $net_margin_per, $single_industry_arr);
                    $Interest_Cover_res = give_bs_category_weight('Interest Coverage', $Interest_Cover, $single_industry_arr);
                    $rec_days_res = give_bs_category_weight('Receivables Days', $rec_days, $single_industry_arr, 1);
                    $pay_days_res = give_bs_category_weight('Payable Days', $pay_days, $single_industry_arr);
                    $work_days_res = give_bs_category_weight('Working Capital Days', $work_days, $single_industry_arr, 1);
                    $Operating_CF_Margin_res = give_bs_category_weight('Operating CF Margin', $Operating_CF_Margin, $single_industry_arr);
                    $Cash_Flow_Coverage_res = give_bs_category_weight('CF Coverage', $Cash_Flow_Coverage, $single_industry_arr);
                    $Cash_Flow_Debt_res = give_bs_category_weight('CF to Debt', $Cash_Flow_Debt, $single_industry_arr);
                    $Capex_Coverage_res = give_bs_category_weight('Capex Coverage', $Capex_Coverage, $single_industry_arr);

                    $current_ratio_score = $current_ratio_res['score'];
                    $quick_ratio_score = $quick_ratio_res['score'];
                    $debt_equity_score = $debt_equity_res['score'];
                    $asset_tunover_score = $asset_tunover_res['score'];
                    $roe_score = $roe_res['score'];
                    $rot_score = $rot_res['score'];
                    $gross_margin_per_score = $gross_margin_per_res['score'];
                    $net_margin_per_score = $net_margin_per_res['score'];
                    $Interest_Cover_score = $Interest_Cover_res['score'];
                    $rec_days_score = $rec_days_res['score'];
                    $pay_days_score = $pay_days_res['score'];
                    $work_days_score = $work_days_res['score'];
                    $Operating_CF_Margin_score = $Operating_CF_Margin_res['score'];
                    $Cash_Flow_Coverage_score = $Cash_Flow_Coverage_res['score'];
                    $Cash_Flow_Debt_score = $Cash_Flow_Debt_res['score'];
                    $Capex_Coverage_score = $Capex_Coverage_res['score'];

                    $final_score = $current_ratio_score + $quick_ratio_score + $debt_equity_score + $asset_tunover_score + $roe_score + $rot_score + $gross_margin_per_score + $net_margin_per_score + $Interest_Cover_score + $rec_days_score + $pay_days_score + $work_days_score + $Operating_CF_Margin_score + $Cash_Flow_Coverage_score + $Cash_Flow_Debt_score + $Capex_Coverage_score;

                    //0-50 shaky
                    //51-75 steady
                    //76+ strong

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '4')->setValue($current_ratio_res['result'])->getStyle($n_tbdate_column_group . '4')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '5')->setValue($quick_ratio_res['result'])->getStyle($n_tbdate_column_group . '5')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '6')->setValue($debt_equity_res['result'])->getStyle($n_tbdate_column_group . '6')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '7')->setValue($asset_tunover_res['result'])->getStyle($n_tbdate_column_group . '7')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '8')->setValue($roe_res['result'])->getStyle($n_tbdate_column_group . '8')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '9')->setValue($rot_res['result'])->getStyle($n_tbdate_column_group . '9')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '10')->setValue($gross_margin_per_res['result'])->getStyle($n_tbdate_column_group . '10')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '11')->setValue($net_margin_per_res['result'])->getStyle($n_tbdate_column_group . '11')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '12')->setValue($Interest_Cover_res['result'])->getStyle($n_tbdate_column_group . '12')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '13')->setValue($rec_days_res['result'])->getStyle($n_tbdate_column_group . '13')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '14')->setValue($pay_days_res['result'])->getStyle($n_tbdate_column_group . '14')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '15')->setValue($work_days_res['result'])->getStyle($n_tbdate_column_group . '15')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '16')->setValue($Operating_CF_Margin_res['result'], )->getStyle($n_tbdate_column_group . '16')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '17')->setValue($Cash_Flow_Coverage_res['result'])->getStyle($n_tbdate_column_group . '17')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '18')->setValue($Cash_Flow_Debt_res['result'])->getStyle($n_tbdate_column_group . '18')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '19')->setValue($Capex_Coverage_res['result'])->getStyle($n_tbdate_column_group . '19')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '20')->setValue($final_score)->getStyle($n_tbdate_column_group . '20')->applyFromArray($itemStyle);

                    // $current_revenue = isset($revenue_arr[$n_tbdate_column_group['column']]) ? str_replace(',', '', $revenue_arr[$n_tbdate_column_group['column']]) : 0;
                    // $current_gsMrg = isset($gross_mrg_percentage_arr[$n_tbdate_column_group['column']]) ?  str_replace(',', '', $gross_mrg_percentage_arr[$n_tbdate_column_group['column']] ) : 0;
                    // $current_overhead = isset($overheads_arr[$n_tbdate_column_group['column']]) ?  str_replace(',', '', $overheads_arr[$n_tbdate_column_group['column']] ) : 0;
                    // $current_accrec = isset($accounts_receivable_arr[$n_tbdate_column_group['column']]) ?  str_replace(',', '', $accounts_receivable_arr[$n_tbdate_column_group['column']] ) : 0;
                    // $current_closest = isset($closing_stock_arr[$n_tbdate_column_group['column']]) ?  str_replace(',', '', $closing_stock_arr[$n_tbdate_column_group['column']] ) : 0;
                    // $current_accpay = isset($accounts_payable_arr[$n_tbdate_column_group['column']]) ?  str_replace(',', '', $accounts_payable_arr[$n_tbdate_column_group['column']] ) : 0;
                    // $current_cogs = isset($cogs_arr[$n_tbdate_column_group['column']]) ?  str_replace(',', '', $cogs_arr[$n_tbdate_column_group['column']] ) : 0;

                    // if( $next_index == 'A' ){
                    //     $prev_gsMrg = $prev_revenue = $prev_accrec = $prev_closest = $prev_accpay = $prev_overhead = $prev_cogs = 0;
                    // }else{
                    //     $prev_gsMrg = isset($gross_mrg_percentage_arr[$next_index]) ? str_replace(',', '', $gross_mrg_percentage_arr[$next_index] ) : 0;
                    //     $prev_revenue = isset($revenue_arr[$next_index]) ? str_replace(',', '', $revenue_arr[$next_index] ) : 0;
                    //     $prev_overhead = isset($overheads_arr[$next_index]) ? str_replace(',', '', $overheads_arr[$next_index] ) : 0;
                    //     $prev_accrec = isset($accounts_receivable_arr[$next_index]) ? str_replace(',', '', $accounts_receivable_arr[$next_index] ) : 0;
                    //     $prev_closest = isset($closing_stock_arr[$next_index]) ? str_replace(',', '', $closing_stock_arr[$next_index] ) : 0;
                    //     $prev_accpay = isset($accounts_payable_arr[$next_index]) ? str_replace(',', '', $accounts_payable_arr[$next_index] ) : 0;
                    //     $prev_cogs = isset($cogs_arr[$next_index]) ? str_replace(',', '', $cogs_arr[$next_index] ) : 0;
                    // }



                    // $gross_margin_total = ( ( (float)$current_revenue * (float)$current_gsMrg ) - ( (float)$current_revenue * (float)$prev_gsMrg ) ) / count( $data_column_range );
                    // $gross_margin_total = round( $gross_margin_total, 2 );
                    // $overhead_total = ( (float)$current_overhead - ( (float)$prev_overhead * ( ( (float)$prev_revenue > 0) ? (float)$current_revenue / (float)$prev_revenue : 0 ) ) ) / count($data_column_range);
                    // $overhead_total = round( $overhead_total, 2 );
                    // $receivables_total  = ( (float)$current_accrec - ( (float)$prev_accrec * ( ( (float)$prev_revenue > 0) ? (float)$current_revenue / (float)$prev_revenue : 0 ) ) )/  count($data_column_range);

                    // $receivables_total = round( $receivables_total, 2 );

                    // $inventory_total = ( $current_closest - ( $prev_closest * ( ( (float)$prev_revenue > 0) ? (float)$current_cogs / (float)$prev_cogs : 0 ) ) ) / count($data_column_range); 
                    // $inventory_total = round($inventory_total,2);
                    // $payable_total = ( $current_accpay - ( $prev_accpay * ( ( (float)$prev_revenue > 0) ? (float)$current_cogs / (float)$prev_cogs : 0 ) ) ) / count($data_column_range); 
                    // $payable_total = round($payable_total,2);
                    // $decision_total = $gross_margin_total + $overhead_total + $receivables_total + $inventory_total + $payable_total;
                    // $decision_total = round($decision_total,2);

                    // if( $next_index == 'A' ){
                    //     $gross_margin_total = $overhead_total = $receivables_total  = $inventory_total = $payable_total =  $decision_total = 0;
                    // }

                    // $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].'4' )->setValue( convert_decimal_format($gross_margin_total) )->getStyle( $n_tbdate_column_group['column'].'4' )->applyFromArray(($gross_margin_total < 0) ? $negativeStyle : $itemStyle);
                    // $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].'5' )->setValue( convert_decimal_format($overhead_total) )->getStyle( $n_tbdate_column_group['column'].'5' )->applyFromArray(($overhead_total < 0) ? $negativeStyle : $itemStyle);

                    // $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].'6' )->setValue( convert_decimal_format($receivables_total) )->getStyle( $n_tbdate_column_group['column'].'6' )->applyFromArray(($receivables_total < 0) ? $negativeStyle : $itemStyle);

                    // $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].'7' )->setValue( convert_decimal_format($inventory_total) )->getStyle( $n_tbdate_column_group['column'].'7' )->applyFromArray(($inventory_total < 0) ? $negativeStyle : $itemStyle);

                    // $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].'8' )->setValue( convert_decimal_format($payable_total) )->getStyle( $n_tbdate_column_group['column'].'8' )->applyFromArray(($payable_total < 0) ? $negativeStyle : $itemStyle);

                    // $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].'9' )->setValue( convert_decimal_format($decision_total) )->getStyle( $n_tbdate_column_group['column'].'9' )->applyFromArray(($decision_total < 0) ? $negativeStyle : $itemStyle);                    

                    $next_index = $n_tbdate_column_group;
                }
            }



            // for ( $ih = 4; $ih <= 9; $ih++ ) { 
            //     $color = ($ih % 2 == 0) ? 'c7e4db' : 'e3f1ed';
            //     $spreadsheet->getActiveSheet()
            //     ->getStyle('A'.$ih)
            //     ->getFill()
            //     ->setFillType(Fill::FILL_SOLID)
            //     ->getStartColor()
            //     ->setARGB($color);

            //     if( $data_column_range ){
            //         foreach( $data_column_range as $n_tbdate_index => $n_tbdate_column_group ){ 
            //             $spreadsheet->getActiveSheet()
            //             ->getStyle($n_tbdate_column_group['column'].$ih)
            //             ->getFill()
            //             ->setFillType(Fill::FILL_SOLID)
            //             ->getStartColor()
            //             ->setARGB($color);
            //         }
            //     }
            // }


            $sheet->getStyle('A3:' . $end_cell . '20')->applyFromArray($comman_all_border);

            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'BS Category Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/cashflow-quality-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                // ob_start();
                //$writer = new Html($spreadsheet);
                // $writer->save('php://output');
                // $html = ob_get_clean();

                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);


                return response()->json([
                    'message' => 'BS Category Report Generated Successfully!',
                    'status' => 'success',
                    'cellArray' => $cellArray,
                ], 200);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getFNSReportView(Request $request)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->leftjoin('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->leftjoin('chartaccount as ca1', 'ca.parent_id', '=', 'ca1.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.code',
                    'ca.type',
                    'ca.sub_type',
                    'ca.gl_name',
                    'ca1.code as ca1_code',
                    'ca1.gl_name as ca1_gl_name',
                    'ca1.parent_id as ca1_parent_id',
                    'ca.parent_id',
                    'ca.id as main_id',
                    DB::raw('tb.credit - tb.debit as total_income'),
                    DB::raw('tb.debit - tb.credit as total_expense'),
                    DB::raw('tb.closing - tb.opening as total_closing'),
                    DB::raw('tb.closing as final_closing'),
                )
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();


            $parent_id_list = $all_parent_items = $all_chart_acc_ids = $all_balance_sheet_names = $sub_all_item_header_list = $balance_sheet_names = $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['label'] = $TrialBalanceItem->sub_type;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['data'][] = $TrialBalanceItem->total_expense;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['closing_data'][] = $TrialBalanceItem->total_income;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['final_closing'][] = $TrialBalanceItem->final_closing;
                        $balance_sheet_names[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name][$TrialBalanceItem->tbdate][] = $TrialBalanceItem;

                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['data'][] = $TrialBalanceItem->total_expense;
                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['closing_data'][] = $TrialBalanceItem->total_income;
                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $first_item = [
                            'id' => $TrialBalanceItem->main_id,
                            'code' => $TrialBalanceItem->main_id,
                            'gl_name' => $TrialBalanceItem->gl_name,
                            'parent_id' => $TrialBalanceItem->parent_id,
                            'category' => $TrialBalanceItem->category,
                            'type' => $TrialBalanceItem->type,
                        ];

                        $all_chart_acc_ids[$TrialBalanceItem->main_id] = $first_item;
                        $all_parent_items[$TrialBalanceItem->main_id] = $first_item;
                        if ($TrialBalanceItem->parent_id > 0) {
                            $second_item = [
                                'id' => $TrialBalanceItem->parent_id,
                                'code' => $TrialBalanceItem->ca1_code,
                                'gl_name' => $TrialBalanceItem->ca1_gl_name,
                                'parent_id' => $TrialBalanceItem->ca1_parent_id,
                                'category' => $TrialBalanceItem->category,
                                'type' => $TrialBalanceItem->type,
                            ];
                            $all_chart_acc_ids[$TrialBalanceItem->parent_id] = $second_item;
                            $all_parent_items[$TrialBalanceItem->parent_id] = $second_item;

                            if (is_array($parent_id_list) && $TrialBalanceItem->ca1_parent_id > 0 && !in_array($TrialBalanceItem->ca1_parent_id, $parent_id_list)) {
                                $parent_id_list[] = $TrialBalanceItem->ca1_parent_id;
                            }
                        }
                    }
                }
            }

            if (is_array($parent_id_list) && count($parent_id_list) > 0) {
                $all_parent_items = get_all_parent_list($parent_id_list, $all_parent_items);
            }

            $all_items = get_data_with_parent_child_rel($all_parent_items);
            $category_wise_all_items = get_data_with_category_wise($all_items);
         
            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->new_financial_summary_report_list;

            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            $qt_data_column_range = [];
            if (is_array($quater_grouped) && count($quater_grouped) > 0) {
                $qt_d_cnt = count($quater_grouped);
                $qt_d_cnt = $qt_d_cnt - 1;
                $quater_list_arr = array_values($quater_grouped);
                $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
                if ($quater_header_cell) {
                    $start_col = end($quater_header_cell);
                    foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
                        if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
                            $tb_header[$qtr_range_index] = [
                                'label' => $quater_list_arr[$qt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
                            $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $qtr_range_index),
                            );
                        }
                    }
                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            $year_data_column_range = [];
            if (is_array($year_grouped) && count($year_grouped) > 0) {
                $yt_d_cnt = count($year_grouped);
                $yt_d_cnt = $yt_d_cnt - 1;
                $year_list_arr = array_values($year_grouped);
                $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
                if ($year_header_cell) {
                    $start_col = end($year_header_cell);
                    foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
                        if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
                            $tb_header[$yt_range_index] = [
                                'label' => $year_list_arr[$yt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
                            $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $yt_range_index),
                            );
                        }
                    }
                }
            }

            $header_arr = array_merge($header_arr, $tb_header);

            $table_header = '';
            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $table_header .= '<th>' . $header_cell['label'] . '</th>';
                }
            }
            $table_header = '<thead>' . $table_header . '</thead>';
            $p_index = 4;

            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;
            $table_row_html = '';
            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];

                    $first_tr_item = $second_tr_item = $third_tr_item = '';

                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {


                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }

                            //$sub_label_str = generateTableRowHtml( $balance_sheet_names, $sub_items['label'], $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $sub_all_item_header_list, $month_wise_grouped, $year_wise_grouped );

                            $sub_label_str = setTableDataWithChildParent($all_balance_sheet_names, $category_wise_all_items, $sub_items['label'], $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $sub_all_item_header_list, $month_wise_grouped, $year_wise_grouped);

                            $extra_rows = $sub_items['label'] . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a>';
                            if ($sub_label_str) {
                                $extra_rows = '<a href="javascript:;" class="extra-row-btn ' . (!isset($sub_items['sub_row']) ? 'main-tr-btn' : '') . '" data-key="' . $sub_key . '"><i class="ri-add-circle-line"></i>' . $sub_items['label'] . '</a>' . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a>';
                            }
                            $parent_tr_hide = isset($sub_items['parent_tr_hide']) ? 'hide-his-tr' : '';
                            $item_css = '';
                            if (isset($sub_items['sub_row'])) {
                                $first_tr_item .= '<tr class="sub-row ' . $parent_tr_hide . '" data-label="' . $sub_items['sub_row'] . '" data-main-key="' . $sub_items['sub_row'] . '" >';
                                $item_css = 'style="padding-left:60px !important;"';
                            } else {

                                $first_tr_item .= '<tr class="' . $parent_tr_hide . '" >';
                            }
                            $first_tr_item .= '<td ' . $item_css . '>' . $extra_rows . '</td>';

                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {
                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];

                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {

                                        $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',', '', $final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }

                                    $first_tr_item .= '<td>' . convert_decimal_format($cell_val,$decimal_1) . '</td>';
                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;
                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }
                            $bl_flag = 0;

                            if( isset($sub_items['bl_rule']) && $sub_items['bl_rule'] == true ){
                                $bl_flag = 1;
                            }
                            if ($qt_data_column_range) {
                                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                    if( $bl_flag == 1 ){
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    }else{
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;    
                                    }
                                    
                                    $q_cell_val = round($q_cell_val, 2);
                                    $first_tr_item .= '<td>' . convert_decimal_format($q_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);

                                    if( $bl_flag == 1 ){
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                                    }else{
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                                    }
                                    
                                    $y_cell_val = round($y_cell_val, 2);
                                    $first_tr_item .= '<td>' . convert_decimal_format($y_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            $first_tr_item .= '</tr>';

                            if ($sub_label_str) {
                                $first_tr_item .= $sub_label_str;
                            }
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {

                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];

                        $hide_tr = isset($bl_key['data']['total']['hide_tr']) ? 'style="display:none !important;"' : '';
                        $main_row_cls = isset($bl_key['data']['total']['main_row']) ? 'main-tr-inner' : '';

                        $second_tr_item .= '<tr ' . $hide_tr . ' class="' . $main_row_cls . '" data-key="' . (isset($bl_key['data']['total']['main_row']) ? $bl_key['data']['total']['main_row'] : '') . '">';
                        $total_extra_rows = '';

                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        if (isset($bl_key['data']['total']['main_row'])) {
                            $second_tr_item .= '<td><a href="javascript:;" class="extra-row-btn main-tr-btn" data-key="' . $bl_key['data']['total']['main_row'] . '"><i class="ri-add-circle-line"></i>' . $total_label . '</a>' . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a></td>';
                        } else {
                            if ($bl_key['data']['total']['bold'] == true) {
                                $second_tr_item .= '<td><b>' . $total_label . '<b></td>';
                            } else {
                                $second_tr_item .= '<td>' . $total_label . '</td>';
                            }
                        }

                        $bl_tt_flag = 0;

                        if( isset($bl_key['data']['total']['bl_rule']) && $bl_key['data']['total']['bl_rule'] == true ){
                            $bl_tt_flag = 1;
                        }


                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {

                                //s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;
                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if ($bl_key['data']['total']['bold'] == true) {
                                    $second_tr_item .= '<td><b>' . convert_decimal_format($s_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $second_tr_item .= '<td>' . convert_decimal_format($s_cell_val,$decimal_2) . '</td>';
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if ($qt_data_column_range) {
                            foreach ($qt_data_column_range as $qt_index => $qt_column_group) {


                                if( $bl_tt_flag == 1 ){
                                    $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? $s_date_wise_sub_types[$qt_index][array_key_last($s_date_wise_sub_types[$qt_index])] : 0;
                                }else{
                                    $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                                }
                                
                                $q_cell_val = round($q_cell_val, 2);
                                if ($bl_key['data']['total']['bold'] == true) {
                                    $second_tr_item .= '<td><b>' . convert_decimal_format($q_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $second_tr_item .= '<td>' . convert_decimal_format($q_cell_val,$decimal_2) . '</td>';
                                }
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);

                                if( $bl_tt_flag == 1 ){
                                    $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? $s_year_wise_sub_types[$r_yrt_index][array_key_last($s_year_wise_sub_types[$r_yrt_index])] : 0;
                                }else{
                                    $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;
                                }
                                
                                $y_cell_val = round($y_cell_val, 2);
                                if ($bl_key['data']['total']['bold'] == true) {
                                    $second_tr_item .= '<td><b>' . convert_decimal_format($y_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $second_tr_item .= '<td>' . convert_decimal_format($y_cell_val,$decimal_2) . '</td>';
                                }
                            }
                        }
                        $second_tr_item .= '</tr>';
                        $p_index++;
                    }

                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $bl_ttt_flag = 0;

                                if( isset($final_items['bl_rule']) && $final_items['bl_rule'] == true ){
                                    $bl_ttt_flag = 1;
                                }

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }


                                $third_tr_item .= '<tr>';
                                if ($final_items['bold'] == true) {
                                    $third_tr_item .= '<td><b>' . $final_items['label'] . '<b></td>';
                                } else {
                                    $third_tr_item .= '<td>' . $final_items['label'] . '</td>';
                                }

                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $dtttt[] = $final_all_total[$value][$n_tbdate_column_group['column']];
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $third_tr_item .= '<td><b>' . convert_decimal_format($n_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $third_tr_item .= '<td>' . convert_decimal_format($n_cell_val,$decimal_3) . '</td>';
                                        }
                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                if ($qt_data_column_range) {
                                    foreach ($qt_data_column_range as $qt_index => $qt_column_group) {

                                        if( $bl_ttt_flag == 1 ){
                                            $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? $f_date_wise_sub_types[$qt_index][array_key_last($f_date_wise_sub_types[$qt_index])] : 0;
                                        }else{
                                            $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;
                                        }
                                        
                                        $q_cell_val = round($q_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $third_tr_item .= '<td><b>' . convert_decimal_format($q_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $third_tr_item .= '<td>' . convert_decimal_format($q_cell_val,$decimal_3) . '</td>';
                                        }
                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);

                                        if( $bl_ttt_flag == 1 ){
                                            $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? $f_year_wise_sub_types[$r_yrt_index][array_key_last($f_year_wise_sub_types[$r_yrt_index])] : 0;
                                        }else{
                                            $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                        }
                                        
                                        $y_cell_val = round($y_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $third_tr_item .= '<td><b>' . convert_decimal_format($y_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $third_tr_item .= '<td>' . convert_decimal_format($y_cell_val,$decimal_3) . '</td>';
                                        }
                                    }
                                }
                                $third_tr_item .= '</tr>';
                                $p_index++;
                            }

                        }
                    }
                    if (isset($bl_key['data']['total']['main_row'])) {
                        $table_row_html .= $second_tr_item . $first_tr_item . $third_tr_item;
                    } else {
                        $table_row_html .= $first_tr_item . $second_tr_item . $third_tr_item;
                    }
                    $all_item_rows[] = 'A' . $p_index;
                }

                $all_item_rows[] = 'A' . $p_index;

            }

            if ($table_row_html) {
                $table_row_html = '<tbody>' . $table_row_html . '</tbody>';
            } else {
                $table_row_html = '<tbody><tr><td>No data found</td></tr></tbody>';
            }

            $table_html = '<table class="table bordered-table mb-0">' . $table_header . $table_row_html . '</table>';

            return response()->json([
                'message' => 'Financial Summery View Generated Successfully!',
                'status' => 'success',
                'table_html' => $table_html,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getFNSReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->new_financial_summary_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            $qt_data_column_range = [];
            if (is_array($quater_grouped) && count($quater_grouped) > 0) {
                $qt_d_cnt = count($quater_grouped);
                $qt_d_cnt = $qt_d_cnt - 1;
                $quater_list_arr = array_values($quater_grouped);
                $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
                if ($quater_header_cell) {
                    $start_col = end($quater_header_cell);
                    foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
                        if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
                            $tb_header[$qtr_range_index] = [
                                'label' => $quater_list_arr[$qt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
                            $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $qtr_range_index),
                            );
                        }
                    }
                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            $year_data_column_range = [];
            if (is_array($year_grouped) && count($year_grouped) > 0) {
                $yt_d_cnt = count($year_grouped);
                $yt_d_cnt = $yt_d_cnt - 1;
                $year_list_arr = array_values($year_grouped);
                $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
                if ($year_header_cell) {
                    $start_col = end($year_header_cell);
                    foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
                        if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
                            $tb_header[$yt_range_index] = [
                                'label' => $year_list_arr[$yt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
                            $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $yt_range_index),
                            );
                        }
                    }
                }
            }


            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/fns-report/';

            $rp_po_path = 'fns-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Financial Summary Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {

                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }

                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if ($sub_items['r_type'] == 'pl') {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    } else {

                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['total_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['total_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['total_closing']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {

                                        $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',', '', $final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }

                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }
                            $bl_flag = 0;

                            if( isset($sub_items['bl_rule']) && $sub_items['bl_rule'] == true ){
                                $bl_flag = 1;
                            }
                            if ($qt_data_column_range) {
                                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                    if( $bl_flag == 1 ){
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;    
                                    }else{
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                                    }
                                    $q_cell_val = round($q_cell_val, 2);
                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_1))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);

                                    if( $bl_flag == 1 ){
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;    
                                    }else{
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                                    }

                                    
                                    $y_cell_val = round($y_cell_val, 2);
                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_1))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }
                                }
                            }
                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $bl_tt_flag = 0;

                        if( isset($bl_key['data']['total']['bl_rule']) && $bl_key['data']['total']['bl_rule'] == true ){
                            $bl_tt_flag = 1;
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                // $s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;
                                // $s_cell_val = round($s_cell_val,2);

                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if ($qt_data_column_range) {
                            foreach ($qt_data_column_range as $qt_index => $qt_column_group) {

                                if( $bl_tt_flag == 1 ){
                                    $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? $s_date_wise_sub_types[$qt_index][array_key_last($s_date_wise_sub_types[$qt_index])] : 0;    
                                }else{
                                    $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                                }

                                
                                $q_cell_val = round($q_cell_val, 2);
                                if (!isset($bl_key['data']['total']['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_2))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);

                                if( $bl_tt_flag == 1 ){
                                    $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? $s_year_wise_sub_types[$r_yrt_index][array_key_last($s_year_wise_sub_types[$r_yrt_index])] : 0;    
                                }else{
                                    $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;
                                }
                                
                                $y_cell_val = round($y_cell_val, 2);
                                if (!isset($bl_key['data']['total']['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_2))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                            }
                        }
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $bl_ttt_flag = 0;

                                if( isset($final_items['bl_rule']) && $final_items['bl_rule'] == true ){
                                    $bl_ttt_flag = 1;
                                }


                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];

                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val,$decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                if ($qt_data_column_range) {
                                    foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                        if( $bl_ttt_flag == 1 ){
                                            $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? $f_date_wise_sub_types[$qt_index][array_key_last($f_date_wise_sub_types[$qt_index])] : 0;    
                                        }else{
                                            $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;
                                        }
                                        
                                        $q_cell_val = round($q_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_3))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                        if( $bl_ttt_flag == 1 ){
                                            $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? $f_year_wise_sub_types[$r_yrt_index][array_key_last($f_year_wise_sub_types[$r_yrt_index])] : 0;    
                                        }else{
                                            $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                        }                                        
                                        $y_cell_val = round($y_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_3))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                $p_index++;
                            }

                        }
                    }


                    $all_item_rows[] = 'A' . $p_index;
                }

                $all_item_rows[] = 'A' . $p_index;

            }


            if (is_array($all_item_rows) && count($all_item_rows) > 0) {
                foreach ($all_item_rows as $a_key => $a_value) {
                    $color = ($a_key % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                    $spreadsheet->getActiveSheet()
                        ->getStyle($a_value)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB($color);

                    $cnt_index = str_replace('A', '', $a_value);

                    if ($data_column_range) {
                        foreach ($data_column_range as $at_index => $at_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($at_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    if ($qt_data_column_range) {
                        foreach ($qt_data_column_range as $nn_qt_index => $nn_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($nn_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    if ($year_data_column_range) {
                        foreach ($year_data_column_range as $nn_syrt_index => $nn_syrt_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($nn_syrt_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                }
            }

            if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            }

            $last_cell_index = $p_index - 1;
            $sheet->getStyle('A3:' . $end_cell . $last_cell_index)->applyFromArray($comman_all_border);
            $writer = new Xlsx($spreadsheet);
            $writer->save($path . $documentFileName);


            return response()->json([
                'message' => 'Financial Summary Generated Successfully!',
                'status' => 'success',
                'filename' => $documentFileName,
                'pdf' => url('/public/reports/fns-report/') . '/' . $rp_po_path . '/' . $documentFileName
            ], 200);

        } catch (\Exception $e) {

            if ($return_data == 1) {
                return [];
            }

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }


    public function getAICashFlowReport(Request $request)
    {
        $bl_sheetdata = $this->getCashflowReport($request, 1);
        $fileName = 'ai_bl_report_' . time() . '.pdf';
        $local_path = $path = public_path() . '/reports/bl-report/ai/';
        if (!file_exists($local_path)) {
            mkdir($local_path, 0775, true);
        }
        $filePath = $local_path . DIRECTORY_SEPARATOR . $fileName;
        $messages = [
            ['role' => 'system', 'content' => 'You are a financial analyst.'],
            [
                'role' => 'user',
                'content' => "Here is CashFlow data in JSON format:\n" . json_encode($bl_sheetdata) .
                    "\nPlease provide a summary of Executive Summary, Profitability Analysis, Liquidity & Solvency Analysis, Efficiency Ratios, Benchmark Comparison (specifying source), and Actionable Recommendations and i need response with html format so automatically show in my pdf also i don't need html and body i need only with reponse with need his own tags not <response> tag"
            ],
        ];
        try {
            $resp = $this->deepseek->chat($messages, 'deepseek-chat');
            $content = data_get($resp, 'choices.0.message.content', null);
            $this->pdf->generateFinancialReport($content, $filePath, 'CashFlow Report');
            return response()->json([
                'status' => 'success',

                'filename' => $fileName,
                'pdf' => url("/public/reports/bl-report/ai/{$fileName}"),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAIReport(Request $request){

        $date_filter = $request->get('date_filter');
        $end_date = $start_date = '';
        if (!empty($date_filter)) {
            $between_date = explode('/', $date_filter);
            $start_date = trim($between_date[0]);
            $end_date = trim($between_date[1]);
        }
        
        $user = auth()->user();
        
        $ai_industry_id = get_user_meta( $user->id, 'industry_id', true );

        if ( empty($ai_industry_id) || $ai_industry_id == 0 || $ai_industry_id == null ) {
              $ai_industry_id = 1;
        }
    
        $industry_data = DB::table('industry')->where('id','=', $ai_industry_id)->first();    
        $step_action = $request->get('step_action');
        $industry_name = isset($industry_data->name) ? $industry_data->name : '';
        $industry_category = isset($industry_data->category) ? $industry_data->category : '';

        if( $end_date ){
            $month = date('m', strtotime($end_date));
            $year = date('Y', strtotime($end_date));

            $request->merge([
               'month' => $month
             ]);
            $request->merge([
               'year' => $year
             ]);
        }
        $all_report_data = [];
        $record_list = '';
        if( $step_action == 0 ) {


             $dashboardController = new DashboardController();
             $dashboardData = $dashboardController->getDashboardReports($request);
             
            if (isset($dashboardData->original['report_data']) && !empty($dashboardData->original['report_data'])) {

                $month_string = $year_string = $record_list = '';
                $ij = 1;
                
                 if( $end_date ){
                     $month_string = date('F', strtotime($end_date));
                    $year_string = date('Y', strtotime($end_date));
                }
                    $last_month_str = '';
                if( $month_string ){
                    $last_month_str = '( '.$month_string .' '.$year_string.' )';
                }
            
                $record_list .= '
                <h2 style="color:#2c3e50; font-size:18px; margin-bottom:12px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                    Last Month KPI Data '.$last_month_str.'
                </h2>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>';
            
                 $ignore_keys = [ 'Fixed Assets', 'Other Assets', 'Other Liabilities', 'Other Capital', 'Cash & Bank', 'Total Debt', 'Equity', 'Total Funding', 'A/R Days', 'A/P Days', 'W/C Days', 'Inventory Days' ];
            
            
                foreach ($dashboardData->original['report_data'] as $record_title => $record_item) {
            
            
                    if( in_array(trim($record_title), $ignore_keys) ){
                        continue;
                    }
            
            
                    $last_month_string = number_format((float)$record_item['data']['last_month']);
                    $current_month_string = number_format((float)$record_item['data']['current_month']);
            
                    if( $record_item['noInt'] == 1 ){
                        $last_month_string = $record_item['data']['last_month'];
                        $current_month_string = $record_item['data']['current_month'];
                    }
            
                    // Percentage block
                    if ($record_item['data']['percentage'] < 0) {
                        $percentage_html = '
                            <p style="font-size:14px; margin:12px 0 0 0; color:#000;">
                                <span style="background:#fdecec; padding:6px; border-radius:4px; font-weight:500; color:#dc3545; font-size:14px;">
                                    <i class="ri-arrow-right-down-line"></i> '.number_format((float)$record_item['data']['percentage']).'%
                                </span> 
                                &nbsp;Last month '.$last_month_string.'
                            </p>';
                    } else {
                        $percentage_html = '
                            <p style="font-size:14px; margin:12px 0 0 0; color:#000;">
                                <span style="background:#e7f7ed; padding:6px; border-radius:4px; font-weight:500; color:#198754; font-size:14px;">
                                    <i class="ri-arrow-right-up-line"></i> '.number_format((float)$record_item['data']['percentage']).'%
                                </span> 
                                &nbsp;Last month '.$last_month_string.'
                            </p>';
                    }
                    
                    if( strtoupper($record_title) == 'IMPACT OF MANAGEMENT DECISIONS' ){
                        $record_title = 'IMPACT OF DECISIONS';
                    }
            
                    // Each box becomes a table cell
                    $record_list .= '
                    <td style="width:25%; padding:12px; vertical-align:top;">
                        <table style="width:100%; border:1px solid #155724; border-radius:8px; padding:12px; background:#f4fbf4;">
                            <tr>
                                <td style="padding-bottom:12px;">
                                    <table style="width:100%;">
                                        <tr>
                                            <td style="width:48px;">
                                                <div style="width:48px; height:48px; background:#0891b2; border-radius:50%; display:flex; justify-content:center; align-items:center;">
                                                    <img src="assets/images/home-eleven/icons/home-eleven-icon1.svg" alt="" style="width:24px; height:24px;">
                                                </div>
                                            </td>
                                            <td style="padding-left:10px;">
                                                <span style="font-size:13px; font-weight:600; color:#155724;text-transform: capitalize;">'.$record_title.'</span><br>
                                                <span style="font-size:15px; font-weight:600;color:#000;">'.$current_month_string.'</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
            
                            <tr>
                                <td>'.$percentage_html.'</td>
                            </tr>
                        </table>
                    </td>';
            
                    // 4 items per row
                    if ($ij == 2) {
                        $record_list .= '</tr><tr>';
                        $ij = 1;
                    } else {
                        $ij++;
                    }
                }
            
                $record_list .= '
                    </tr>
                </table>';
            }

        }else{
            $bl_sheetdata = $this->getBLReport($request, 1);
            $profit_lossdata = $this->getPLReport($request, 1);
            $cashflow_sheetdata = $this->getCashflowReport($request, 1);
            $profit_power_list = $this->getProfitPowerReport($request, 1);
            $cashmng_list = $this->getCashMngReport($request, 1);
            $capexres_list = $this->getCapexReport($request, 1);
            $financing_list = $this->getFinancingReport($request, 1);    

            $sales_query = DB::table('sales_register')->select([
                DB::raw("DATE_FORMAT(sales_register.sales_date, '%Y-%m') as period"),
                DB::raw("COUNT(*) as total_count"),                                   
                DB::raw("COUNT(DISTINCT sales_register.invoice_no) as invoice_count"),
                DB::raw("SUM(sales_register.amount) as total_amount")
            ])->groupBy(DB::raw("DATE_FORMAT(sales_register.sales_date, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(sales_register.sales_date, '%Y-%m')"), 'ASC');
            $purchase_query = DB::table('purchase_register')->select([
                DB::raw("DATE_FORMAT(purchase_register.purchase_date, '%Y-%m') as period"),
                DB::raw("COUNT(*) as total_count"),                                  
                DB::raw("COUNT(DISTINCT purchase_register.invoice_no) as invoice_count"),
                DB::raw("SUM(purchase_register.amount) as total_amount")
            ])
            ->groupBy(DB::raw("DATE_FORMAT(purchase_register.purchase_date, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(purchase_register.purchase_date, '%Y-%m')"), 'ASC');
            $inventory_query = DB::table('inventory')->select([
                DB::raw("DATE_FORMAT(inventory.buy_date, '%Y-%m') as period"),
                DB::raw("COUNT(*) as total_count"),                                  
                DB::raw("SUM(inventory.inward_qty) as total_inward_qty"),
                DB::raw("SUM(inventory.outward_qty) as total_outward_qty"),
                DB::raw("SUM(inventory.inward_value) as total_inward_value"),
                DB::raw("SUM(inventory.outward_value) as total_outward_value"),
            ])
            ->groupBy(DB::raw("DATE_FORMAT(inventory.buy_date, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(inventory.buy_date, '%Y-%m')"), 'ASC');
            $all_dates = [];
            if (!empty($date_filter)) {
                $all_dates = getMonthEndDates($start_date, $end_date);
                $sales_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('sales_date', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
                $purchase_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('purchase_date', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
                $inventory_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('buy_date', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $all_report_data = [
                'bl_sheetdata' => [ 'report_title' => 'Balance Sheet Items', 'data' => label_wise_format_data_by_monthwise(remove_comma_format_data_by_monthwise(format_data_by_monthwise($bl_sheetdata)))],
                'profit_lossdata' => [ 'report_title' => 'Profit Loss Items', 'data' => label_wise_format_data_by_monthwise( remove_comma_format_data_by_monthwise(format_data_by_monthwise($profit_lossdata)) ) ],
                'cashflow_sheetdata' => [ 'report_title' => 'CashFlow Items', 'data' => label_wise_format_data_by_monthwise( remove_comma_format_data_by_monthwise($cashflow_sheetdata) ) ],
                'profit_power_list' => [ 'report_title' => 'Profit Power Items', 'data' => label_wise_format_data_by_monthwise( remove_comma_format_data_by_monthwise($profit_power_list) ) ],
                'cashmng_list' => [ 'report_title' => 'Cash Management Items', 'data' => label_wise_format_data_by_monthwise( remove_comma_format_data_by_monthwise($cashmng_list) ) ],
                'capexres_list' => [ 'report_title' => 'Capex Items', 'data' => label_wise_format_data_by_monthwise( remove_comma_format_data_by_monthwise($capexres_list) ) ],
                'financing_list' => [ 'report_title' => 'Financing Items', 'data' => label_wise_format_data_by_monthwise( remove_comma_format_data_by_monthwise($financing_list) ) ],                
            ];

            $all_inventory_data = [                
                'sales_register_list' => [ 'report_title' => 'Sales Register List', 'data' => $sales_query->get()->toArray() ],
                'purchase_register_list' => [ 'report_title' => 'Purchase Register List', 'data' => $purchase_query->get()->toArray() ],
                'inventory_list' => [ 'report_title' => 'Inventory List', 'data' => $inventory_query->get()->toArray() ],
            ];

        

            $systemPromptExtra = '';
            $industry_name = isset($industry_data->name) ? $industry_data->name : '';
            $industry_category = isset($industry_data->category) ? $industry_category : '';
            // ENHANCED: Better industry context for AI
            $industryContext = '';
            if ($industry_name || $industry_category) {
                $industryContext = "
                **INDUSTRY-SPECIFIC ANALYSIS REQUIRED:**
                - **Industry:** {$industry_name}
                - **Category:** {$industry_category}
                - **Benchmark Expectations:** Based on {$industry_name} sector norms
                - **Key Metrics Focus:** Provide insights relevant to {$industry_category} businesses in India
                - **Terminology:** Use industry-appropriate terms for {$industry_name}
                ";
            }

             $systemPrompt = [
                    'role' => 'system',
                'content' => 'You are Profit Niti AI, a virtual CFO based in India who converts financial data into simple, actionable business stories for Indian businesses. You naturally use terms like **lakh, crore, FY, turnover, vendor, debtor, capex, opex, ROCE,** and **working capital**. You MUST follow the exact HTML structure provided for each section. Return only the HTML content without any wrapper tags. All amounts should be in Indian Rupees (₹). All describe content may use india english language easy to read.
                    **CRITICAL DATA ACCURACY RULES:**
                    1. Use ONLY the "monthwise_data" arrays provided. Each entry has "date" and "value".
                    2. Dates are in any format - convert to readable format (e.g., "April 2025").
                    3. All "value" fields are strings - convert to numbers: (float)$value.
                    4. If same value appears across months, report "stable at ₹X" not "increased/decreased".
                    5. Calculate trends only if at least 2 DIFFERENT values exist.
                    6. For ₹1,00,000 = ₹1 lakh. For ₹1,00,00,000 = ₹1 crore.
                    7. Do NOT invent data for missing months between provided dates.
                    8. **Dynamically detect date range**: Find earliest and latest dates in monthwise_data.
                    9. Report exactly: "Data covers [earliest month] to [latest month] ([count] months)"
                    10. **VERIFY ALL NUMBERS**: Cross-check calculations before presenting.
                    11. **INDUSTRY CONTEXT IS CRITICAL**: ' . $industryContext . '
                    12. **DOUBLE-CHECK**: All figures must match the provided JSON data exactly.'
            ];
        
            $section1Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business: " . json_encode($all_report_data) . "\n\n" .
                    "Generate ONLY Section 1 (Big Picture) using this HTML (clean, no symbols before titles) and use json data for correct accurate numbers always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .

                    '<section style="margin-bottom:25px; font-family: Arial, sans-serif; font-size:13px; line-height:1.55;">

                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:12px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            1. Big Picture — How the Business Looks Right Now
                        </h2>

                        <div id="overview" style="margin-bottom:18px;">
                            <!-- 3–4 sentence overview of overall business performance -->
                        </div>

                        <div style="background:#f4fbf4; border-left:4px solid #28a745; padding:12px 14px; margin:18px 0;">
                            <h3 style="color:#155724; margin:0 0 10px 0; font-size:15px; font-weight:bold;">
                                GREEN FLAG BOX — What\'s Working Well
                            </h3>
                            <ul style="margin:0; padding-left:18px; list-style-type:disc;">
                                <!-- 3 strong positive insights -->
                            </ul>
                        </div>

                        <div style="background:#fff5f5; border-left:4px solid #dc3545; padding:12px 14px; margin:18px 0;">
                            <h3 style="color:#721c24; margin:0 0 10px 0; font-size:15px; font-weight:bold;">
                                RED FLAG BOX — What Needs Attention
                            </h3>
                            <ul style="margin:0; padding-left:18px; list-style-type:disc;">
                                <!-- 3 critical issues or weaknesses -->
                            </ul>
                        </div>

                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px; margin:18px 0;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0 0;">
                                <!-- 1–2 sentences of actionable CFO guidance -->
                            </p>
                        </div>

                    </section>'
            ];

            $section2Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_report_data['profit_power_list']) . "\n\n" . 
                    "Generate ONLY Section 2 (Profit Power) using this clean HTML structure and use json data for correct accurate numbers always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            2. Profit Power — The Earning Engine
                        </h2>
            
                        <div id="profit-analysis" style="margin-bottom:18px;">
                            <!-- Explain profit drivers, profitable products, expense issues -->
                        </div>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px; margin-top:18px;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0 0;">
                                <!-- 1–2 sentences profit-specific advice -->
                            </p>
                        </div>
            
                    </section>'
            ];


            $section3Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_report_data['cashflow_sheetdata']) . "\n\n" .
                    "Generate ONLY Section 3 (Cash Flow Quality) using this clean HTML structure and use json data for correct accurate numbers always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            3. Cash Flow Quality — Profit vs Real Cash
                        </h2>
            
                        <div id="cash-flow-analysis" style="margin-bottom:18px;">
                            <!-- Profit to cash conversion and cash blockages -->
                        </div>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0;">
                                <!-- 1–2 sentences cash flow advice -->
                            </p>
                        </div>
            
                    </section>'
            ];

            $section4Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_report_data['cashmng_list']) . "\n\n" .
                    "Generate ONLY Section 4 (Cash Management) using this clean HTML structure and use json data for correct accurate numbers always :\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            4. Cash Management — Daily Cash Rhythm
                        </h2>
            
                        <div id="cash-rhythm-analysis" style="margin-bottom:18px;">
                            <!-- DSO, DPO, DIO, cash cycle analysis -->
                        </div>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0;">
                                <!-- 1–2 sentences guidance -->
                            </p>
                        </div>
            
                    </section>'
            ];

            $section5Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_report_data) . "\n\n" .
                    "Generate ONLY Section 5 (Growth Investments) using this clean HTML structure and use json data for correct accurate numbers always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            5. Growth Investments — Where Money Is Going
                        </h2>
            
                        <div id="investment-analysis" style="margin-bottom:18px;">
                            <!-- Investment trends, ROI, underused assets -->
                        </div>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0;">
                                <!-- Investment-specific advice -->
                            </p>
                        </div>
            
                    </section>'
            ];

            $section6Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_report_data) . "\n\n" .
                    "Generate ONLY Section 6 (Funding Story) using this clean HTML structure and use json data for correct accurate numbers always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            6. Funding Story — How Growth Is Being Financed
                        </h2>
            
                        <div id="funding-analysis" style="margin-bottom:18px;">
                            <!-- Loan vs equity, interest costs, debt impact -->
                        </div>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0;">
                                <!-- Funding-specific guidance -->
                            </p>
                        </div>
            
                    </section>'
            ];

            $section7Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_report_data['bl_sheetdata']) . "\n\n" .
                    "Generate ONLY Section 7 (Balance Sheet Health) using this clean HTML structure and use json data for correct accurate numbers always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            7. Balance Sheet Health — Financial Fitness
                        </h2>
            
                        <div id="balance-sheet-analysis" style="margin-bottom:18px;">
                            <!-- Asset-liability balance, key ratios -->
                        </div>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px;">
                            <strong style="font-size:14px;">Strategic Advice:</strong>
                            <p style="margin:8px 0 0;">
                                <!-- 1–2 lines of improvement advice -->
                            </p>
                        </div>
            
                    </section>'
            ];

            $section8Prompt = [
                'role' => 'user',
                'content' =>
                    "Based on previous analysis, generate ONLY Section 8 (AI Simulation) using this clean HTML:\n\n" . 
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            8. Smart AI Simulation — What-If Scenarios
                        </h2>
            
                        <div style="background:#fff3e0; border-left:4px solid #ff9800; padding:12px 14px;">
                            <p style="margin:0 0 10px 0;"><em>Based on weak spots identified in the report, the AI simulated the following outcomes:</em></p>
                            <ul style="margin:0; padding-left:18px; list-style-type:disc;">
                                <li><strong>Scenario A – Profit Focus:</strong> [Describe profit simulation]</li>
                                <li><strong>Scenario B – Cash Focus:</strong> [Describe cash simulation]</li>
                                <li><strong>Scenario C – Balanced:</strong> [Describe combined scenario]</li>
                            </ul>
                        </div>
            
                    </section>'
            ];

            $section9Prompt = [
                'role' => 'user',
                'content' =>
                    "Here is financial data for {$industry_name} business:  " . json_encode($all_inventory_data) . "\n\n" .
                    "Generate ONLY Section 9 (Operational Insights) using this clean HTML and use json data for correct exact figure always:\n\n" . "**Industry Context:** {$industry_name} - {$industry_category}\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            9. Operational Insight Engine — Ground-Level Trends
                        </h2>
            
                        <div id="operational-analysis">
                            <!-- Narrative on sales, customers, inventory -->
                        </div>
            
                    </section>'
            ];
            
            $section10Prompt = [
                'role' => 'user',
                'content' =>
                    "Based on all previous analysis, generate ONLY Section 10 (Action Plan) using this clean HTML:\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            10. 90-Day Action Plan
                        </h2>
            
                        <table style="width:100%; border-collapse:collapse; margin:15px 0;">
                            <thead>
                                <tr>
                                    <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Days</th>
                                    <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Focus</th>
                                    <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Action</th>
                                    <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Target</th>
                                    <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Benefit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border:1px solid #ddd; padding:10px;">0-30</td>
                                    <td style="border:1px solid #ddd; padding:10px;">Cash</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Cash action]</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Target]</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Benefit]</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #ddd; padding:10px;">31-60</td>
                                    <td style="border:1px solid #ddd; padding:10px;">Profit</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Profit action]</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Target]</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Benefit]</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #ddd; padding:10px;">61-90</td>
                                    <td style="border:1px solid #ddd; padding:10px;">Growth</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Growth action]</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Target]</td>
                                    <td style="border:1px solid #ddd; padding:10px;">[Benefit]</td>
                                </tr>
                            </tbody>
                        </table>
            
                    </section>'
            ];

            $summaryPrompt = [
                'role' => 'user',
                'content' =>
                    "Generate ONLY the Final Summary using this clean HTML:\n\n" .
            
                    '<section style="margin-bottom:25px; font-family:Arial, sans-serif; font-size:13px; line-height:1.55;">
            
                        <h2 style="color:#2c3e50; font-size:18px; margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:6px;">
                            Final Summary
                        </h2>
            
                        <div style="background:#eef5ff; border-left:4px solid #2196f3; padding:12px 14px;">
                            <p style="margin:0;">"[Strong concluding statement summarizing the full CFO report]"</p>
                        </div>
            
                    </section>'
            ];

            $sections = [];
            
            $sectionPrompts = [
                'section1' => $section1Prompt,
                'section2' => $section2Prompt,
                'section3' => $section3Prompt,
                'section4' => $section4Prompt,
                'section5' => $section5Prompt,
                'section6' => $section6Prompt,
                'section7' => $section7Prompt,
                'section8' => $section8Prompt,
                'section9' => $section9Prompt,
                'section10' => $section10Prompt,
            ];

        }
      
        
        $fileName = 'ai_report_' . time() . '.pdf';
        $local_path = $path = public_path() . '/reports/ai-report/';
        if (!file_exists($local_path)) {
            mkdir($local_path, 0775, true);
        }
        $filePath = $local_path . DIRECTORY_SEPARATOR . $fileName;
        
        
        $local_path =  public_path() . '/reports/ai-report/html/';
        if (!is_dir($local_path)) {
            mkdir($local_path, 0777);
        }
        $user_id = auth()->user()->id;
        $documentFileName = $user_id . ".txt";


        if( $step_action == 0 ){
            file_put_contents($local_path.$documentFileName, $record_list);
        }

        try {

            if( $step_action > 0 ){
                $messages = [
                    $systemPrompt,
                    ['role' => 'user', 'content' => $sectionPrompts['section'.$step_action]['content']]
                ];
                $resp = $this->deepseek->chat($messages, 'deepseek-chat');
                $content = data_get($resp, 'choices.0.message.content', '');
                file_put_contents($local_path.$documentFileName, $content, FILE_APPEND);
            }
            
            if( $step_action == 10 ){
                // Combine all sections with wrapper HTML
                $fullHtml = $this->buildFullReport(file_get_contents(url("/public/reports/ai-report/html/".$documentFileName.'?ver='.time())), $date_filter );
                // Generate PDF
                $this->pdf->generateFinancialReport($fullHtml, $filePath, 'AI Report');
                return response()->json([
                    'status' => 'final_success',
                    'filename' => $fileName,
                    'pdf' => url("/public/reports/ai-report/{$fileName}"),
                ]);
            }else{
                 return response()->json([
                    'status' => 'success', 
                    //'all_report_data' => $all_report_data,                                   
                ]);
            }
        } catch (\Exception $e) {
                // Log error but continue with other sections
                //Log::error("Error generating {$key}: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }


    private function buildFullReport($section_html, $date_range = '' )
    {

        $branding_option_list = Options::where('option_name', 'branding_options')->first();
        $branding_option_arr = [];
        if( isset($branding_option_list->option_value) && !empty($branding_option_list->option_value) ){
            $branding_option_arr = unserialize($branding_option_list->option_value);
        }

        if( isset($branding_option_arr['report_main_title']) && empty($branding_option_arr['report_main_title']) || !isset($branding_option_arr['report_main_title']) ){
            $r_title = 'Profit Niti – AI Report ( '.$date_range.' )';
        }else{
            $r_title = str_replace( '[date_range]', $date_range, $branding_option_arr['report_main_title'] );
        }

        return '
        <div style="max-width: 900px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">

            <header style="border-bottom: 2px solid #2c3e50; padding-bottom: 12px; margin-bottom: 25px;">
                <h1 style="
                    margin: 0 0 0;
                    font-size: 22px;
                    font-weight: 700;
                    color: #2c3e50;
                    letter-spacing: 0.3px;
                ">
                    '.$r_title.'
                </h1>
            </header>

            ' . $section_html . '

        </div>';
    }


    public function getCashflowReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $month = $request->get('month');
            $year = $request->get('year');
            $report_type = $request->get('report_type');

            // $currentMonth = date("Y-m", strtotime("$year-$month-01"));
            // $previousMonth = date("Y-m", strtotime("-1 month", strtotime("$year-$month-01")));

            // $cMonth = date('m', strtotime($currentMonth));
            // $PvMonth = date('m', strtotime($previousMonth));

            // $end_date = date("Y-m-t", strtotime("$year-$month-01"));

            // $start_date = date("Y-m-d", strtotime("-1 month", strtotime("$year-$month-01")));
            // $request->merge([
            //     'date_filter' => $start_date . ' / ' . $end_date
            // ]);

            $date_filter = $request->get('date_filter');
            
            $pl_query = DB::table('trialbalance as tb')
            ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
            ->select(
                DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m') as month"), 
                DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                'ca.category',
                'ca.type',
                'ca.sub_type',
                DB::raw('SUM(tb.closing - tb.opening) as total_closing'),                
                DB::raw('SUM(tb.closing) as final_closing'),
            )
            ->whereIn('ca.type', [
                'cash_and_bank_balances',
                'accounts_receivable',
                'accounts_payable',
                'closing_stock',
                'fixed_assets',
                'secured_short_term_borrowings',
                'secured_long_term_borrowings',
                'unsecured_long_term_borrowings',
                'equity_share_capital',
                'other_equity',
                'reserve_surplus',
                'current_year_profit',
                'current_investments',
                'other_current_assets',
                'branch',
                'suspense',
                'non_current_investments',
                'long_term_loans_advances',
                'deferred_tax_assets',
                'other_non_current_assets',
                'short_term_provisions',
                'current_deferred_tax_liabilities',
                'other_current_liabilities',
                'difference_in_opening_balance',
                'long_term_provisions',
                'deferred_tax_liabilities',
                'other_non_current_liabilities'
            ])                
            ->groupBy(DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m')"), 'tbdate', 'ca.category', 'ca.type','ca.sub_type' )
            ->orderBy(DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m')"), 'asc');

           $end_date = $start_date  = '';
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $start_date = date("Y-m-d", strtotime("-1 month", strtotime($start_date)));                
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $request->merge([
                'date_filter' => $start_date.' / '.$end_date
            ]);

            $fns_list = $this->getFNSReport($request, 1);

           

            $fns_final_all_total = [];
            foreach ($fns_list as $key => $s_value) {
                $first_col = $s_value['A'];
                unset($s_value['A']);
                $fns_final_all_total[$first_col] = $s_value;
            }

            $prev_month_flag = 0;

            if( isset($fns_final_all_total['Particulars']['B']) && !empty($fns_final_all_total['Particulars']['B']) && !is_null($fns_final_all_total['Particulars']['B']) && date('Y-m',strtotime($fns_final_all_total['Particulars']['B'])) == date('Y-m',strtotime($start_date)) ){
                $prev_month_flag = 1;
            }            

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }

            $TrialBalanceList = $pl_query->get();


            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];

            $hide_column = 0;

            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));

                        if( date('Y-m',strtotime($start_date)) == date('Y-m',strtotime($TrialBalanceItem->tbdate)) ){
                            $hide_column = 1;
                        }

                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = isset($TrialBalanceItem->total_expense) ? $TrialBalanceItem->total_expense : 0;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = isset($TrialBalanceItem->total_income) ? $TrialBalanceItem->total_income : 0;

                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = isset($TrialBalanceItem->total_expense) ? $TrialBalanceItem->total_expense : 0;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = isset($TrialBalanceItem->total_income) ? $TrialBalanceItem->total_income : 0;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['total_closing'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                    }
                }
            }

            $column_width_list = [
                'A' => 300,
                'B' => 150,
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 2;
            $start_col = 'B';

            $finacial_titles = comman_finacial_column_title($all_dates);

            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];
         
            if ($TrialBalanceList) {
                if( $hide_column == 1 ){             
                    array_shift($tbdate_list);
                }
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);

                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {

                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);
            

            $local_path = $path = public_path() . '/reports/cashflow-report/';

            $rp_po_path = 'cashflow-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('CASH FLOW STATEMENT')->getStyle('A1:B1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getCell('A3')->setValue('Cash at Beginning of Period')->getStyle('A3')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A4')->setValue('Cash at End of Period')->getStyle('A4')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Cash Flows')->getStyle('A5')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Cash flows from Operating Activity')->getStyle('A6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Cash flows from Investing Activity')->getStyle('A7')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Cash flows from Financing Activity')->getStyle('A8')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A9')->setValue('Validation')->getStyle('A9')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));

            $spreadsheet->getActiveSheet()->getCell('A2')->setValue('Particulars')->getStyle('A2')->applyFromArray(hg_comman_customer_header_style('#156082', 14, 'ffffff'));
           // $spreadsheet->getActiveSheet()->getCell('B11')->setValue('Price')->getStyle('B11')->applyFromArray(hg_comman_customer_header_style('#156082', 14, 'ffffff'));

            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');

                }
            }

            $p_index = 11;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;


            /*$cashflow_data = array(
                array( 'label' => 'Trade Receivables', 'data_keys' => 'accounts_receivable', 'dr_cr' => -1 ),
                array( 'label' => 'Trade Payables', 'data_keys' => 'accounts_payable', 'dr_cr' => 1 ),
                array( 'label' => 'Inventory', 'data_keys' => 'closing_stock', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Operating Activity', 'sum_row' => ['Trade Receivables','Trade Payables','Inventory'] ),
                array( 'label' => 'Fixed Assets', 'data_keys' => 'fixed_assets', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Investing Activity', 'sum_row' => ['Fixed Assets'] ),
                array( 'label' => 'Equity', 'data_keys' => 'equity_share_capital,other_equity,reserve_surplus,current_year_profit', 'dr_cr' => 1 ),
                array( 'label' => 'Borrowings', 'data_keys' => 'secured_short_term_borrowings,secured_long_term_borrowings,unsecured_long_term_borrowings', 'dr_cr' => 1 ),
                array( 'label' => 'Others', 'data_keys' => 'current_investments,other_current_assets,branch,suspense,short_term_loans_advances,non_current_investments,long_term_loans_advances,deferred_tax_assets,other_non_current_assets,short_term_provisions,current_deferred_tax_liabilities,other_current_liabilities,difference_in_opening_balance,long_term_provisions,deferred_tax_liabilities,other_non_current_liabilities', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Financing Activity',  'sum_row' => ['Equity','Borrowings','Others'] ),
                array( 'label' => 'Total Cash Flows', 'sum_row' => ['Cash Inflows from Operating Activity','Cash Inflows from Investing Activity','Cash Inflows from Financing Activity'] ),
            );

            $final_all_total = [];
            foreach (  $cashflow_data as $key => $cash_value ) {

                if( isset($cash_value['data_keys']) ){
                    $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $cash_value['label'] )->getStyle( 'A'.$p_index )->applyFromArray($subitemStyle);
                    $data_keys = explode( ',', $cash_value['data_keys'] );
                    $pastMonthVal = $currentMonthVal = 0;                    
                    foreach ( $data_keys as $dkey => $data_val ) {
                        $currentMonthVal += isset($comman_trail_list[$data_val][$cMonth]->final_closing) ? (float)$comman_trail_list[$data_val][$cMonth]->final_closing : 0;
                        $pastMonthVal += isset($comman_trail_list[$data_val][$PvMonth]->final_closing) ? (float)$comman_trail_list[$data_val][$PvMonth]->final_closing : 0;
                    }              
                    $cell_val = round( ($currentMonthVal - $pastMonthVal),2);
                    $cell_val =    $cell_val * $cash_value['dr_cr'];                    
                    $final_all_total[ $cash_value['label'] ] = $cell_val;
                    $spreadsheet->getActiveSheet()->getCell( 'B'.$p_index )->setValue( convert_decimal_format($cell_val) )->getStyle( 'B'.$p_index )->applyFromArray($subitemStyle);
                }

                if( isset($cash_value['sum_row']) ){
                    $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $cash_value['label'] )->getStyle( 'A'.$p_index )->applyFromArray($itemStyle);
                    $sub_total = 0;
                    foreach ( $cash_value['sum_row'] as $s_key => $s_value ) {
                        $sub_total += isset($final_all_total[$s_value]) ? (float)$final_all_total[$s_value] : 0;
                    }
                    $final_all_total[ $cash_value['label'] ] = $sub_total;
                    $spreadsheet->getActiveSheet()->getCell( 'B'.$p_index )->setValue( convert_decimal_format($sub_total) )->getStyle( 'B'.$p_index )->applyFromArray($itemStyle);
                }   

                $p_index++;
            }*/

            $cashflow_data = array(
                array('label' => 'Trade Receivables', 'data_keys' => 'Accounts Receivable', 'dr_cr' => -1),
                array('label' => 'Trade Payables', 'data_keys' => 'Accounts Payable', 'dr_cr' => 1),
                array('label' => 'Inventory', 'data_keys' => 'Closing Stock', 'dr_cr' => -1),
                array('label' => 'Cash Inflows from Operating Activity', 'sum_row' => ['Trade Receivables' => 11, 'Trade Payables' => 12, 'Inventory' => 13]),
                array('label' => 'Fixed Assets', 'data_keys' => 'Fixed Assets', 'dr_cr' => -1),
                array('label' => 'Cash Inflows from Investing Activity', 'sum_row' => ['Fixed Assets' => 15]),
                array('label' => 'Equity', 'data_keys' => 'Equity', 'dr_cr' => 1),
                array('label' => 'Borrowings', 'data_keys' => 'Bank Loans - Current,Bank Loans - Non Current', 'dr_cr' => 1),
                array('label' => 'Others', 'data_plus_keys' => 'Other Non Current Liabilities,Other Current Liabilities', 'data_minus_keys' => 'Other Non Current Assets,Other Current Assets', 'dr_cr' => -1),
                array('label' => 'Cash Inflows from Financing Activity', 'sum_row' => ['Equity' => 17, 'Borrowings' => 18, 'Others' => 19 ]),
                array('label' => 'Total Cash Flows', 'sum_row' => ['Cash Inflows from Operating Activity' => 14, 'Cash Inflows from Investing Activity' => 16, 'Cash Inflows from Financing Activity' => 20 ]),
            );

            $final_all_total = [];

            foreach ($cashflow_data as $key => $cash_value) {

                if( $prev_month_flag == 1 ){
                    $next_index = 'B';
                }else{
                    $next_index = 'A';
                }


                if (isset($cash_value['data_keys']) || isset($cash_value['data_plus_keys'])) {
                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($cash_value['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                }

                if (isset($cash_value['sum_row'])) {
                    $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($cash_value['label'])->getStyle('A' . $p_index)->applyFromArray($itemStyle);
                }

                if ($data_column_range) {
                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                        $data_cell_name = $n_tbdate_column_group['column'];
                         if( $prev_month_flag == 1 ){
                            $data_cell_name = hg_only_get_next_excel_cell($n_tbdate_column_group['column']);
                         }


                        if (isset($cash_value['data_keys']) || isset($cash_value['data_plus_keys'])) {

                            if ($cash_value['label'] == 'Others') {

                                $data_minus_keys = explode(',', $cash_value['data_minus_keys']);
                                $data_plus_keys = explode(',', $cash_value['data_plus_keys']);
                                $pluspastMonthVal = $pluscurrentMonthVal = $minuspastMonthVal = $minuscurrentMonthVal = 0;
                                foreach ($data_minus_keys as $dkey => $data_val) {
                                    $minuscurrentMonthVal += isset($fns_final_all_total[$data_val][$data_cell_name]) ? (float) str_replace(',', '', $fns_final_all_total[$data_val][$data_cell_name]) : 0;
                                    $minuspastMonthVal += isset($fns_final_all_total[$data_val][$next_index]) ? (float) str_replace(',', '', $fns_final_all_total[$data_val][$next_index]) : 0;
                                }

                                foreach ($data_plus_keys as $dkey => $pdata_val) {
                                    $pluscurrentMonthVal += isset($fns_final_all_total[$pdata_val][$data_cell_name]) ? (float) str_replace(',', '', $fns_final_all_total[$pdata_val][$data_cell_name]) : 0;
                                    $pluspastMonthVal += isset($fns_final_all_total[$pdata_val][$next_index]) ? (float) str_replace(',', '', $fns_final_all_total[$pdata_val][$next_index]) : 0;
                                }

                                $minus_cell_val = round(($minuscurrentMonthVal - $minuspastMonthVal), 2);
                                $minus_cell_val = $minus_cell_val * $cash_value['dr_cr'];
                                $plus_cell_val = round(($pluscurrentMonthVal - $pluspastMonthVal), 2);
                                $cell_val = $plus_cell_val + $minus_cell_val;
                            } else {

                                $data_keys = explode(',', $cash_value['data_keys']);
                                $pastMonthVal = $currentMonthVal = 0;
                                foreach ($data_keys as $dkey => $data_val) {
                                    $currentMonthVal += isset($fns_final_all_total[$data_val][$data_cell_name]) ? (float) str_replace(',', '', $fns_final_all_total[$data_val][$data_cell_name]) : 0;
                                    $pastMonthVal += isset($fns_final_all_total[$data_val][$next_index]) ? (float) str_replace(',', '', $fns_final_all_total[$data_val][$next_index]) : 0;
                                }
                                
                                $cell_val = round(($currentMonthVal - $pastMonthVal), 2);
                                $cell_val = $cell_val * $cash_value['dr_cr'];

                            }

                            $final_all_total[$cash_value['label']][$n_tbdate_column_group['column'] . $p_index] = $cell_val;
                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                        }

                        if (isset($cash_value['sum_row'])) {
                            $sub_total = 0;
                            foreach ($cash_value['sum_row'] as $s_value => $s_index) {
                                $sub_total += isset($final_all_total[$s_value][$n_tbdate_column_group['column'] . $s_index]) ? (float) $final_all_total[$s_value][$n_tbdate_column_group['column'] . $s_index] : 0;
                            }
                            $final_all_total[$cash_value['label']][$n_tbdate_column_group['column'] . $p_index] = $sub_total;
                            $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($sub_total))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray($itemStyle);
                        }

                        
                        $cashBankCurrent = isset($fns_final_all_total['Cash & Bank'][$data_cell_name]) ? (float) str_replace(',', '', $fns_final_all_total['Cash & Bank'][$data_cell_name]) : 0;
                        $cashBankCurrent = round($cashBankCurrent, 2);
                        $cashBankPrev = isset($fns_final_all_total['Cash & Bank'][$next_index]) ? (float) str_replace(',', '', $fns_final_all_total['Cash & Bank'][$next_index]) : 0;
                        $cashBankPrev = round($cashBankPrev, 2);
                        $cashFlowTotal = round($cashBankCurrent - $cashBankPrev, 2);

                        $total_cashflow_amt = isset($final_all_total['Total Cash Flows'][$n_tbdate_column_group['column'] . '21']) ? (float) str_replace(',', '',$final_all_total['Total Cash Flows'][$n_tbdate_column_group['column'] . '21']) : 0;

                        $validation = round($total_cashflow_amt - $cashFlowTotal, 2);

                        $operating_activity = isset($final_all_total['Cash Inflows from Operating Activity'][$n_tbdate_column_group['column'] . '14']) ? $final_all_total['Cash Inflows from Operating Activity'][$n_tbdate_column_group['column'] . '14'] : 0;
                        $investing_activity = isset($final_all_total['Cash Inflows from Investing Activity'][$n_tbdate_column_group['column'] . '16']) ? $final_all_total['Cash Inflows from Investing Activity'][$n_tbdate_column_group['column'] . '16'] : 0;
                        $financing_activity = isset($final_all_total['Cash Inflows from Financing Activity'][$n_tbdate_column_group['column'] . '20']) ? $final_all_total['Cash Inflows from Financing Activity'][$n_tbdate_column_group['column'] . '20'] : 0;


                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '3')->setValue(convert_decimal_format($cashBankPrev))->getStyle($n_tbdate_column_group['column'].'3')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'4')->setValue(convert_decimal_format($cashBankCurrent))->getStyle($n_tbdate_column_group['column'].'4')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'5')->setValue(convert_decimal_format($cashFlowTotal))->getStyle($n_tbdate_column_group['column'].'5')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'6')->setValue(convert_decimal_format($operating_activity))->getStyle($n_tbdate_column_group['column'].'6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'7')->setValue(convert_decimal_format($investing_activity))->getStyle($n_tbdate_column_group['column'].'7')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'8')->setValue(convert_decimal_format($financing_activity))->getStyle($n_tbdate_column_group['column'].'8')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'9')->setValue(convert_decimal_format($validation))->getStyle($n_tbdate_column_group['column'].'9')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));

                        $next_index = $data_cell_name;
                    }
                }
                $p_index++;


            }
         

            // foreach ($cashflow_data as $key => $cash_value) {

            //     if (isset($cash_value['data_keys']) || isset($cash_value['data_plus_keys'])) {
            //         $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($cash_value['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);

            //         if ($cash_value['label'] == 'Others') {

            //             $data_minus_keys = explode(',', $cash_value['data_minus_keys']);
            //             $data_plus_keys = explode(',', $cash_value['data_plus_keys']);
            //             $pluspastMonthVal = $pluscurrentMonthVal = $minuspastMonthVal = $minuscurrentMonthVal = 0;
            //             foreach ($data_minus_keys as $dkey => $data_val) {
            //                 $minuscurrentMonthVal += isset($fns_final_all_total[$data_val]['C']) ? (float) str_replace(',', '', $fns_final_all_total[$data_val]['C']) : 0;
            //                 $minuspastMonthVal += isset($fns_final_all_total[$data_val]['B']) ? (float) str_replace(',', '', $fns_final_all_total[$data_val]['B']) : 0;
            //             }

            //             foreach ($data_plus_keys as $dkey => $pdata_val) {
            //                 $pluscurrentMonthVal += isset($fns_final_all_total[$pdata_val]['C']) ? (float) str_replace(',', '', $fns_final_all_total[$pdata_val]['C']) : 0;
            //                 $pluspastMonthVal += isset($fns_final_all_total[$pdata_val]['B']) ? (float) str_replace(',', '', $fns_final_all_total[$pdata_val]['B']) : 0;
            //             }

            //             $minus_cell_val = round(($minuscurrentMonthVal - $minuspastMonthVal), 2);
            //             $minus_cell_val = $minus_cell_val * $cash_value['dr_cr'];
            //             $plus_cell_val = round(($pluscurrentMonthVal - $pluspastMonthVal), 2);
            //             $cell_val = $plus_cell_val + $minus_cell_val;

            //         } else {
            //             $data_keys = explode(',', $cash_value['data_keys']);
            //             $pastMonthVal = $currentMonthVal = 0;
            //             foreach ($data_keys as $dkey => $data_val) {
            //                 $currentMonthVal += isset($fns_final_all_total[$data_val]['C']) ? (float) str_replace(',', '', $fns_final_all_total[$data_val]['C']) : 0;
            //                 $pastMonthVal += isset($fns_final_all_total[$data_val]['B']) ? (float) str_replace(',', '', $fns_final_all_total[$data_val]['B']) : 0;
            //             }
            //             $cell_val = round(($currentMonthVal - $pastMonthVal), 2);
            //             $cell_val = $cell_val * $cash_value['dr_cr'];
            //         }

            //         $final_all_total[$cash_value['label']] = $cell_val;
            //         $spreadsheet->getActiveSheet()->getCell('B' . $p_index)->setValue(convert_decimal_format($cell_val))->getStyle('B' . $p_index)->applyFromArray($subitemStyle);
            //     }

            //     if (isset($cash_value['sum_row'])) {
            //         $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($cash_value['label'])->getStyle('A' . $p_index)->applyFromArray($itemStyle);
            //         $sub_total = 0;
            //         foreach ($cash_value['sum_row'] as $s_key => $s_value) {
            //             $sub_total += isset($final_all_total[$s_value]) ? (float) $final_all_total[$s_value] : 0;
            //         }
            //         $final_all_total[$cash_value['label']] = $sub_total;
            //         $spreadsheet->getActiveSheet()->getCell('B' . $p_index)->setValue(convert_decimal_format($sub_total))->getStyle('B' . $p_index)->applyFromArray($itemStyle);
            //     }

            //     $p_index++;
            // }


            // $cashBankCurrent = isset($fns_final_all_total['Cash & Bank']['C']) ? (float) str_replace(',', '', $fns_final_all_total['Cash & Bank']['C']) : 0;
            // $cashBankCurrent = round($cashBankCurrent, 2);
            // $cashBankPrev = isset($fns_final_all_total['Cash & Bank']['B']) ? (float) str_replace(',', '', $fns_final_all_total['Cash & Bank']['B']) : 0;
            // $cashBankPrev = round($cashBankPrev, 2);
            // $cashFlowTotal = round($cashBankCurrent - $cashBankPrev, 2);

            // $validation = round($final_all_total['Total Cash Flows'] - $cashFlowTotal, 2);


            // $spreadsheet->getActiveSheet()->getCell('B3')->setValue(convert_decimal_format($cashBankPrev))->getStyle('B3')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('B4')->setValue(convert_decimal_format($cashBankCurrent))->getStyle('B4')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('B5')->setValue(convert_decimal_format($cashFlowTotal))->getStyle('B5')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('B6')->setValue(convert_decimal_format($final_all_total['Cash Inflows from Operating Activity']))->getStyle('B6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('B7')->setValue(convert_decimal_format($final_all_total['Cash Inflows from Investing Activity']))->getStyle('B7')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('B8')->setValue(convert_decimal_format($final_all_total['Cash Inflows from Financing Activity']))->getStyle('B8')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('B9')->setValue(convert_decimal_format($validation))->getStyle('B9')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));

            $last_cell_index = $p_index - 1;
            $sheet->getStyle('A2:'.$end_cell . $last_cell_index)->applyFromArray($comman_all_border);


            if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            }

            if ($report_type == 'download') {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
                return response()->json([
                    'message' => 'Cashflow Report Generated Successfully!',
                    'status' => 'success',
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/cashflow-report/') . '/' . $rp_po_path . '/' . $documentFileName
                ], 200);

            } else {

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();


                return response()->json([
                    'message' => 'Cashflow Report Generated Successfully!',
                    'status' => 'success',
                    'table_html' => $html,
                ], 200);

            }


        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getOldCashflowReport( Request $request ){

        
        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by'); 
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            // $month = $request->get('month');            
            // $year = $request->get('year');         
             $month = '04';
             $year = '2025';
            $report_type = $request->get('report_type');         

            $currentMonth = date("Y-m", strtotime("$year-$month-01"));
            $previousMonth = date("Y-m", strtotime("-1 month", strtotime("$year-$month-01"))); 

            $cMonth = date( 'm',  strtotime($currentMonth) );
            $PvMonth = date( 'm',  strtotime($previousMonth) );
            
           
            $end_date = date("Y-m-t", strtotime("$year-$month-01"));
        
            $start_date = date("Y-m-d", strtotime("-1 month", strtotime("$year-$month-01"))); 
            $request->merge([
                'date_filter' => $start_date.' / '.$end_date
            ]);
            $fns_list = $this->getFNSReport( $request, 1 );
            $fns_final_all_total = [];
            foreach ( $fns_list as $key => $s_value ) {
                $first_col = $s_value['A'];
                unset($s_value['A']);
                $fns_final_all_total[$first_col] = $s_value;
            } 
            $pl_query = DB::table('trialbalance as tb')
            ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
            ->select(
                DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m') as month"), // group by year-month
                'ca.category',
                'ca.type',
                DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                DB::raw('SUM(tb.closing) as final_closing'),
            )
            ->whereIn('ca.type', [
                'cash_and_bank_balances','accounts_receivable', 'accounts_payable', 'closing_stock', 'fixed_assets',
                'secured_short_term_borrowings', 'secured_long_term_borrowings', 'unsecured_long_term_borrowings',
                'equity_share_capital', 'other_equity', 'reserve_surplus', 'current_year_profit',
                'current_investments', 'other_current_assets', 'branch', 'suspense',
                'non_current_investments', 'long_term_loans_advances', 'deferred_tax_assets',
                'other_non_current_assets', 'short_term_provisions', 'current_deferred_tax_liabilities',
                'other_current_liabilities', 'difference_in_opening_balance', 'long_term_provisions',
                'deferred_tax_liabilities', 'other_non_current_liabilities'
            ])
            ->where(function($query) use ($previousMonth, $currentMonth) {
                $query->where(DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m')"), $previousMonth)
                ->orWhere(DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m')"), $currentMonth);
            })
            ->groupBy(DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m')"), 'ca.category', 'ca.type')
            ->orderBy(DB::raw("DATE_FORMAT(tb.tbdate, '%Y-%m')"), 'asc');

             if ($isAdmin && $assignUserId != null ) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            
            $TrialBalanceList = $pl_query->get();

            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];

            $comman_trail_list = [];

            if( $TrialBalanceList ){
                foreach( $TrialBalanceList as $TrialBalanceItem ){
                    if( isset($TrialBalanceItem->month) && !empty($TrialBalanceItem->month) && !is_null($TrialBalanceItem->month) ){
                        $td_date = date( 'm', strtotime( $TrialBalanceItem->month ) );   
                        $comman_trail_list[$TrialBalanceItem->type][$td_date] = $TrialBalanceItem;
                    }
                }
            }   
           
            $column_width_list = [
                'A' => 300,
                'B' => 150,
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' =>  hg_comman_customer_header_style( 'bfbfbf' )
                ]                                
            ];    
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';        

            $local_path = $path = public_path().'/reports/cashflow-report/';

            $rp_po_path = 'cashflow-report';

            $path = $local_path.$rp_po_path.'/';

            if( !is_dir( $local_path )){
                mkdir( $local_path, 0777 );                
            }

            if( !is_dir( $path )){
                mkdir( $path, 0777 );                
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path.'-'.time().'-'.$user_id.".xlsx";

            $styleArray = array(
                'font'  => array(
                    'size'  => 10,                    
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,                    
                    'name' => 'Calibri'
                ],                
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if( $header_arr ){
                foreach( $header_arr as $header_index => $header_cell ){
                    $spreadsheet->getActiveSheet()->getCell( $header_index )->setValue( $header_cell['label'] )->getStyle( $header_index )->applyFromArray($header_cell['style']);                
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells( 'A1:B1' ); 
            $spreadsheet->getActiveSheet()->getCell( 'A1' )->setValue( 'CASH FLOW STATEMENT' )->getStyle( 'A1:B1' )->applyFromArray(hg_comman_customer_header_style( '#bfbfbf', 14, '000000' ));


            $spreadsheet->getActiveSheet()->getCell( 'A3' )->setValue( 'Cash at Beginning of Period' )->getStyle( 'A3' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'A4' )->setValue('Cash at End of Period')->getStyle( 'A4' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'A5' )->setValue('Cash Flows')->getStyle( 'A5' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'A6' )->setValue('Cash flows from Operating Activity')->getStyle( 'A6' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'A7' )->setValue('Cash flows from Investing Activity')->getStyle( 'A7' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'A8' )->setValue('Cash flows from Financing Activity')->getStyle( 'A8' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'A9' )->setValue('Validation')->getStyle( 'A9' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));        

            $spreadsheet->getActiveSheet()->getCell( 'A11' )->setValue('Particulars')->getStyle( 'A11' )->applyFromArray(hg_comman_customer_header_style( '#156082', 14, 'ffffff' ));    
            $spreadsheet->getActiveSheet()->getCell( 'B11' )->setValue('Price')->getStyle( 'B11' )->applyFromArray(hg_comman_customer_header_style( '#156082', 14, 'ffffff' ));    

            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(           
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font'  => [
                    'size'  => 11,     
                    'bold' => true,
                    'color' => ['argb' => '#000000' ],
                ],
                'fill' =>[
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(           
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font'  => [
                    'size'  => 11,     
                    'bold' => false,
                    'color' => ['argb' => '#000000' ],
                ],
                'fill' =>[
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if( $column_width_list ){
                foreach( $column_width_list as $col_key => $column_width ){
                    $spreadsheet->getActiveSheet()->getColumnDimension( $col_key )->setWidth( $column_width, 'px' );      

                }
            }

            $p_index = 12;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;


            /*$cashflow_data = array(
                array( 'label' => 'Trade Receivables', 'data_keys' => 'accounts_receivable', 'dr_cr' => -1 ),
                array( 'label' => 'Trade Payables', 'data_keys' => 'accounts_payable', 'dr_cr' => 1 ),
                array( 'label' => 'Inventory', 'data_keys' => 'closing_stock', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Operating Activity', 'sum_row' => ['Trade Receivables','Trade Payables','Inventory'] ),
                array( 'label' => 'Fixed Assets', 'data_keys' => 'fixed_assets', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Investing Activity', 'sum_row' => ['Fixed Assets'] ),
                array( 'label' => 'Equity', 'data_keys' => 'equity_share_capital,other_equity,reserve_surplus,current_year_profit', 'dr_cr' => 1 ),
                array( 'label' => 'Borrowings', 'data_keys' => 'secured_short_term_borrowings,secured_long_term_borrowings,unsecured_long_term_borrowings', 'dr_cr' => 1 ),
                array( 'label' => 'Others', 'data_keys' => 'current_investments,other_current_assets,branch,suspense,short_term_loans_advances,non_current_investments,long_term_loans_advances,deferred_tax_assets,other_non_current_assets,short_term_provisions,current_deferred_tax_liabilities,other_current_liabilities,difference_in_opening_balance,long_term_provisions,deferred_tax_liabilities,other_non_current_liabilities', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Financing Activity',  'sum_row' => ['Equity','Borrowings','Others'] ),
                array( 'label' => 'Total Cash Flows', 'sum_row' => ['Cash Inflows from Operating Activity','Cash Inflows from Investing Activity','Cash Inflows from Financing Activity'] ),
            );
           
            $final_all_total = [];
            foreach (  $cashflow_data as $key => $cash_value ) {
                
                if( isset($cash_value['data_keys']) ){
                    $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $cash_value['label'] )->getStyle( 'A'.$p_index )->applyFromArray($subitemStyle);
                    $data_keys = explode( ',', $cash_value['data_keys'] );
                    $pastMonthVal = $currentMonthVal = 0;                    
                    foreach ( $data_keys as $dkey => $data_val ) {
                        $currentMonthVal += isset($comman_trail_list[$data_val][$cMonth]->final_closing) ? (float)$comman_trail_list[$data_val][$cMonth]->final_closing : 0;
                        $pastMonthVal += isset($comman_trail_list[$data_val][$PvMonth]->final_closing) ? (float)$comman_trail_list[$data_val][$PvMonth]->final_closing : 0;
                    }              
                    $cell_val = round( ($currentMonthVal - $pastMonthVal),2);
                    $cell_val =    $cell_val * $cash_value['dr_cr'];                    
                    $final_all_total[ $cash_value['label'] ] = $cell_val;
                    $spreadsheet->getActiveSheet()->getCell( 'B'.$p_index )->setValue( convert_decimal_format($cell_val) )->getStyle( 'B'.$p_index )->applyFromArray($subitemStyle);
                }

                if( isset($cash_value['sum_row']) ){
                    $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $cash_value['label'] )->getStyle( 'A'.$p_index )->applyFromArray($itemStyle);
                    $sub_total = 0;
                    foreach ( $cash_value['sum_row'] as $s_key => $s_value ) {
                        $sub_total += isset($final_all_total[$s_value]) ? (float)$final_all_total[$s_value] : 0;
                    }
                    $final_all_total[ $cash_value['label'] ] = $sub_total;
                    $spreadsheet->getActiveSheet()->getCell( 'B'.$p_index )->setValue( convert_decimal_format($sub_total) )->getStyle( 'B'.$p_index )->applyFromArray($itemStyle);
                }   

                $p_index++;
            }*/
            
            $cashflow_data = array(
                array( 'label' => 'Trade Receivables', 'data_keys' => 'Accounts Receivable', 'dr_cr' => -1 ),
                array( 'label' => 'Trade Payables', 'data_keys' => 'Accounts Payable', 'dr_cr' => 1 ),
                array( 'label' => 'Inventory', 'data_keys' => 'Closing Stock', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Operating Activity', 'sum_row' => ['Trade Receivables','Trade Payables','Inventory'] ),
                array( 'label' => 'Fixed Assets', 'data_keys' => 'Fixed Assets', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Investing Activity', 'sum_row' => ['Fixed Assets'] ),
                array( 'label' => 'Equity', 'data_keys' => 'Equity', 'dr_cr' => 1 ),
                array( 'label' => 'Borrowings', 'data_keys' => 'Bank Loans - Current,Bank Loans - Non Current', 'dr_cr' => 1 ),
                array( 'label' => 'Others', 'data_plus_keys' => 'Other Non Current Liabilities,Other Current Liabilities', 'data_minus_keys' => 'Other Non Current Assets,Other Current Assets', 'dr_cr' => -1 ),
                array( 'label' => 'Cash Inflows from Financing Activity',  'sum_row' => ['Equity','Borrowings','Others'] ),
                array( 'label' => 'Total Cash Flows', 'sum_row' => ['Cash Inflows from Operating Activity','Cash Inflows from Investing Activity','Cash Inflows from Financing Activity'] ),
            );
          
            $final_all_total = [];
            foreach (  $cashflow_data as $key => $cash_value ) {
                
                if( isset($cash_value['data_keys']) || isset($cash_value['data_plus_keys']) ){
                    $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $cash_value['label'] )->getStyle( 'A'.$p_index )->applyFromArray($subitemStyle);
                   
             
                    if( $cash_value['label'] == 'Others' ){
                        
                        $data_minus_keys = explode( ',', $cash_value['data_minus_keys'] );
                        $data_plus_keys = explode( ',', $cash_value['data_plus_keys'] );
                        $pluspastMonthVal = $pluscurrentMonthVal = $minuspastMonthVal = $minuscurrentMonthVal = 0;  
                        foreach ( $data_minus_keys as $dkey => $data_val ) {
                            $minuscurrentMonthVal += isset($fns_final_all_total[$data_val]['C']) ? (float)str_replace(',','',$fns_final_all_total[$data_val]['C']) : 0;
                            $minuspastMonthVal += isset($fns_final_all_total[$data_val]['B']) ? (float)str_replace(',','',$fns_final_all_total[$data_val]['B']) : 0;
                        }
                        
                        foreach ( $data_plus_keys as $dkey => $pdata_val ) {
                            $pluscurrentMonthVal += isset($fns_final_all_total[$pdata_val]['C']) ? (float)str_replace(',','',$fns_final_all_total[$pdata_val]['C']) : 0;
                            $pluspastMonthVal += isset($fns_final_all_total[$pdata_val]['B']) ? (float)str_replace(',','',$fns_final_all_total[$pdata_val]['B']) : 0;
                        }
                        
                        $minus_cell_val = round( ($minuscurrentMonthVal - $minuspastMonthVal),2);
                        $minus_cell_val =  $minus_cell_val * $cash_value['dr_cr'];    
                        $plus_cell_val = round( ($pluscurrentMonthVal - $pluspastMonthVal),2);
                        $cell_val = $plus_cell_val + $minus_cell_val;
                       
                    }else{
                         $data_keys = explode( ',', $cash_value['data_keys'] );
                    $pastMonthVal = $currentMonthVal = 0;       
                        foreach ( $data_keys as $dkey => $data_val ) {
                            $currentMonthVal += isset($fns_final_all_total[$data_val]['C']) ? (float)str_replace(',','',$fns_final_all_total[$data_val]['C']) : 0;
                            $pastMonthVal += isset($fns_final_all_total[$data_val]['B']) ? (float)str_replace(',','',$fns_final_all_total[$data_val]['B']) : 0;
                        }
                        $cell_val = round( ($currentMonthVal - $pastMonthVal),2);
                        $cell_val =    $cell_val * $cash_value['dr_cr'];     
                    }      
                                  
                    $final_all_total[ $cash_value['label'] ] = $cell_val;
                    $spreadsheet->getActiveSheet()->getCell( 'B'.$p_index )->setValue( convert_decimal_format($cell_val) )->getStyle( 'B'.$p_index )->applyFromArray($subitemStyle);
                }

                if( isset($cash_value['sum_row']) ){
                    $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $cash_value['label'] )->getStyle( 'A'.$p_index )->applyFromArray($itemStyle);
                    $sub_total = 0;
                    foreach ( $cash_value['sum_row'] as $s_key => $s_value ) {
                        $sub_total += isset($final_all_total[$s_value]) ? (float)$final_all_total[$s_value] : 0;
                    }
                    $final_all_total[ $cash_value['label'] ] = $sub_total;
                    $spreadsheet->getActiveSheet()->getCell( 'B'.$p_index )->setValue( convert_decimal_format($sub_total) )->getStyle( 'B'.$p_index )->applyFromArray($itemStyle);
                }   

                $p_index++;
            }
            

            $cashBankCurrent = isset($fns_final_all_total['Cash & Bank']['C']) ? (float)str_replace(',','',$fns_final_all_total['Cash & Bank']['C']) : 0;
            $cashBankCurrent = round($cashBankCurrent,2);
            $cashBankPrev = isset($fns_final_all_total['Cash & Bank']['B']) ? (float)str_replace(',','',$fns_final_all_total['Cash & Bank']['B']) : 0;
            $cashBankPrev = round($cashBankPrev,2);
            $cashFlowTotal = round($cashBankCurrent - $cashBankPrev,2);

            $validation = round($final_all_total['Total Cash Flows'] - $cashFlowTotal, 2);


            $spreadsheet->getActiveSheet()->getCell( 'B3' )->setValue( convert_decimal_format( $cashBankPrev ) )->getStyle( 'B3' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'B4' )->setValue( convert_decimal_format( $cashBankCurrent ) )->getStyle( 'B4' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'B5' )->setValue( convert_decimal_format( $cashFlowTotal ) )->getStyle( 'B5' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'B6' )->setValue(convert_decimal_format( $final_all_total['Cash Inflows from Operating Activity'] ) )->getStyle( 'B6' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'B7' )->setValue(convert_decimal_format( $final_all_total['Cash Inflows from Investing Activity'] ) )->getStyle( 'B7' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'B8' )->setValue(convert_decimal_format( $final_all_total['Cash Inflows from Financing Activity'] ) )->getStyle( 'B8' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));
            $spreadsheet->getActiveSheet()->getCell( 'B9' )->setValue( convert_decimal_format( $validation ) )->getStyle( 'B9' )->applyFromArray(hg_comman_customer_header_style( '#00FF00', 14, '000000' ));                   

            $last_cell_index = $p_index - 1;   
            $sheet->getStyle('A11:B'.$last_cell_index )->applyFromArray($comman_all_border);

            if( $report_type == 'download' ){
                $writer = new Xlsx($spreadsheet);
                $writer->save( $path.$documentFileName );
                return response()->json([
                    'message' => 'Cashflow Report Generated Successfully!',
                    'status' => 'success',            
                    'filename' => $documentFileName,
                    'pdf' => url('/public/reports/cashflow-report/').'/'.$rp_po_path.'/'.$documentFileName
                ],200);

            }else{

                ob_start();
                $writer = new Html($spreadsheet);
                $writer->save('php://output');
                $html = ob_get_clean();


                return response()->json([
                    'message' => 'Cashflow Report Generated Successfully!',
                    'status' => 'success',            
                    'table_html' =>  $html,
                ],200);

            }
            

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function getBLReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $profit_lossdata = $this->getPLReport($request, 1);
            $retain_profit_arr = isset($profit_lossdata[26]) ? $profit_lossdata[26] : [];

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                    DB::raw('SUM(tb.closing) as final_closing'),
                )
                ->whereIn('ca.category', ['equity', 'liability', 'asset'])
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->final_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->final_closing;
                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->balancesheet_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            $qt_data_column_range = [];
            if (is_array($quater_grouped) && count($quater_grouped) > 0) {
                $qt_d_cnt = count($quater_grouped);
                $qt_d_cnt = $qt_d_cnt - 1;
                $quater_list_arr = array_values($quater_grouped);
                $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
                if ($quater_header_cell) {
                    $start_col = end($quater_header_cell);
                    foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
                        if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
                            $tb_header[$qtr_range_index] = [
                                'label' => $quater_list_arr[$qt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
                            $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $qtr_range_index),
                            );
                        }
                    }
                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            $year_data_column_range = [];
            if (is_array($year_grouped) && count($year_grouped) > 0) {
                $yt_d_cnt = count($year_grouped);
                $yt_d_cnt = $yt_d_cnt - 1;
                $year_list_arr = array_values($year_grouped);
                $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
                if ($year_header_cell) {
                    $start_col = end($year_header_cell);
                    foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
                        if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
                            $tb_header[$yt_range_index] = [
                                'label' => $year_list_arr[$yt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
                            $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $yt_range_index),
                            );
                        }
                    }
                }
            }


            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/bl-report/';

            $rp_po_path = 'bl-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Balance Sheet Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {

                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }

                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    if ($sub_items['pl'] == true) {
                                        $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_items['label']]['data']) && count($all_item_header_list[$tbdate_index][$sub_items['label']]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['data']) : 0;
                                    } else {
                                        $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) && count($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {
                                        $retain_total = isset($retain_profit_arr[$tbdate_column_group['column']]) ? str_replace(',', '', $retain_profit_arr[$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }

                                    $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }
                            if ($qt_data_column_range) {
                                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                    //$q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                                    $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    $q_cell_val = round($q_cell_val, 2);
                                    $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_1))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                    //$y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                                    $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                                    $y_cell_val = round($y_cell_val, 2);
                                    $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_1))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                }
                            }
                            $p_index++;
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($bl_key['data']['total']['label'])->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                $s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;
                                $s_cell_val = round($s_cell_val, 2);
                                $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if ($qt_data_column_range) {
                            foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                //$q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                                $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? $s_date_wise_sub_types[$qt_index][array_key_last($s_date_wise_sub_types[$qt_index])] : 0;
                                $q_cell_val = round($q_cell_val, 2);
                                $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_2))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                //$y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;
                                $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? $s_year_wise_sub_types[$r_yrt_index][array_key_last($s_year_wise_sub_types[$r_yrt_index])] : 0;
                                $y_cell_val = round($y_cell_val, 2);
                                $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_2))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                            }
                        }
                        $p_index++;
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                            $s_data = $final_items['items'];

                                            $operators = $final_items['operators'];
                                            foreach ($s_data as $i => $value) {
                                                $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val,$decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                if ($qt_data_column_range) {
                                    foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                        //$q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;
                                        $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? $f_date_wise_sub_types[$qt_index][array_key_last($f_date_wise_sub_types[$qt_index])] : 0;
                                        $q_cell_val = round($q_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_3))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                        //$y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                        $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? $f_year_wise_sub_types[$r_yrt_index][array_key_last($f_year_wise_sub_types[$r_yrt_index])] : 0;
                                        $y_cell_val = round($y_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_3))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                $p_index++;
                            }

                        }
                    }


                    $all_item_rows[] = 'A' . $p_index;
                }

                $all_item_rows[] = 'A' . $p_index;

            }


            if (is_array($all_item_rows) && count($all_item_rows) > 0) {
                foreach ($all_item_rows as $a_key => $a_value) {
                    $color = ($a_key % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                    $spreadsheet->getActiveSheet()
                        ->getStyle($a_value)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB($color);

                    $cnt_index = str_replace('A', '', $a_value);

                    if ($data_column_range) {
                        foreach ($data_column_range as $at_index => $at_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($at_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    if ($qt_data_column_range) {
                        foreach ($qt_data_column_range as $nn_qt_index => $nn_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($nn_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    if ($year_data_column_range) {
                        foreach ($year_data_column_range as $nn_syrt_index => $nn_syrt_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($nn_syrt_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                }
            }




            $last_cell_index = $p_index - 1;
            $sheet->getStyle('A3:' . $end_cell . $last_cell_index)->applyFromArray($comman_all_border);

            if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            } else {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
            }

            return response()->json([
                'message' => 'Balance Sheet Report Generated Successfully!',
                'status' => 'success',
                'filename' => $documentFileName,
                'pdf' => url('/public/reports/bl-report/') . '/' . $rp_po_path . '/' . $documentFileName
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getBLReportView(Request $request)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            /*$pl_query = DB::table('trialbalance as tb')
            ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')            
            ->leftjoin('chartaccount as ca1', 'ca.parent_id', '=', 'ca1.id')
            ->select(
                DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                'ca.category',
                'ca.type',
                'ca.sub_type',
                'ca.gl_name',
                'ca1.code as ca1_code',
                'ca1.gl_name as ca1_gl_name',                
                DB::raw('SUM(tb.closing - tb.opening) as total_closing'),
                DB::raw('SUM(tb.closing) as final_closing'),
            )
            ->whereIn('ca.category', ['equity','liability', 'asset'])
            ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type', 'gl_name')
            ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');*/


            $profit_lossdata = $this->getPLReport($request, 1);

            $retain_profit_arr = isset($profit_lossdata[26]) ? $profit_lossdata[26] : [];

            $pl_query = DB::table('trialbalance as tb')
                ->leftjoin('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->leftjoin('chartaccount as ca1', 'ca.parent_id', '=', 'ca1.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.code',
                    'ca.type',
                    'ca.sub_type',
                    'ca.gl_name',
                    'ca1.code as ca1_code',
                    'ca1.gl_name as ca1_gl_name',
                    'ca1.parent_id as ca1_parent_id',
                    'ca.parent_id',
                    'ca.id as main_id',
                    DB::raw('tb.closing - tb.opening as total_closing'),
                    DB::raw('tb.closing as final_closing'),
                )
                ->whereIn('ca.category', ['equity', 'liability', 'asset'])
                //->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type', 'gl_name')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $parent_id_list = [];
            $all_balance_sheet_names = $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = $sub_all_item_header_list = [];
            $all_parent_items = $balance_sheet_names = $all_chart_acc_ids = [];
            if ($TrialBalanceList->count() > 0) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_closing;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->final_closing;

                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->final_closing;

                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['label'] = $TrialBalanceItem->sub_type;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['data'][] = $TrialBalanceItem->total_closing;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['closing_data'][] = $TrialBalanceItem->final_closing;
                        $balance_sheet_names[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name][$TrialBalanceItem->tbdate][] = $TrialBalanceItem;

                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['data'][] = $TrialBalanceItem->total_closing;
                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['closing_data'][] = $TrialBalanceItem->final_closing;

                        $first_item = [
                            'id' => $TrialBalanceItem->main_id,
                            'code' => $TrialBalanceItem->main_id,
                            'gl_name' => $TrialBalanceItem->gl_name,
                            'parent_id' => $TrialBalanceItem->parent_id,
                            'category' => $TrialBalanceItem->category,
                            'type' => $TrialBalanceItem->type,
                        ];

                        $all_chart_acc_ids[$TrialBalanceItem->main_id] = $first_item;
                        $all_parent_items[$TrialBalanceItem->main_id] = $first_item;
                        if ($TrialBalanceItem->parent_id > 0) {
                            $second_item = [
                                'id' => $TrialBalanceItem->parent_id,
                                'code' => $TrialBalanceItem->ca1_code,
                                'gl_name' => $TrialBalanceItem->ca1_gl_name,
                                'parent_id' => $TrialBalanceItem->ca1_parent_id,
                                'category' => $TrialBalanceItem->category,
                                'type' => $TrialBalanceItem->type,
                            ];
                            $all_chart_acc_ids[$TrialBalanceItem->parent_id] = $second_item;
                            $all_parent_items[$TrialBalanceItem->parent_id] = $second_item;
                            if (is_array($parent_id_list) && $TrialBalanceItem->ca1_parent_id > 0 && !in_array($TrialBalanceItem->ca1_parent_id, $parent_id_list)) {
                                $parent_id_list[] = $TrialBalanceItem->ca1_parent_id;
                            }
                        }
                    }
                }
            }
            if (is_array($parent_id_list) && count($parent_id_list) > 0) {
                $all_parent_items = get_all_parent_list($parent_id_list, $all_parent_items);
            }

            $all_items = get_data_with_parent_child_rel($all_parent_items);

            $category_wise_all_items = get_data_with_category_wise($all_items);
            
            $finacial_titles = comman_finacial_column_title($all_dates);

            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];


            $pl_balance_items = $this->balancesheet_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            $qt_data_column_range = [];
            if (is_array($quater_grouped) && count($quater_grouped) > 0) {
                $qt_d_cnt = count($quater_grouped);
                $qt_d_cnt = $qt_d_cnt - 1;
                $quater_list_arr = array_values($quater_grouped);
                $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
                if ($quater_header_cell) {
                    $start_col = end($quater_header_cell);
                    foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
                        if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
                            $tb_header[$qtr_range_index] = [
                                'label' => $quater_list_arr[$qt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
                            $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $qtr_range_index),
                            );
                        }
                    }
                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            $year_data_column_range = [];
            if (is_array($year_grouped) && count($year_grouped) > 0) {
                $yt_d_cnt = count($year_grouped);
                $yt_d_cnt = $yt_d_cnt - 1;
                $year_list_arr = array_values($year_grouped);
                $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
                if ($year_header_cell) {
                    $start_col = end($year_header_cell);
                    foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
                        if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
                            $tb_header[$yt_range_index] = [
                                'label' => $year_list_arr[$yt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
                            $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $yt_range_index),
                            );
                        }
                    }
                }
            }



            $header_arr = array_merge($header_arr, $tb_header);

            $p_index = 4;

            $table_header = '';
            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $table_header .= '<th>' . $header_cell['label'] . '</th>';
                }
            }
            $table_header = '<thead>' . $table_header . '</thead>';
            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            $table_row_html = '';
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {

                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }

                            // $sub_label_str = generateTableRowHtml( $balance_sheet_names, $sub_items['label'], $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $sub_all_item_header_list, $month_wise_grouped, $year_wise_grouped );

                            $sub_label_str = setTableDataWithChildParent($all_balance_sheet_names, $category_wise_all_items, $sub_items['label'], $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $sub_all_item_header_list, $month_wise_grouped, $year_wise_grouped);

                            $extra_rows = $sub_items['label'] . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a>';
                            if ($sub_label_str) {
                                $extra_rows = '<a href="javascript:;" class="extra-row-btn main-tr-btn" data-key="' . $sub_key . '"><i class="ri-add-circle-line"></i>' . $sub_items['label'] . '</a>' . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a>';
                            }

                            $table_row_html .= '<tr>';
                            $table_row_html .= '<td>' . $extra_rows . '</td>';
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {
                                    if ($sub_items['pl'] == true) {
                                        $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_items['label']]['data']) && count($all_item_header_list[$tbdate_index][$sub_items['label']]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['data']) : 0;
                                    } else {
                                        $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) && count($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                    }
                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {
                                        $retain_total = isset($retain_profit_arr[$tbdate_column_group['column']]) ? str_replace(',', '', $retain_profit_arr[$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
                                    }

                                    $table_row_html .= '<td>' . convert_decimal_format($cell_val,$decimal_1) . '</td>';
                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;
                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }
                            if ($qt_data_column_range) {
                                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                    //$q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                                    $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    $q_cell_val = round($q_cell_val, 2);
                                    $table_row_html .= '<td>' . convert_decimal_format($q_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                    //$y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                                    $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                                    $y_cell_val = round($y_cell_val, 2);
                                    $table_row_html .= '<td>' . convert_decimal_format($y_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            $table_row_html .= '</tr>';



                            if ($sub_label_str) {
                                $table_row_html .= $sub_label_str;
                            }
                            $p_index++;
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {


                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        $table_row_html .= '<tr>';
                        if ($bl_key['data']['total']['bold'] == true) {
                            $table_row_html .= '<td><b>' . $bl_key['data']['total']['label'] . '<b></td>';
                        } else {
                            $table_row_html .= '<td>' . $bl_key['data']['total']['label'] . '</td>';
                        }

                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                $s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;

                                //$s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? $sub_total_date_wise_arr[$s_tbdate_column_group['column']][array_key_last($sub_total_date_wise_arr[$s_tbdate_column_group['column']])] : 0;

                                $s_cell_val = round($s_cell_val, 2);

                                if ($bl_key['data']['total']['bold'] == true) {
                                    $table_row_html .= '<td><b>' . convert_decimal_format($s_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $table_row_html .= '<td>' . convert_decimal_format($s_cell_val,$decimal_2) . '</td>';
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if ($qt_data_column_range) {
                            foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                //$q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                                $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? $s_date_wise_sub_types[$qt_index][array_key_last($s_date_wise_sub_types[$qt_index])] : 0;

                                $q_cell_val = round($q_cell_val, 2);
                                if ($bl_key['data']['total']['bold'] == true) {
                                    $table_row_html .= '<td><b>' . convert_decimal_format($q_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $table_row_html .= '<td>' . convert_decimal_format($q_cell_val,$decimal_2) . '</td>';
                                }
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                //$y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;

                                $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? $s_year_wise_sub_types[$r_yrt_index][array_key_last($s_year_wise_sub_types[$r_yrt_index])] : 0;

                                $y_cell_val = round($y_cell_val, 2);

                                if ($bl_key['data']['total']['bold'] == true) {
                                    $table_row_html .= '<td><b>' . convert_decimal_format($y_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $table_row_html .= '<td>' . convert_decimal_format($y_cell_val,$decimal_2) . '</td>';
                                }
                            }
                        }
                        $table_row_html .= '</tr>';
                        $p_index++;
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];

                                $table_row_html .= '<tr>';
                                if ($final_items['bold'] == true) {
                                    $table_row_html .= '<td><b>' . $final_items['label'] . '<b></td>';
                                } else {
                                    $table_row_html .= '<td>' . $final_items['label'] . '</td>';
                                }


                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                            $s_data = $final_items['items'];

                                            $operators = $final_items['operators'];
                                            foreach ($s_data as $i => $value) {
                                                $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);

                                        if ($final_items['bold'] == true) {
                                            $table_row_html .= '<td><b>' . convert_decimal_format($n_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $table_row_html .= '<td>' . convert_decimal_format($n_cell_val,$decimal_3) . '</td>';
                                        }

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                if ($qt_data_column_range) {
                                    foreach ($qt_data_column_range as $qt_index => $qt_column_group) {

                                        //$q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;                                        
                                        $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? $f_date_wise_sub_types[$qt_index][array_key_last($f_date_wise_sub_types[$qt_index])] : 0;
                                        $q_cell_val = round($q_cell_val, 2);

                                        if ($final_items['bold'] == true) {
                                            $table_row_html .= '<td><b>' . convert_decimal_format($q_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $table_row_html .= '<td>' . convert_decimal_format($q_cell_val,$decimal_3) . '</td>';
                                        }

                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                        //$y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                        $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? $f_year_wise_sub_types[$r_yrt_index][array_key_last($f_year_wise_sub_types[$r_yrt_index])] : 0;
                                        $y_cell_val = round($y_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $table_row_html .= '<td><b>' . convert_decimal_format($y_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $table_row_html .= '<td>' . convert_decimal_format($y_cell_val,$decimal_3) . '</td>';
                                        }

                                    }
                                }
                                $table_row_html .= '</tr>';
                                $p_index++;
                            }

                        }
                    }


                    $all_item_rows[] = 'A' . $p_index;
                }

                $all_item_rows[] = 'A' . $p_index;

            }
            if ($table_row_html) {
                $table_row_html = '<tbody>' . $table_row_html . '</tbody>';
            } else {
                $table_row_html = '<tbody><tr><td>No data found</td></tr></tbody>';
            }

            $table_html = '<table class="table bordered-table mb-0">' . $table_header . $table_row_html . '</table>';


            return response()->json([
                'message' => 'Balance Sheet View Generated Successfully!',
                'status' => 'success',
                'table_html' => $table_html,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getPLReportView(Request $request)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            /*$pl_query = DB::table('trialbalance as tb')
            ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
            ->select(
                DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                'ca.category',
                'ca.type',
                'ca.sub_type',
                'ca.gl_name',
                DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
            )
            ->whereIn('ca.category', ['income','expense'])
            ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type', 'ca.gl_name' )
            ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');*/


            $pl_query = DB::table('trialbalance as tb')
                ->leftjoin('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->leftjoin('chartaccount as ca1', 'ca.parent_id', '=', 'ca1.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.code',
                    'ca.type',
                    'ca.sub_type',
                    'ca.gl_name',
                    'ca1.code as ca1_code',
                    'ca1.gl_name as ca1_gl_name',
                    'ca1.parent_id as ca1_parent_id',
                    'ca.parent_id',
                    'ca.id as main_id',
                    DB::raw('tb.credit - tb.debit as total_income'),
                    DB::raw('tb.closing as final_closing'),
                    DB::raw('tb.debit - tb.credit as total_expense'),
                )
                ->whereIn('ca.category', ['income', 'expense', 'asset'])
                // ->Where(function ($q) {
                //      $q->where('ca.type', '=', 'opening_stock');
                //  })
                //->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type', 'gl_name')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');


            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();


            $parent_id_list = $all_parent_items = $all_chart_acc_ids = $all_balance_sheet_names = $sub_all_item_header_list = $balance_sheet_names = $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['label'] = $TrialBalanceItem->sub_type;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['data'][] = $TrialBalanceItem->total_expense;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['closing_data'][] = $TrialBalanceItem->total_income;
                        $sub_all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name]['final_closing'][] = $TrialBalanceItem->final_closing;
                        $balance_sheet_names[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]][$TrialBalanceItem->gl_name][$TrialBalanceItem->tbdate][] = $TrialBalanceItem;

                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['data'][] = $TrialBalanceItem->total_expense;
                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['closing_data'][] = $TrialBalanceItem->total_income;
                        $all_balance_sheet_names[$TrialBalanceItem->main_id][$TrialBalanceItem->tbdate]['final_closing'][] = $TrialBalanceItem->final_closing;

                        $first_item = [
                            'id' => $TrialBalanceItem->main_id,
                            'code' => $TrialBalanceItem->main_id,
                            'gl_name' => $TrialBalanceItem->gl_name,
                            'parent_id' => $TrialBalanceItem->parent_id,
                            'category' => $TrialBalanceItem->category,
                            'type' => $TrialBalanceItem->type,
                        ];

                        $all_chart_acc_ids[$TrialBalanceItem->main_id] = $first_item;
                        $all_parent_items[$TrialBalanceItem->main_id] = $first_item;
                        if ($TrialBalanceItem->parent_id > 0) {
                            $second_item = [
                                'id' => $TrialBalanceItem->parent_id,
                                'code' => $TrialBalanceItem->ca1_code,
                                'gl_name' => $TrialBalanceItem->ca1_gl_name,
                                'parent_id' => $TrialBalanceItem->ca1_parent_id,
                                'category' => $TrialBalanceItem->category,
                                'type' => $TrialBalanceItem->type,
                            ];
                            $all_chart_acc_ids[$TrialBalanceItem->parent_id] = $second_item;
                            $all_parent_items[$TrialBalanceItem->parent_id] = $second_item;

                            if (is_array($parent_id_list) && $TrialBalanceItem->ca1_parent_id > 0 && !in_array($TrialBalanceItem->ca1_parent_id, $parent_id_list)) {
                                $parent_id_list[] = $TrialBalanceItem->ca1_parent_id;
                            }
                        }
                    }
                }
            }

            if (is_array($parent_id_list) && count($parent_id_list) > 0) {
                $all_parent_items = get_all_parent_list($parent_id_list, $all_parent_items);
            }

            $all_items = get_data_with_parent_child_rel($all_parent_items);
            $category_wise_all_items = get_data_with_category_wise($all_items);

            $finacial_titles = comman_finacial_column_title($all_dates);            
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->profitloss_report_list;

            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            $qt_data_column_range = [];
            if (is_array($quater_grouped) && count($quater_grouped) > 0) {
                $qt_d_cnt = count($quater_grouped);
                $qt_d_cnt = $qt_d_cnt - 1;
                $quater_list_arr = array_values($quater_grouped);
                $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
                if ($quater_header_cell) {
                    $start_col = end($quater_header_cell);
                    foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
                        if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
                            $tb_header[$qtr_range_index] = [
                                'label' => $quater_list_arr[$qt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
                            $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $qtr_range_index),
                            );
                        }
                    }
                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            $year_data_column_range = [];
            if (is_array($year_grouped) && count($year_grouped) > 0) {
                $yt_d_cnt = count($year_grouped);
                $yt_d_cnt = $yt_d_cnt - 1;
                $year_list_arr = array_values($year_grouped);
                $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
                if ($year_header_cell) {
                    $start_col = end($year_header_cell);
                    foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
                        if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
                            $tb_header[$yt_range_index] = [
                                'label' => $year_list_arr[$yt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
                            $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $yt_range_index),
                            );
                        }
                    }
                }
            }

            $header_arr = array_merge($header_arr, $tb_header);

            $table_header = '';
            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $table_header .= '<th>' . $header_cell['label'] . '</th>';
                }
            }
            $table_header = '<thead>' . $table_header . '</thead>';
            $p_index = 4;

            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;
            $table_row_html = '';
            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];

                    $first_tr_item = $second_tr_item = $third_tr_item = '';

                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {

                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }

                            //$sub_label_str = generateTableRowHtml( $balance_sheet_names, $sub_items['label'], $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $sub_all_item_header_list, $month_wise_grouped, $year_wise_grouped );

                            $sub_label_str = setTableDataWithChildParent($all_balance_sheet_names, $category_wise_all_items, $sub_items['label'], $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $sub_all_item_header_list, $month_wise_grouped, $year_wise_grouped);

                            $extra_rows = $sub_items['label'] . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a>';
                            if ($sub_label_str) {
                                $extra_rows = '<a href="javascript:;" class="extra-row-btn ' . (!isset($sub_items['sub_row']) ? 'main-tr-btn' : '') . '" data-key="' . $sub_key . '"><i class="ri-add-circle-line"></i>' . $sub_items['label'] . '</a>' . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a>';
                            }
                            $parent_tr_hide = isset($sub_items['parent_tr_hide']) ? 'hide-his-tr' : '';
                            $item_css = '';
                            if (isset($sub_items['sub_row'])) {
                                $first_tr_item .= '<tr class="sub-row ' . $parent_tr_hide . '" data-label="' . $sub_items['sub_row'] . '" data-main-key="' . $sub_items['sub_row'] . '" >';
                                $item_css = 'style="padding-left:60px !important;"';
                            } else {

                                $first_tr_item .= '<tr class="' . $parent_tr_hide . '" >';
                            }
                            $first_tr_item .= '<td ' . $item_css . '>' . $extra_rows . '</td>';

                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];

                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {
                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];

                                    if (isset($sub_items['r_type']) && $sub_items['r_type'] == 'bl') {
                                        $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                    } else {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);
                                    $first_tr_item .= '<td>' . convert_decimal_format($cell_val,$decimal_1) . '</td>';
                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;
                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }

                            $bl_flag = 0;

                            if( isset($sub_items['bl_rule']) && $sub_items['bl_rule'] == true ){
                                $bl_flag = 1;
                            }

                            if ($qt_data_column_range) {
                                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                    if ( $bl_flag == 1 ) {
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    }else{
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                                    }

                                    $q_cell_val = round($q_cell_val, 2);
                                    $first_tr_item .= '<td>' . convert_decimal_format($q_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                    if ( $bl_flag == 1 ) {
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                                    }else{
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                                    }
                                    $y_cell_val = round($y_cell_val, 2);
                                    $first_tr_item .= '<td>' . convert_decimal_format($y_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            $first_tr_item .= '</tr>';

                            if ($sub_label_str) {
                                $first_tr_item .= $sub_label_str;
                            }
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {

                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];

                        $hide_tr = isset($bl_key['data']['total']['hide_tr']) ? 'style="display:none !important;"' : '';
                        $main_row_cls = isset($bl_key['data']['total']['main_row']) ? 'main-tr-inner' : '';

                        $second_tr_item .= '<tr ' . $hide_tr . ' class="' . $main_row_cls . '" data-key="' . (isset($bl_key['data']['total']['main_row']) ? $bl_key['data']['total']['main_row'] : '') . '">';
                        $total_extra_rows = '';

                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }

                        if (isset($bl_key['data']['total']['main_row'])) {
                            $second_tr_item .= '<td><a href="javascript:;" class="extra-row-btn main-tr-btn" data-key="' . $bl_key['data']['total']['main_row'] . '"><i class="ri-add-circle-line"></i>' . $total_label . '</a>' . '<a class="show-acc-edit" href="/chartaccount?group_value=' . replace_space_to_dash($sub_items['label']) . '" target="_blank"><i class="ri-pencil-line"></i></a></td>';
                        } else {
                            if ($bl_key['data']['total']['bold'] == true) {
                                $second_tr_item .= '<td><b>' . $total_label . '<b></td>';
                            } else {
                                $second_tr_item .= '<td>' . $total_label . '</td>';
                            }
                        }


                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {


                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if ($bl_key['data']['total']['bold'] == true) {
                                    $second_tr_item .= '<td><b>' . convert_decimal_format($s_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $second_tr_item .= '<td>' . convert_decimal_format($s_cell_val,$decimal_2) . '</td>';
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if ($qt_data_column_range) {
                            foreach ($qt_data_column_range as $qt_index => $qt_column_group) {                                
                                $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                                $q_cell_val = round($q_cell_val, 2);
                                if ($bl_key['data']['total']['bold'] == true) {
                                    $second_tr_item .= '<td><b>' . convert_decimal_format($q_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $second_tr_item .= '<td>' . convert_decimal_format($q_cell_val,$decimal_2) . '</td>';
                                }
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);                                
                                $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;
                                $y_cell_val = round($y_cell_val, 2);
                                if ($bl_key['data']['total']['bold'] == true) {
                                    $second_tr_item .= '<td><b>' . convert_decimal_format($y_cell_val,$decimal_2) . '<b></td>';
                                } else {
                                    $second_tr_item .= '<td>' . convert_decimal_format($y_cell_val,$decimal_2) . '</td>';
                                }
                            }
                        }
                        $second_tr_item .= '</tr>';
                        $p_index++;
                    }

                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];

                                $third_tr_item .= '<tr>';
                                if ($final_items['bold'] == true) {
                                    $third_tr_item .= '<td><b>' . $final_items['label'] . '<b></td>';
                                } else {
                                    $third_tr_item .= '<td>' . $final_items['label'] . '</td>';
                                }

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $third_tr_item .= '<td><b>' . convert_decimal_format($n_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $third_tr_item .= '<td>' . convert_decimal_format($n_cell_val,$decimal_3) . '</td>';
                                        }
                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                if ($qt_data_column_range) {
                                    foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                        
                                        $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;
                                        $q_cell_val = round($q_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $third_tr_item .= '<td><b>' . convert_decimal_format($q_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $third_tr_item .= '<td>' . convert_decimal_format($q_cell_val,$decimal_3) . '</td>';
                                        }
                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);                                        
                                        $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                        $y_cell_val = round($y_cell_val, 2);
                                        if ($final_items['bold'] == true) {
                                            $third_tr_item .= '<td><b>' . convert_decimal_format($y_cell_val,$decimal_3) . '<b></td>';
                                        } else {
                                            $third_tr_item .= '<td>' . convert_decimal_format($y_cell_val,$decimal_3) . '</td>';
                                        }
                                    }
                                }
                                $third_tr_item .= '</tr>';
                                $p_index++;
                            }

                        }
                    }
                    if (isset($bl_key['data']['total']['main_row'])) {
                        $table_row_html .= $second_tr_item . $first_tr_item . $third_tr_item;
                    } else {
                        $table_row_html .= $first_tr_item . $second_tr_item . $third_tr_item;
                    }
                    $all_item_rows[] = 'A' . $p_index;
                }

                $all_item_rows[] = 'A' . $p_index;

            }

            if ($table_row_html) {
                $table_row_html = '<tbody>' . $table_row_html . '</tbody>';
            } else {
                $table_row_html = '<tbody><tr><td>No data found</td></tr></tbody>';
            }

            $table_html = '<table class="table bordered-table mb-0">' . $table_header . $table_row_html . '</table>';

            return response()->json([
                'message' => 'Profit & Loss View Generated Successfully!',
                'status' => 'success',
                'table_html' => $table_html,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function getAIPLReport(Request $request)
    {
        $profit_lossdata = $this->getPLReport($request, 1);
        $pl_header_list = [];
        $pl_data_list = [];
        if (is_array($profit_lossdata) && count($profit_lossdata) > 0) {
            $pl_inner_header_list = $profit_lossdata[3];
            foreach ($pl_inner_header_list as $item_key => $item_value) {
                if (is_null($item_value) || $item_value == '' || $item_key == 'A') {
                    continue;
                }
                foreach ($profit_lossdata as $sub_key => $sub_value) {
                    if ($sub_key > 3 && isset($sub_value[$item_key])) {
                        $pl_data_list[$item_value][] = array('label' => $sub_value['A'], 'data' => $sub_value[$item_key]);
                    }
                }
            }
        }

        $fileName = 'ai_pl_report_' . time() . '.pdf';
        $local_path = $path = public_path() . '/reports/pl-report/ai/';
        if (!file_exists($local_path)) {
            mkdir($local_path, 0775, true);
        }
        $filePath = $local_path . DIRECTORY_SEPARATOR . $fileName;
        $messages = [
            ['role' => 'system', 'content' => 'You are a financial analyst.'],
            [
                'role' => 'user',
                'content' => "Here is Profit & Loss data in JSON format:\n" . json_encode($pl_data_list) .
                    "\nPlease provide a summary of Executive Summary, Profitability Analysis, Liquidity & Solvency Analysis, Efficiency Ratios, Benchmark Comparison (specifying source), and Actionable Recommendations and i need response with html format so automatically show in my pdf also i don't need html and body i need only with reponse with need his own tags not <response> tag"
            ],
        ];
        try {
            $resp = $this->deepseek->chat($messages, 'deepseek-chat');
            $content = data_get($resp, 'choices.0.message.content', null);
            $this->pdf->generateFinancialReport($content, $filePath, 'Profit & Loss Report');
            return response()->json([
                'status' => 'success',

                'filename' => $fileName,
                'pdf' => url("/public/reports/pl-report/ai/{$fileName}"),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAIBLReport(Request $request)
    {
        $bl_sheetdata = $this->getBLReport($request, 1);

        $bl_header_list = [];
        $bl_data_list = [];
        if (is_array($bl_sheetdata) && count($bl_sheetdata) > 0) {
            $bl_inner_header_list = $bl_sheetdata[3];
            foreach ($bl_inner_header_list as $item_key => $item_value) {
                if (is_null($item_value) || $item_value == '' || $item_key == 'A') {
                    continue;
                }
                foreach ($bl_sheetdata as $sub_key => $sub_value) {
                    if ($sub_key > 3 && isset($sub_value[$item_key])) {
                        $bl_data_list[$item_value][] = array('label' => $sub_value['A'], 'data' => $sub_value[$item_key]);
                    }
                }
            }
        }

        $styleVariants = [
            "Write insights under every sub-heading individually. Each sub-heading must have its own paragraph or bullet list.",
            "Do NOT write per sub-heading. Instead, combine insights of all sub-headings into a single concise paragraph for each main section.",
            "For some sub-headings provide standalone insights, while others can be merged into a combined paragraph. Choose naturally based on relevance.",
            "Present narrative discussion for each main section summarizing sub-heading content together, but occasionally highlight important sub-headings separately."
        ];

        $fileName = 'ai_bl_report_' . time() . '.pdf';
        $local_path = $path = public_path() . '/reports/bl-report/ai/';
        if (!file_exists($local_path)) {
            mkdir($local_path, 0775, true);
        }
        $filePath = $local_path . DIRECTORY_SEPARATOR . $fileName;
        $messages = [
            ['role' => 'system', 'content' => 'You are a financial analyst specializing in interpreting balance sheet data and presenting insights in structured HTML. The response must ONLY be HTML (no <html> or <body> wrapper).'],
            [
                'role' => 'user',
                'content' => "
                            Here is Balance Sheet data in JSON format: " . json_encode($bl_data_list) . "

                            Generate a financial analysis report strictly following the titles + sub-headings below.
                            Provide separate content under every sub-heading. Do not merge sub-headings.
                            Use financial formulas wherever possible.

                            RULES:
                            - Always produce content under every sub-heading.
                            - If metric can’t be computed → write 'Data unavailable'.
                            - Use any HTML tags.
                            - Do not include <html> or <body>.
                            - Prioritize latest month → else next available.

                            ================ FIXED TITLES & SUB-HEADINGS ================

                            1. Liquidity Analysis
                                - Current Ratio trending and alerts
                                - Quick Ratio / Acid Test trending
                                - Working Capital adequacy analysis
                                - Cash position and runway analysis
                                - Net Working Capital changes and drivers
                                - Liquidity coverage ratio
                                - Cash conversion efficiency

                            2. Leverage & Solvency Analysis
                                - Debt-to-Equity ratio trending
                                - Debt-to-Capital ratio analysis
                                - Total Debt composition (secured vs unsecured)
                                - Debt maturity profile
                                - Interest coverage ratio
                                - Debt service coverage ratio
                                - Fixed charge coverage ratio
                                - Solvency stress testing

                            3. Asset Efficiency Analysis
                                - Asset Turnover ratios (total, fixed, current)
                                - Fixed Asset utilization trends
                                - Asset age & depreciation analysis
                                - Capital intensity metrics
                                - Asset productivity per rupee invested
                                - Non-current vs current asset mix
                                - Asset quality indicators

                            4. Capital Structure Analysis
                                - Equity composition and changes
                                - Reserves & Surplus movements
                                - Retained earnings patterns
                                - Capital adequacy metrics
                                - Optimal capital structure comparison
                                - Cost of capital analysis

                            5. Balance Sheet Quality Metrics
                                - Asset-to-Liability ratios
                                - Equity multiplier
                                - Financial leverage index
                                - Balance sheet strength score
                                - Red flags identification (negative equity, excessive debt, etc.)

                            ======================================================
                            "
            ],
        ];
        try {
            $resp = $this->deepseek->chat($messages, 'deepseek-chat');
            $content = data_get($resp, 'choices.0.message.content', null);
            $this->pdf->generateFinancialReport($content, $filePath, 'Balance Sheet Report');
            return response()->json([
                'status' => 'success',

                'filename' => $fileName,
                'pdf' => url("/public/reports/bl-report/ai/{$fileName}"),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPLReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = DB::table('trialbalance as tb')
                ->join('chartaccount as ca', 'tb.account_id', '=', 'ca.id')
                ->select(
                    DB::raw("LAST_DAY(tb.tbdate) as tbdate"),
                    'ca.category',
                    'ca.type',
                    'ca.sub_type',
                    DB::raw('SUM(tb.closing) as final_closing'),
                    DB::raw('SUM(tb.credit - tb.debit) as total_income'),
                    DB::raw('SUM(tb.debit - tb.credit) as total_expense'),
                )
                ->whereIn('ca.category', ['income', 'expense', 'asset'])
                ->groupBy(DB::raw("LAST_DAY(tb.tbdate)"), 'ca.category', 'ca.type', 'ca.sub_type')
                ->orderBy(DB::raw("LAST_DAY(tb.tbdate)"), 'asc');

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('tb.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('tb.user_id', $userId);
            }
            $all_dates = [];
            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthEndDates($start_date, $end_date);
                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tb.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $TrialBalanceList = $pl_query->get();
            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];
            if ($TrialBalanceList) {
                foreach ($TrialBalanceList as $TrialBalanceItem) {
                    if (isset($TrialBalanceItem->tbdate) && !empty($TrialBalanceItem->tbdate) && !is_null($TrialBalanceItem->tbdate)) {
                        $td_date = date('m/d/Y', strtotime($TrialBalanceItem->tbdate));
                        $tbdate_list[$td_date][] = $TrialBalanceItem;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;
                        $item_header_list[$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['label'] = $TrialBalanceItem->sub_type;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['data'][] = $TrialBalanceItem->total_expense;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['closing_data'][] = $TrialBalanceItem->total_income;
                        $all_item_header_list[$TrialBalanceItem->tbdate][$this->balance_sheet_list_keys[$TrialBalanceItem->category . '||' . $TrialBalanceItem->type]]['final_closing'][] = $TrialBalanceItem->final_closing;
                    }
                }
            }

            $finacial_titles = comman_finacial_column_title($all_dates);
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $pl_balance_items = $this->profitloss_report_list;
            $column_width_list = [
                'A' => 200
            ];
            $header_arr = [
                'A3' => [
                    'label' => 'Particulars',
                    'style' => hg_comman_customer_header_style('bfbfbf')
                ]
            ];
            $data_column_range = $tb_header = [];

            $start_row = 3;
            $start_col = 'B';

            if ($TrialBalanceList) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tb_item->tbdate));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tb_item->tbdate] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            $qt_data_column_range = [];
            if (is_array($quater_grouped) && count($quater_grouped) > 0) {
                $qt_d_cnt = count($quater_grouped);
                $qt_d_cnt = $qt_d_cnt - 1;
                $quater_list_arr = array_values($quater_grouped);
                $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
                if ($quater_header_cell) {
                    $start_col = end($quater_header_cell);
                    foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
                        if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
                            $tb_header[$qtr_range_index] = [
                                'label' => $quater_list_arr[$qt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
                            $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $qtr_range_index),
                            );
                        }
                    }
                }
            }

            $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            $year_data_column_range = [];
            if (is_array($year_grouped) && count($year_grouped) > 0) {
                $yt_d_cnt = count($year_grouped);
                $yt_d_cnt = $yt_d_cnt - 1;
                $year_list_arr = array_values($year_grouped);
                $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
                if ($year_header_cell) {
                    $start_col = end($year_header_cell);
                    foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
                        if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
                            $tb_header[$yt_range_index] = [
                                'label' => $year_list_arr[$yt_header_index]['label'],
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
                            $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
                                'column' => str_replace($start_row, '', $yt_range_index),
                            );
                        }
                    }
                }
            }


            $end_cell = str_replace($start_row, '', $start_col);

            $header_arr = array_merge($header_arr, $tb_header);

            $local_path = $path = public_path() . '/reports/pl-report/';

            $rp_po_path = 'pl-report';

            $path = $local_path . $rp_po_path . '/';

            if (!is_dir($local_path)) {
                mkdir($local_path, 0777);
            }

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $user_id = auth()->user()->id;
            $documentFileName = $rp_po_path . '-' . time() . '-' . $user_id . ".xlsx";

            $styleArray = array(
                'font' => array(
                    'size' => 10,
                )
            );
            $comman_outline_border = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'indent' => 5,

                ],
            );
            $comman_bottom_border = array(
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $comman_all_border = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
            );

            $allborder = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '000000'),
                    ),
                ),
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],

            );


            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            $styleArray = [
                'font' => [
                    'size' => 11,
                    'name' => 'Calibri'
                ],
            ];

            $spreadsheet->getDefaultStyle()->applyFromArray($styleArray);

            $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($header_arr) {
                foreach ($header_arr as $header_index => $header_cell) {
                    $spreadsheet->getActiveSheet()->getCell($header_index)->setValue($header_cell['label'])->getStyle($header_index)->applyFromArray($header_cell['style']);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:' . $end_cell . '1');
            $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Profit & Loss Report')->getStyle('A1:' . $end_cell . '1')->applyFromArray(hg_comman_customer_header_style('#bfbfbf', 14, '000000'));


            $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
            $p_index = 4;

            $itemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => true,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            $subitemStyle = array(
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false
                ],
                'font' => [
                    'size' => 11,
                    'bold' => false,
                    'color' => ['argb' => '#000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'c7e4db',
                    ],
                ]
            );

            if ($column_width_list) {
                foreach ($column_width_list as $col_key => $column_width) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col_key)->setWidth($column_width, 'px');
                }
            }


            $p_index = 4;
            $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
            $i = 0;

            $all_item_rows = [];
            $final_all_total = [];
            if ($pl_balance_items) {
                foreach ($pl_balance_items as $b_key => $bl_key) {
                    $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
                    if (isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0) {
                        foreach ($bl_key['data']['items'] as $sub_key => $sub_items) {
                            if (!isset($sub_items['download_tr_hide'])) {
                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($sub_items['label'])->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
                            }

                            $decimal_1 = 0;
                            if( isset($sub_items['decimal']) && $sub_items['decimal'] == true ){
                                $decimal_1 = 2;
                            }

                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];


                                    if (isset($sub_items['r_type']) && $sub_items['r_type'] == 'bl') {
                                        $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['final_closing']) && count($all_item_header_list[$tbdate_index][$s_label]['final_closing']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['final_closing']) : 0;
                                    } else {
                                        if ($sub_items['pl'] == true) {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['data']) && count($all_item_header_list[$tbdate_index][$s_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$s_label]['data']) : 0;
                                        } else {
                                            $cell_val = (isset($all_item_header_list[$tbdate_index][$s_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$s_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items['label']]['closing_data']) : 0;
                                        }
                                    }

                                    $cell_val = round($cell_val, 2);

                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($cell_val,$decimal_1))->getStyle($tbdate_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }

                                    $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                                    $final_all_total[$sub_items['label']][$tbdate_column_group['column']] = $cell_val;
                                    $date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                    $sub_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($tbdate_index))]][] = $cell_val;
                                }
                            }
                            $bl_flag = 0;
                            if( isset($sub_items['bl_rule']) && $sub_items['bl_rule'] == true ){
                                $bl_flag = 1;
                            }
                            if ($qt_data_column_range) {
                                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                    if ( $bl_flag == 1 ) {
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    }else{
                                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;                         
                                    }

                                    $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                                    $q_cell_val = round($q_cell_val, 2);
                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_1))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                    if ( $bl_flag == 1 ) {
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                                    }else{
                                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                                    }
                                    $y_cell_val = round($y_cell_val, 2);
                                    if (!isset($sub_items['download_tr_hide'])) {
                                        $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_1))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                    }
                                }
                            }
                            if (!isset($sub_items['download_tr_hide'])) {
                                $p_index++;
                            }
                        }
                    }

                    if (isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0) {
                        $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
                        $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($total_label)->getStyle('A' . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                        }

                        $decimal_2 = 0;
                        if( isset($bl_key['data']['total']['decimal']) && $bl_key['data']['total']['decimal'] == true ){
                            $decimal_2 = 2;
                        }


                        $s_year_wise_sub_types = $s_date_wise_sub_types = [];
                        if ($data_column_range) {
                            foreach ($data_column_range as $s_tbdate_index => $s_tbdate_column_group) {
                                // $s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;
                                // $s_cell_val = round($s_cell_val,2);

                                $s_cell_val = 0;

                                if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                                    $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                                    $s_cell_val = array_sum($all_values);
                                    if (is_array($minus_items_list) && count($minus_items_list) > 0) {
                                        foreach ($minus_items_list as $index) {
                                            if (isset($all_values[$index])) {
                                                $s_cell_val -= $all_values[$index] * 2;
                                            }
                                        }
                                    }

                                }
                                $s_cell_val = round($s_cell_val, 2);

                                if (!isset($bl_key['data']['total']['hide_tr'])) {

                                    $spreadsheet->getActiveSheet()->getCell($s_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($s_cell_val,$decimal_2))->getStyle($s_tbdate_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }

                                $s_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;
                                $s_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($s_tbdate_index))]][] = $s_cell_val;

                                $final_all_total[$bl_key['data']['total']['label']][$s_tbdate_column_group['column']] = $s_cell_val;
                            }
                        }

                        if ($qt_data_column_range) {
                            foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? array_sum($s_date_wise_sub_types[$qt_index]) : 0;
                                $q_cell_val = round($q_cell_val, 2);
                                if (!isset($bl_key['data']['total']['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_2))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                $y_cell_val = (isset($s_year_wise_sub_types[$r_yrt_index]) && count($s_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($s_year_wise_sub_types[$r_yrt_index]) : 0;
                                $y_cell_val = round($y_cell_val, 2);
                                if (!isset($bl_key['data']['total']['hide_tr'])) {
                                    $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_2))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                                }
                            }
                        }
                        if (!isset($bl_key['data']['total']['hide_tr'])) {
                            $p_index++;
                        }
                    }



                    if (isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0) {
                        foreach ($bl_key['final'] as $final_index => $final_items) {

                            if (isset($final_items['label']) && !empty($final_items['label'])) {

                                $f_year_wise_sub_types = $f_date_wise_sub_types = [];

                                $decimal_3 = 0;
                                if( isset($final_items['decimal']) && $final_items['decimal'] == true ){
                                    $decimal_3 = 2;
                                }

                                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($final_items['label'])->getStyle('A' . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                if ($data_column_range) {
                                    foreach ($data_column_range as $n_tbdate_index => $n_tbdate_column_group) {
                                        $n_cell_val = 0;

                                        if (isset($final_items['direct']) && $final_items['direct'] == true) {
                                            $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                                        } else {
                                            if (isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0) {
                                                $s_data = $final_items['items'];
                                                $operators = $final_items['operators'];
                                                foreach ($s_data as $i => $value) {
                                                    $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                                                }
                                            }
                                        }

                                        $n_cell_val = round($n_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . $p_index)->setValue(convert_decimal_format($n_cell_val,$decimal_3))->getStyle($n_tbdate_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);

                                        $f_date_wise_sub_types[$month_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;
                                        $f_year_wise_sub_types[$year_wise_grouped[date('m/d/Y', strtotime($n_tbdate_index))]][] = $n_cell_val;

                                        $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] = $n_cell_val;

                                    }
                                }

                                if ($qt_data_column_range) {
                                    foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                                        $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? array_sum($f_date_wise_sub_types[$qt_index]) : 0;
                                        $q_cell_val = round($q_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_3))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                        $y_cell_val = (isset($f_year_wise_sub_types[$r_yrt_index]) && count($f_year_wise_sub_types[$r_yrt_index]) > 0) ? array_sum($f_year_wise_sub_types[$r_yrt_index]) : 0;
                                        $y_cell_val = round($y_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($yrt_column_group['column'] . $p_index)->setValue(convert_decimal_format($y_cell_val,$decimal_3))->getStyle($yrt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                $p_index++;
                            }

                        }
                    }


                    $all_item_rows[] = 'A' . $p_index;
                }

                $all_item_rows[] = 'A' . $p_index;

            }


            if (is_array($all_item_rows) && count($all_item_rows) > 0) {
                foreach ($all_item_rows as $a_key => $a_value) {
                    $color = ($a_key % 2 == 0) ? 'c7e4db' : 'e3f1ed';
                    $spreadsheet->getActiveSheet()
                        ->getStyle($a_value)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB($color);

                    $cnt_index = str_replace('A', '', $a_value);

                    if ($data_column_range) {
                        foreach ($data_column_range as $at_index => $at_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($at_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    if ($qt_data_column_range) {
                        foreach ($qt_data_column_range as $nn_qt_index => $nn_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($nn_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                    if ($year_data_column_range) {
                        foreach ($year_data_column_range as $nn_syrt_index => $nn_syrt_column_group) {
                            $spreadsheet->getActiveSheet()
                                ->getStyle($nn_syrt_column_group['column'] . $cnt_index)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setARGB($color);
                        }
                    }

                }
            }


            $last_cell_index = $p_index - 1;
            $sheet->getStyle('A3:' . $end_cell . $last_cell_index)->applyFromArray($comman_all_border);

            if ($return_data == 1) {
                $sheet = $spreadsheet->getActiveSheet();
                $cellArray = $sheet->toArray(null, true, true, true);
                return $cellArray;
            } else {
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . $documentFileName);
            }

            return response()->json([
                'message' => 'Profit & Loss Report Generated Successfully!',
                'status' => 'success',
                'filename' => $documentFileName,
                'pdf' => url('/public/reports/pl-report/') . '/' . $rp_po_path . '/' . $documentFileName
            ], 200);

        } catch (\Exception $e) {

            if ($return_data == 1) {
                return [];
            }

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }

}