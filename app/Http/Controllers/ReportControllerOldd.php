<?php
namespace App\Http\Controllers;
use App\Http\Controllers\DashboardController; 
use App\Models\KPIRecords;
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

class ReportController extends Controller{

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
    public $kpi_bl_pl_items;

    function __construct() {

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

    public function getBLReport(Request $request, $return_data = 0){

        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

           // $profit_lossdata = $this->getPLReport($request, 1);
            //$retain_profit_arr = isset($profit_lossdata[26]) ? $profit_lossdata[26] : [];
            $retain_profit_arr = [];

            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();

            

            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));

                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
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

            if ($tbdate_list) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tbdate_list_arr[$tb_header_index]] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key])) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;
                                    $cell_val = round($cell_val, 2);

                                    // if ($sub_items['label'] == 'Current Year Profit') {
                                    //     $retain_total = isset($retain_profit_arr[$tbdate_column_group['column']]) ? str_replace(',', '', $retain_profit_arr[$tbdate_column_group['column']]) : 0;
                                    //     if ($retain_total == null) {
                                    //         $retain_total = 0;
                                    //     }
                                    //     $cell_val = $cell_val + (float) $retain_total;
                                    //     $cell_val = round($cell_val, 2);
                                    // }

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
                                    $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    $q_cell_val = round($q_cell_val, 2);
                                    $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_1))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray($subitemStyle);
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
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
                                $q_cell_val = (isset($s_date_wise_sub_types[$qt_index]) && count($s_date_wise_sub_types[$qt_index]) > 0) ? $s_date_wise_sub_types[$qt_index][array_key_last($s_date_wise_sub_types[$qt_index])] : 0;
                                $q_cell_val = round($q_cell_val, 2);
                                $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_2))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
                            }
                        }
                        if ($year_data_column_range) {
                            foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                $r_yrt_index = str_replace('YTD-', '', $yrt_index);
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
                                        $q_cell_val = (isset($f_date_wise_sub_types[$qt_index]) && count($f_date_wise_sub_types[$qt_index]) > 0) ? $f_date_wise_sub_types[$qt_index][array_key_last($f_date_wise_sub_types[$qt_index])] : 0;
                                        $q_cell_val = round($q_cell_val, 2);
                                        $spreadsheet->getActiveSheet()->getCell($qt_column_group['column'] . $p_index)->setValue(convert_decimal_format($q_cell_val,$decimal_3))->getStyle($qt_column_group['column'] . $p_index)->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);
                                    }
                                }
                                if ($year_data_column_range) {
                                    foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                        $r_yrt_index = str_replace('YTD-', '', $yrt_index);
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

    public function getBLReportView(Request $request){
        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();

            

            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));

                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
            }


            //$profit_lossdata = $this->getPLReport($request, 1);
            //$retain_profit_arr = isset($profit_lossdata[26]) ? $profit_lossdata[26] : [];
            $retain_profit_arr = [];
            
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

            if ($tbdate_list) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);

                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tbdate_list_arr[$tb_header_index]] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),                                
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
                    
                            $table_row_html .= '<tr>';
                            $table_row_html .= '<td>' . $sub_items['label']. '</td>';
                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];
                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {
                                  
                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;
                                    $cell_val = round($cell_val, 2);

                                    // if ($sub_items['label'] == 'Current Year Profit') {
                                    //     $retain_total = isset($retain_profit_arr[$tbdate_column_group['column']]) ? str_replace(',', '', $retain_profit_arr[$tbdate_column_group['column']]) : 0;
                                    //     if ($retain_total == null) {
                                    //         $retain_total = 0;
                                    //     }
                                    //     $cell_val = $cell_val + (float) $retain_total;
                                    //     $cell_val = round($cell_val, 2);
                                    // }
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
                                  
                                    $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                                    $q_cell_val = round($q_cell_val, 2);
                                    $table_row_html .= '<td>' . convert_decimal_format($q_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            if ($year_data_column_range) {
                                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                                    $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                                    $y_cell_val = round($y_cell_val, 2);
                                    $table_row_html .= '<td>' . convert_decimal_format($y_cell_val,$decimal_1) . '</td>';
                                }
                            }
                            $table_row_html .= '</tr>';
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

    public function getPLReportView(Request $request) {

        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
            }
            
            $finacial_titles = comman_finacial_column_title($all_dates);            
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $table_row_html = '';
            $table_header = '<th>Particulars</th>';
            $all_profitloss_report_list = $this->profitloss_report_list;
            if ($all_dates) {
                foreach ( $all_dates as $header_index => $header_date ) {
                    $table_header .= '<th>' . date('F, Y',  strtotime($header_date)) . '</th>';
                }
            }
            $table_header = '<thead>' . $table_header . '</thead>';               

            if ($all_dates) {
                foreach ( $all_dates as $t_index => $t_date ) {
                    $sales = isset($all_item_header_list[$t_date]['sales']) ? convert_round_format( $all_item_header_list[$t_date]['sales'], 0 ) : 0;
                    $cogs = isset($all_item_header_list[$t_date]['cost_of_goods_sold']) ? convert_round_format( $all_item_header_list[$t_date]['cost_of_goods_sold'], 0 ) : 0;
                    $opening_stock = isset($all_item_header_list[$t_date]['opening_stock']) ? $all_item_header_list[$t_date]['opening_stock'] : 0;
                    $direct_manufacturing_expenses = isset($all_item_header_list[$t_date]['direct_manufacturing_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['direct_manufacturing_expenses'], 0 ) : 0;
                    $closing_stock = isset($all_item_header_list[$t_date]['closing_stock']) ?  convert_round_format( $all_item_header_list[$t_date]['closing_stock'], 0 ) : 0;
                    $main_cogs = convert_round_format( ( ($cogs + $opening_stock + $direct_manufacturing_expenses ) - $closing_stock), 0 );                    
                    $gross_profit = convert_round_format ( $sales - $main_cogs, 0 );
                    $employee_benefit_expenses = isset($all_item_header_list[$t_date]['employee_benefit_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['employee_benefit_expenses'], 0 ): 0;
                    $selling_general_and_administrative_expenses = isset($all_item_header_list[$t_date]['selling_general_and_administrative_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['selling_general_and_administrative_expenses'], 0 ): 0;
                    $other_expenses = isset($all_item_header_list[$t_date]['other_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_expenses'], 0 ): 0;
                    $total_expenses = convert_round_format( ($employee_benefit_expenses  +  $selling_general_and_administrative_expenses + $other_expenses), 0 );
                    $EBITDA = convert_round_format ( ($gross_profit -  $total_expenses), 0 );
                    $depreciation_amortization = isset($all_item_header_list[$t_date]['depreciation_amortization']) ? convert_round_format( $all_item_header_list[$t_date]['depreciation_amortization'], 0 ): 0;
                    $PBIT = convert_round_format ( ($EBITDA -  $depreciation_amortization), 0 );
                    $interest_bank_charges = isset($all_item_header_list[$t_date]['interest_bank_charges']) ? convert_round_format( $all_item_header_list[$t_date]['interest_bank_charges'], 0 ): 0;
                    $profite_interest_tax = convert_round_format ( ($PBIT -  $interest_bank_charges), 0 );
                    $other_non_operating_income = isset($all_item_header_list[$t_date]['other_non_operating_income']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_income'], 0 ): 0;
                    $other_non_operating_expenses = isset($all_item_header_list[$t_date]['other_non_operating_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_expenses'], 0 ): 0;
                    $profite_before_exceptional_tax = convert_round_format ( ( ($profite_interest_tax +  $other_non_operating_income) - $other_non_operating_expenses), 0 );
                    $exceptional_extraordinary_income = isset($all_item_header_list[$t_date]['exceptional_extraordinary_income']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_income'], 0 ): 0;
                    $exceptional_extraordinary_expense = isset($all_item_header_list[$t_date]['exceptional_extraordinary_expense']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_expense'], 0 ): 0;
                    $profite_before_tax = convert_round_format ( ( ($profite_before_exceptional_tax +  $exceptional_extraordinary_income) - $exceptional_extraordinary_expense), 0 );
                    $current_tax = isset($all_item_header_list[$t_date]['current_tax']) ? convert_round_format( $all_item_header_list[$t_date]['current_tax'], 0 ): 0;
                    $deferred_tax = isset($all_item_header_list[$t_date]['deferred_tax']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax'], 0 ): 0;
                    $profite_after_tax = convert_round_format ( ($profite_before_tax - ($current_tax + $deferred_tax)), 0 );
                    $retain_dividend_paid = 0;
                    $retain_profit = convert_round_format ( ($profite_after_tax -  $retain_dividend_paid ), 0 );

                    $all_profitloss_report_list['sales']['amount'][$t_date] =  $sales;
                    $all_profitloss_report_list['main_cogs']['amount'][$t_date] =  $main_cogs;
                    $all_profitloss_report_list['gross_profit']['amount'][$t_date] = $gross_profit;
                    $all_profitloss_report_list['employee_benefit_expenses']['amount'][$t_date] = $employee_benefit_expenses;
                    $all_profitloss_report_list['selling_general_and_administrative_expenses']['amount'][$t_date] = $selling_general_and_administrative_expenses;
                    $all_profitloss_report_list['other_expenses']['amount'][$t_date] = $other_expenses;
                    $all_profitloss_report_list['total_expense']['amount'][$t_date] = $total_expenses;
                    $all_profitloss_report_list['ebita']['amount'][$t_date] = $EBITDA;
                    $all_profitloss_report_list['depreciation_amortization']['amount'][$t_date] = $depreciation_amortization;
                    $all_profitloss_report_list['pbit']['amount'][$t_date] = $PBIT;
                    $all_profitloss_report_list['interest_bank_charges']['amount'][$t_date] = $interest_bank_charges;
                    $all_profitloss_report_list['profit_after_interest_tax']['amount'][$t_date] = $profite_interest_tax;
                    $all_profitloss_report_list['other_non_operating_income']['amount'][$t_date] = $other_non_operating_income;
                    $all_profitloss_report_list['other_non_operating_expenses']['amount'][$t_date] = $other_non_operating_expenses;
                    $all_profitloss_report_list['profit_before_exceptional_taxt']['amount'][$t_date] = $profite_before_exceptional_tax;
                    $all_profitloss_report_list['exceptional_extraordinary_income']['amount'][$t_date] = $exceptional_extraordinary_income;   
                    $all_profitloss_report_list['exceptional_extraordinary_expense']['amount'][$t_date] = $exceptional_extraordinary_expense;
                    $all_profitloss_report_list['profit_before_tax']['amount'][$t_date] = $profite_before_tax;
                    $all_profitloss_report_list['current_tax']['amount'][$t_date] = $current_tax;
                    $all_profitloss_report_list['deferred_tax']['amount'][$t_date] = $deferred_tax;
                    $all_profitloss_report_list['profit_after_tax']['amount'][$t_date] = $profite_after_tax;
                    $all_profitloss_report_list['retain_dividend']['amount'][$t_date] = $retain_dividend_paid;
                    $all_profitloss_report_list['retain_profit']['amount'][$t_date] = $retain_profit;
                }
            }   

            foreach ( $all_profitloss_report_list as $rp_key => $rp_value ) {
                $table_row_html .= '<tr>';
                $bold_flag = 0;
                if( $rp_value['bold'] == true ){
                    $table_row_html .= '<td><b>'.$rp_value['label'].'</b></td>';
                    $bold_flag = 1;
                }else{
                    $table_row_html .= '<td>'.$rp_value['label'].'</td>';
                }
                if( isset($rp_value['amount']) && is_array($rp_value['amount']) && count($rp_value['amount']) > 0 ){
                    foreach ( $rp_value['amount'] as $a_index => $amount ) {
                        if( $bold_flag == 1 ){                            
                            $table_row_html .= '<td><b>' .  convert_decimal_format($amount,0) . '</b></td>';
                        }else{
                            $table_row_html .= '<td>' .  convert_decimal_format($amount,0) . '</td>';
                        }
                    }      
                }
                $table_row_html .= '</tr>';
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

            $date_filter = $request->get('date_filter');
            
            
            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
            }

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

    public function getFNSReport(Request $request, $return_data = 0)
    {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
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

            if ($tbdate_list) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tbdate_list_arr[$tb_header_index]] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            // $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            // $qt_data_column_range = [];
            // if (is_array($quater_grouped) && count($quater_grouped) > 0) {
            //     $qt_d_cnt = count($quater_grouped);
            //     $qt_d_cnt = $qt_d_cnt - 1;
            //     $quater_list_arr = array_values($quater_grouped);
            //     $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
            //     if ($quater_header_cell) {
            //         $start_col = end($quater_header_cell);
            //         foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
            //             if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
            //                 $tb_header[$qtr_range_index] = [
            //                     'label' => $quater_list_arr[$qt_header_index]['label'],
            //                     'style' => hg_comman_customer_header_style('bfbfbf')
            //                 ];
            //                 $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
            //                 $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
            //                     'column' => str_replace($start_row, '', $qtr_range_index),
            //                 );
            //             }
            //         }
            //     }
            // }

            // $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            // $year_data_column_range = [];
            // if (is_array($year_grouped) && count($year_grouped) > 0) {
            //     $yt_d_cnt = count($year_grouped);
            //     $yt_d_cnt = $yt_d_cnt - 1;
            //     $year_list_arr = array_values($year_grouped);
            //     $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
            //     if ($year_header_cell) {
            //         $start_col = end($year_header_cell);
            //         foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
            //             if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
            //                 $tb_header[$yt_range_index] = [
            //                     'label' => $year_list_arr[$yt_header_index]['label'],
            //                     'style' => hg_comman_customer_header_style('bfbfbf')
            //                 ];
            //                 $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
            //                 $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
            //                     'column' => str_replace($start_row, '', $yt_range_index),
            //                 );
            //             }
            //         }
            //     }
            // }


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

            $all_financial_summary_report_list = $this->new_financial_summary_report_list;

            if ($all_dates) {
                foreach ( $all_dates as $t_index => $t_date ) {
                    $sales = isset($all_item_header_list[$t_date]['sales']) ? convert_round_format( $all_item_header_list[$t_date]['sales'], 0 ) : 0;
                    $cogs = isset($all_item_header_list[$t_date]['cost_of_goods_sold']) ? convert_round_format( $all_item_header_list[$t_date]['cost_of_goods_sold'], 0 ) : 0;
                    $opening_stock = isset($all_item_header_list[$t_date]['opening_stock']) ? $all_item_header_list[$t_date]['opening_stock'] : 0;
                    $direct_manufacturing_expenses = isset($all_item_header_list[$t_date]['direct_manufacturing_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['direct_manufacturing_expenses'], 0 ) : 0;
                    $closing_stock = isset($all_item_header_list[$t_date]['closing_stock']) ?  convert_round_format( $all_item_header_list[$t_date]['closing_stock'], 0 ) : 0;
                    $main_cogs = convert_round_format( ( ($cogs + $opening_stock + $direct_manufacturing_expenses ) - $closing_stock), 0 );                    
                    $gross_profit = convert_round_format ( $sales - $main_cogs, 0 );
                    $employee_benefit_expenses = isset($all_item_header_list[$t_date]['employee_benefit_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['employee_benefit_expenses'], 0 ): 0;
                    $selling_general_and_administrative_expenses = isset($all_item_header_list[$t_date]['selling_general_and_administrative_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['selling_general_and_administrative_expenses'], 0 ): 0;
                    $other_expenses = isset($all_item_header_list[$t_date]['other_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_expenses'], 0 ): 0;
                    $overheads = convert_round_format( ($employee_benefit_expenses  +  $selling_general_and_administrative_expenses + $other_expenses), 0 );
                    $depreciation_amortization = isset($all_item_header_list[$t_date]['depreciation_amortization']) ? convert_round_format( $all_item_header_list[$t_date]['depreciation_amortization'], 0 ): 0;
                    $operating_profit = convert_round_format ( ($gross_profit - ($overheads + $depreciation_amortization) ), 0 );
                    $interest_bank_charges = isset($all_item_header_list[$t_date]['interest_bank_charges']) ? convert_round_format( $all_item_header_list[$t_date]['interest_bank_charges'], 0 ): 0;
                    $profite_interest_tax = convert_round_format ( ($operating_profit -  $interest_bank_charges), 0 );
                    $other_non_operating_income = isset($all_item_header_list[$t_date]['other_non_operating_income']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_income'], 0 ): 0;
                    $other_non_operating_expenses = isset($all_item_header_list[$t_date]['other_non_operating_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_expenses'], 0 ): 0;
                    $profite_before_exceptional_tax = convert_round_format ( ( ($profite_interest_tax +  $other_non_operating_income) - $other_non_operating_expenses), 0 );
                    $exceptional_extraordinary_income = isset($all_item_header_list[$t_date]['exceptional_extraordinary_income']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_income'], 0 ): 0;
                    $exceptional_extraordinary_expense = isset($all_item_header_list[$t_date]['exceptional_extraordinary_expense']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_expense'], 0 ): 0;
                    $profite_before_tax = convert_round_format ( ( ($profite_before_exceptional_tax +  $exceptional_extraordinary_income) - $exceptional_extraordinary_expense), 0 );
                    $current_tax = isset($all_item_header_list[$t_date]['current_tax']) ? convert_round_format( $all_item_header_list[$t_date]['current_tax'], 0 ): 0;
                    $deferred_tax = isset($all_item_header_list[$t_date]['deferred_tax']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax'], 0 ): 0;
                    $tax_paid = convert_round_format ( ($current_tax + $deferred_tax), 0 );
                    $profite_after_tax = convert_round_format ( ($profite_before_tax - ($current_tax + $deferred_tax)), 0 );
                    $retain_dividend_paid = 0;
                    $retain_profit = convert_round_format ( ($profite_after_tax -  $retain_dividend_paid ), 0 );

                    $cash_and_bank_balances = isset($all_item_header_list[$t_date]['cash_and_bank_balances']) ? convert_round_format( $all_item_header_list[$t_date]['cash_and_bank_balances'], 0 ): 0;
                    $accounts_receivable = isset($all_item_header_list[$t_date]['accounts_receivable']) ? convert_round_format( $all_item_header_list[$t_date]['accounts_receivable'], 0 ): 0;

                    $current_investments = isset($all_item_header_list[$t_date]['current_investments']) ? convert_round_format( $all_item_header_list[$t_date]['current_investments'], 0 ): 0;
                    $other_current_assets = isset($all_item_header_list[$t_date]['other_current_assets']) ? convert_round_format( $all_item_header_list[$t_date]['other_current_assets'], 0 ): 0;                    
                    $short_term_loans_advances = isset($all_item_header_list[$t_date]['short_term_loans_advances']) ? convert_round_format( $all_item_header_list[$t_date]['short_term_loans_advances'], 0 ): 0;                    
                    $branch = isset($all_item_header_list[$t_date]['branch']) ? convert_round_format( $all_item_header_list[$t_date]['branch'], 0 ): 0;
                    $suspense = isset($all_item_header_list[$t_date]['suspense']) ? convert_round_format( $all_item_header_list[$t_date]['suspense'], 0 ): 0;

                    $main_other_current_assets = convert_round_format ( ($current_investments +  $other_current_assets + $short_term_loans_advances + $branch + $suspense ), 0 );
                    $current_assets = convert_round_format ( ($cash_and_bank_balances +  $accounts_receivable + $closing_stock + $main_other_current_assets ), 0 );

                    $fixed_assets = isset($all_item_header_list[$t_date]['fixed_assets']) ? convert_round_format( $all_item_header_list[$t_date]['fixed_assets'], 0 ): 0;

                    $non_current_investments = isset($all_item_header_list[$t_date]['non_current_investments']) ? convert_round_format( $all_item_header_list[$t_date]['non_current_investments'], 0 ): 0;                    
                    $long_term_loans_advances = isset($all_item_header_list[$t_date]['long_term_loans_advances']) ? convert_round_format( $all_item_header_list[$t_date]['long_term_loans_advances'], 0 ): 0;                    
                    $deferred_tax_assets = isset($all_item_header_list[$t_date]['deferred_tax_assets']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax_assets'], 0 ): 0;                    
                    $other_non_current_assets = isset($all_item_header_list[$t_date]['other_non_current_assets']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_current_assets'], 0 ): 0;

                    $main_other_non_current_assets = convert_round_format ( ($non_current_investments +  $long_term_loans_advances + $deferred_tax_assets + $other_non_current_assets ), 0 );
                    $non_current_assets = convert_round_format ( ($main_other_non_current_assets +  $fixed_assets ), 0 );

                    $total_assets = convert_round_format ( ($current_assets +  $non_current_assets ), 0 );

                    $accounts_payable = isset($all_item_header_list[$t_date]['accounts_payable']) ? convert_round_format( $all_item_header_list[$t_date]['accounts_payable'], 0 ): 0;
                    $secured_short_term_borrowings = isset($all_item_header_list[$t_date]['secured_short_term_borrowings']) ? convert_round_format( $all_item_header_list[$t_date]['secured_short_term_borrowings'], 0 ): 0;

                    $short_term_provisions = isset($all_item_header_list[$t_date]['short_term_provisions']) ? convert_round_format( $all_item_header_list[$t_date]['short_term_provisions'], 0 ): 0; 
                    $current_deferred_tax_liabilities = isset($all_item_header_list[$t_date]['current_deferred_tax_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['current_deferred_tax_liabilities'], 0 ): 0; 
                    $other_current_liabilities = isset($all_item_header_list[$t_date]['other_current_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['other_current_liabilities'], 0 ): 0; 
                    $difference_in_opening_balance = isset($all_item_header_list[$t_date]['difference_in_opening_balance']) ? convert_round_format( $all_item_header_list[$t_date]['difference_in_opening_balance'], 0 ): 0; 

                    $main_other_current_liabilities = convert_round_format ( ($short_term_provisions +  $current_deferred_tax_liabilities +  $other_current_liabilities +  $difference_in_opening_balance ), 0 );
                    $current_liabilities = convert_round_format ( ($accounts_payable +  $secured_short_term_borrowings +  $other_current_liabilities ), 0 );

                    $secured_long_term_borrowings = isset($all_item_header_list[$t_date]['secured_long_term_borrowings']) ? convert_round_format( $all_item_header_list[$t_date]['secured_long_term_borrowings'], 0 ): 0; 
                    $unsecured_long_term_borrowings = isset($all_item_header_list[$t_date]['unsecured_long_term_borrowings']) ? convert_round_format( $all_item_header_list[$t_date]['unsecured_long_term_borrowings'], 0 ): 0; 

                    $bank_load_non_current = convert_round_format ( ($secured_long_term_borrowings +  $unsecured_long_term_borrowings ), 0 );
                    $long_term_provisions = isset($all_item_header_list[$t_date]['long_term_provisions']) ? convert_round_format( $all_item_header_list[$t_date]['long_term_provisions'], 0 ): 0; 
                    $deferred_tax_liabilities = isset($all_item_header_list[$t_date]['deferred_tax_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax_liabilities'], 0 ): 0; 
                    $other_non_current_liabilities = isset($all_item_header_list[$t_date]['other_non_current_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_current_liabilities'], 0 ): 0; 
                    $main_other_non_current_liabilities = convert_round_format ( ($long_term_provisions +  $deferred_tax_liabilities +  $other_non_current_liabilities ), 0 );

                    $non_current_liabilities = convert_round_format ( ($main_other_non_current_liabilities +  $bank_load_non_current ), 0 );
                    $total_liabilities = convert_round_format ( ($current_liabilities +  $non_current_liabilities ), 0 );
 
                    $equity_share_capital = isset($all_item_header_list[$t_date]['equity_share_capital']) ? convert_round_format( $all_item_header_list[$t_date]['equity_share_capital'], 0 ): 0; 
                    $other_equity = isset($all_item_header_list[$t_date]['other_equity']) ? convert_round_format( $all_item_header_list[$t_date]['other_equity'], 0 ): 0; 
                    $reserve_surplus = isset($all_item_header_list[$t_date]['reserve_surplus']) ? convert_round_format( $all_item_header_list[$t_date]['reserve_surplus'], 0 ): 0; 
                    $current_year_profit = isset($all_item_header_list[$t_date]['current_year_profit']) ? convert_round_format( $all_item_header_list[$t_date]['current_year_profit'], 0 ): 0; 

                    $equity =  convert_round_format ( ($equity_share_capital +  $other_equity +  $reserve_surplus + $current_year_profit ), 0 );

                    $validation =  convert_round_format ( ($total_assets - ( $equity +  $total_liabilities ) ), 0 );

                    $all_financial_summary_report_list['sales']['amount'][$t_date] =  $sales;
                    $all_financial_summary_report_list['main_cogs']['amount'][$t_date] =  $main_cogs;
                    $all_financial_summary_report_list['gross_profit']['amount'][$t_date] = $gross_profit;                    
                    $all_financial_summary_report_list['overheads']['amount'][$t_date] = $overheads;
                    $all_financial_summary_report_list['depreciation_amortization']['amount'][$t_date] = $depreciation_amortization;
                    $all_financial_summary_report_list['operating_profit']['amount'][$t_date] = $operating_profit;
                    $all_financial_summary_report_list['interest_bank_charges']['amount'][$t_date] = $interest_bank_charges;
                    $all_financial_summary_report_list['profit_after_interest_tax']['amount'][$t_date] = $profite_interest_tax;
                    $all_financial_summary_report_list['other_non_operating_income']['amount'][$t_date] = $other_non_operating_income;
                    $all_financial_summary_report_list['other_non_operating_expenses']['amount'][$t_date] = $other_non_operating_expenses;
                    $all_financial_summary_report_list['profit_before_exceptional_taxt']['amount'][$t_date] = $profite_before_exceptional_tax;
                    $all_financial_summary_report_list['exceptional_extraordinary_income']['amount'][$t_date] = $exceptional_extraordinary_income;   
                    $all_financial_summary_report_list['exceptional_extraordinary_expense']['amount'][$t_date] = $exceptional_extraordinary_expense;
                    $all_financial_summary_report_list['profit_before_tax']['amount'][$t_date] = $profite_before_tax;
                    $all_financial_summary_report_list['tax_paid']['amount'][$t_date] = $tax_paid;
                    $all_financial_summary_report_list['profit_after_tax']['amount'][$t_date] = $profite_after_tax;
                    $all_financial_summary_report_list['retain_dividend']['amount'][$t_date] = $retain_dividend_paid;
                    $all_financial_summary_report_list['retain_profit']['amount'][$t_date] = $retain_profit;
                    $all_financial_summary_report_list['cash_and_bank_balances']['amount'][$t_date] = $cash_and_bank_balances;
                    $all_financial_summary_report_list['accounts_receivable']['amount'][$t_date] = $accounts_receivable;
                    $all_financial_summary_report_list['other_current_assets']['amount'][$t_date] = $main_other_current_assets;                    
                    $all_financial_summary_report_list['current_assets']['amount'][$t_date] = $current_assets;                    
                    $all_financial_summary_report_list['fixed_assets']['amount'][$t_date] = $fixed_assets;
                    $all_financial_summary_report_list['other_non_current_assets']['amount'][$t_date] = $main_other_non_current_assets;                    
                    $all_financial_summary_report_list['non_current_assets']['amount'][$t_date] = $non_current_assets;                    
                    $all_financial_summary_report_list['total_assets']['amount'][$t_date] = $total_assets;                    
                    $all_financial_summary_report_list['accounts_payable']['amount'][$t_date] = $accounts_payable;
                    $all_financial_summary_report_list['secured_short_term_borrowings']['amount'][$t_date] = $secured_short_term_borrowings;                    
                    $all_financial_summary_report_list['other_current_liabilities']['amount'][$t_date] = $main_other_current_liabilities;
                    $all_financial_summary_report_list['current_liabilities']['amount'][$t_date] = $current_liabilities;
                    $all_financial_summary_report_list['bank_load_non_current']['amount'][$t_date] = $bank_load_non_current;             
                    $all_financial_summary_report_list['other_non_current_liabilities']['amount'][$t_date] = $main_other_non_current_liabilities;
                    $all_financial_summary_report_list['non_current_liabilities']['amount'][$t_date] = $non_current_liabilities;
                    $all_financial_summary_report_list['total_liabilities']['amount'][$t_date] = $total_liabilities;
                    $all_financial_summary_report_list['equity']['amount'][$t_date] = $equity;
                    $all_financial_summary_report_list['validation']['amount'][$t_date] = $validation;                    

                }
            }  


            $all_item_rows = [];
            $p_index = 4;
            foreach ( $all_financial_summary_report_list as $rp_key => $rp_value ) {
                $all_item_rows[] = 'A' . $p_index;
                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($rp_value['label'])->getStyle('A' . $p_index)->applyFromArray(($rp_value['bold'] == true) ? $itemStyle : $subitemStyle);                
                if( isset($rp_value['amount']) && is_array($rp_value['amount']) && count($rp_value['amount']) > 0 ){
                    foreach ( $rp_value['amount'] as $a_index => $amount ) {    
                        $spreadsheet->getActiveSheet()->getCell($data_column_range[$a_index]['column'] . $p_index)->setValue(convert_decimal_format($amount,0))->getStyle($data_column_range[$a_index]['column']. $p_index)->applyFromArray(($rp_value['bold'] == true) ? $itemStyle : $subitemStyle);
                    }      
                }
                $p_index++;
            }            
            $i = 0;

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

                    // if ($qt_data_column_range) {
                    //     foreach ($qt_data_column_range as $nn_qt_index => $nn_column_group) {
                    //         $spreadsheet->getActiveSheet()
                    //             ->getStyle($nn_column_group['column'] . $cnt_index)
                    //             ->getFill()
                    //             ->setFillType(Fill::FILL_SOLID)
                    //             ->getStartColor()
                    //             ->setARGB($color);
                    //     }
                    // }

                    // if ($year_data_column_range) {
                    //     foreach ($year_data_column_range as $nn_syrt_index => $nn_syrt_column_group) {
                    //         $spreadsheet->getActiveSheet()
                    //             ->getStyle($nn_syrt_column_group['column'] . $cnt_index)
                    //             ->getFill()
                    //             ->setFillType(Fill::FILL_SOLID)
                    //             ->getStartColor()
                    //             ->setARGB($color);
                    //     }
                    // }

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


    public function getFNSReportView(Request $request) {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            
            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
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

            if ($tbdate_list) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tbdate_list_arr[$tb_header_index]] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }

            // $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 1);
            // $qt_data_column_range = [];
            // if (is_array($quater_grouped) && count($quater_grouped) > 0) {
            //     $qt_d_cnt = count($quater_grouped);
            //     $qt_d_cnt = $qt_d_cnt - 1;
            //     $quater_list_arr = array_values($quater_grouped);
            //     $quater_header_cell = hg_generate_excel_cell_names($start_row, $qt_d_cnt, $start_col);
            //     if ($quater_header_cell) {
            //         $start_col = end($quater_header_cell);
            //         foreach ($quater_header_cell as $qt_header_index => $qtr_range_index) {
            //             if (isset($quater_list_arr[$qt_header_index]) && !empty($quater_list_arr[$qt_header_index])) {
            //                 $tb_header[$qtr_range_index] = [
            //                     'label' => $quater_list_arr[$qt_header_index]['label'],
            //                     'style' => hg_comman_customer_header_style('bfbfbf')
            //                 ];
            //                 $column_width_list[str_replace($start_row, '', $qtr_range_index)] = 125;
            //                 $qt_data_column_range[$quater_list_arr[$qt_header_index]['label']] = array(
            //                     'column' => str_replace($start_row, '', $qtr_range_index),
            //                 );
            //             }
            //         }
            //     }
            // }

            // $start_col = hg_get_next_excel_cell_names('', str_replace($start_row, '', $start_col), 0);
            // $year_data_column_range = [];
            // if (is_array($year_grouped) && count($year_grouped) > 0) {
            //     $yt_d_cnt = count($year_grouped);
            //     $yt_d_cnt = $yt_d_cnt - 1;
            //     $year_list_arr = array_values($year_grouped);
            //     $year_header_cell = hg_generate_excel_cell_names($start_row, $yt_d_cnt, $start_col);
            //     if ($year_header_cell) {
            //         $start_col = end($year_header_cell);
            //         foreach ($year_header_cell as $yt_header_index => $yt_range_index) {
            //             if (isset($year_list_arr[$yt_header_index]) && !empty($year_list_arr[$yt_header_index])) {
            //                 $tb_header[$yt_range_index] = [
            //                     'label' => $year_list_arr[$yt_header_index]['label'],
            //                     'style' => hg_comman_customer_header_style('bfbfbf')
            //                 ];
            //                 $column_width_list[str_replace($start_row, '', $yt_range_index)] = 125;
            //                 $year_data_column_range[$year_list_arr[$yt_header_index]['label']] = array(
            //                     'column' => str_replace($start_row, '', $yt_range_index),
            //                 );
            //             }
            //         }
            //     }
            // }

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

            $all_financial_summary_report_list = $this->new_financial_summary_report_list;

            if ($all_dates) {
                foreach ( $all_dates as $t_index => $t_date ) {
                    $sales = isset($all_item_header_list[$t_date]['sales']) ? convert_round_format( $all_item_header_list[$t_date]['sales'], 0 ) : 0;
                    $cogs = isset($all_item_header_list[$t_date]['cost_of_goods_sold']) ? convert_round_format( $all_item_header_list[$t_date]['cost_of_goods_sold'], 0 ) : 0;
                    $opening_stock = isset($all_item_header_list[$t_date]['opening_stock']) ? $all_item_header_list[$t_date]['opening_stock'] : 0;
                    $direct_manufacturing_expenses = isset($all_item_header_list[$t_date]['direct_manufacturing_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['direct_manufacturing_expenses'], 0 ) : 0;
                    $closing_stock = isset($all_item_header_list[$t_date]['closing_stock']) ?  convert_round_format( $all_item_header_list[$t_date]['closing_stock'], 0 ) : 0;
                    $main_cogs = convert_round_format( ( ($cogs + $opening_stock + $direct_manufacturing_expenses ) - $closing_stock), 0 );                    
                    $gross_profit = convert_round_format ( $sales - $main_cogs, 0 );
                    $employee_benefit_expenses = isset($all_item_header_list[$t_date]['employee_benefit_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['employee_benefit_expenses'], 0 ): 0;
                    $selling_general_and_administrative_expenses = isset($all_item_header_list[$t_date]['selling_general_and_administrative_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['selling_general_and_administrative_expenses'], 0 ): 0;
                    $other_expenses = isset($all_item_header_list[$t_date]['other_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_expenses'], 0 ): 0;
                    $overheads = convert_round_format( ($employee_benefit_expenses  +  $selling_general_and_administrative_expenses + $other_expenses), 0 );
                    $depreciation_amortization = isset($all_item_header_list[$t_date]['depreciation_amortization']) ? convert_round_format( $all_item_header_list[$t_date]['depreciation_amortization'], 0 ): 0;
                    $operating_profit = convert_round_format ( ($gross_profit - ($overheads + $depreciation_amortization) ), 0 );
                    $interest_bank_charges = isset($all_item_header_list[$t_date]['interest_bank_charges']) ? convert_round_format( $all_item_header_list[$t_date]['interest_bank_charges'], 0 ): 0;
                    $profite_interest_tax = convert_round_format ( ($operating_profit -  $interest_bank_charges), 0 );
                    $other_non_operating_income = isset($all_item_header_list[$t_date]['other_non_operating_income']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_income'], 0 ): 0;
                    $other_non_operating_expenses = isset($all_item_header_list[$t_date]['other_non_operating_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_expenses'], 0 ): 0;
                    $profite_before_exceptional_tax = convert_round_format ( ( ($profite_interest_tax +  $other_non_operating_income) - $other_non_operating_expenses), 0 );
                    $exceptional_extraordinary_income = isset($all_item_header_list[$t_date]['exceptional_extraordinary_income']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_income'], 0 ): 0;
                    $exceptional_extraordinary_expense = isset($all_item_header_list[$t_date]['exceptional_extraordinary_expense']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_expense'], 0 ): 0;
                    $profite_before_tax = convert_round_format ( ( ($profite_before_exceptional_tax +  $exceptional_extraordinary_income) - $exceptional_extraordinary_expense), 0 );
                    $current_tax = isset($all_item_header_list[$t_date]['current_tax']) ? convert_round_format( $all_item_header_list[$t_date]['current_tax'], 0 ): 0;
                    $deferred_tax = isset($all_item_header_list[$t_date]['deferred_tax']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax'], 0 ): 0;
                    $tax_paid = convert_round_format ( ($current_tax + $deferred_tax), 0 );
                    $profite_after_tax = convert_round_format ( ($profite_before_tax - ($current_tax + $deferred_tax)), 0 );
                    $retain_dividend_paid = 0;
                    $retain_profit = convert_round_format ( ($profite_after_tax -  $retain_dividend_paid ), 0 );

                    $cash_and_bank_balances = isset($all_item_header_list[$t_date]['cash_and_bank_balances']) ? convert_round_format( $all_item_header_list[$t_date]['cash_and_bank_balances'], 0 ): 0;
                    $accounts_receivable = isset($all_item_header_list[$t_date]['accounts_receivable']) ? convert_round_format( $all_item_header_list[$t_date]['accounts_receivable'], 0 ): 0;

                    $current_investments = isset($all_item_header_list[$t_date]['current_investments']) ? convert_round_format( $all_item_header_list[$t_date]['current_investments'], 0 ): 0;
                    $other_current_assets = isset($all_item_header_list[$t_date]['other_current_assets']) ? convert_round_format( $all_item_header_list[$t_date]['other_current_assets'], 0 ): 0;                    
                    $short_term_loans_advances = isset($all_item_header_list[$t_date]['short_term_loans_advances']) ? convert_round_format( $all_item_header_list[$t_date]['short_term_loans_advances'], 0 ): 0;                    
                    $branch = isset($all_item_header_list[$t_date]['branch']) ? convert_round_format( $all_item_header_list[$t_date]['branch'], 0 ): 0;
                    $suspense = isset($all_item_header_list[$t_date]['suspense']) ? convert_round_format( $all_item_header_list[$t_date]['suspense'], 0 ): 0;

                    $main_other_current_assets = convert_round_format ( ($current_investments +  $other_current_assets + $short_term_loans_advances + $branch + $suspense ), 0 );
                    $current_assets = convert_round_format ( ($cash_and_bank_balances +  $accounts_receivable + $closing_stock + $main_other_current_assets ), 0 );

                    $fixed_assets = isset($all_item_header_list[$t_date]['fixed_assets']) ? convert_round_format( $all_item_header_list[$t_date]['fixed_assets'], 0 ): 0;

                    $non_current_investments = isset($all_item_header_list[$t_date]['non_current_investments']) ? convert_round_format( $all_item_header_list[$t_date]['non_current_investments'], 0 ): 0;                    
                    $long_term_loans_advances = isset($all_item_header_list[$t_date]['long_term_loans_advances']) ? convert_round_format( $all_item_header_list[$t_date]['long_term_loans_advances'], 0 ): 0;                    
                    $deferred_tax_assets = isset($all_item_header_list[$t_date]['deferred_tax_assets']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax_assets'], 0 ): 0;                    
                    $other_non_current_assets = isset($all_item_header_list[$t_date]['other_non_current_assets']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_current_assets'], 0 ): 0;

                    $main_other_non_current_assets = convert_round_format ( ($non_current_investments +  $long_term_loans_advances + $deferred_tax_assets + $other_non_current_assets ), 0 );
                    $non_current_assets = convert_round_format ( ($main_other_non_current_assets +  $fixed_assets ), 0 );

                    $total_assets = convert_round_format ( ($current_assets +  $non_current_assets ), 0 );

                    $accounts_payable = isset($all_item_header_list[$t_date]['accounts_payable']) ? convert_round_format( $all_item_header_list[$t_date]['accounts_payable'], 0 ): 0;
                    $secured_short_term_borrowings = isset($all_item_header_list[$t_date]['secured_short_term_borrowings']) ? convert_round_format( $all_item_header_list[$t_date]['secured_short_term_borrowings'], 0 ): 0;

                    $short_term_provisions = isset($all_item_header_list[$t_date]['short_term_provisions']) ? convert_round_format( $all_item_header_list[$t_date]['short_term_provisions'], 0 ): 0; 
                    $current_deferred_tax_liabilities = isset($all_item_header_list[$t_date]['current_deferred_tax_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['current_deferred_tax_liabilities'], 0 ): 0; 
                    $other_current_liabilities = isset($all_item_header_list[$t_date]['other_current_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['other_current_liabilities'], 0 ): 0; 
                    $difference_in_opening_balance = isset($all_item_header_list[$t_date]['difference_in_opening_balance']) ? convert_round_format( $all_item_header_list[$t_date]['difference_in_opening_balance'], 0 ): 0; 

                    $main_other_current_liabilities = convert_round_format ( ($short_term_provisions +  $current_deferred_tax_liabilities +  $other_current_liabilities +  $difference_in_opening_balance ), 0 );
                    $current_liabilities = convert_round_format ( ($accounts_payable +  $secured_short_term_borrowings +  $other_current_liabilities ), 0 );

                    $secured_long_term_borrowings = isset($all_item_header_list[$t_date]['secured_long_term_borrowings']) ? convert_round_format( $all_item_header_list[$t_date]['secured_long_term_borrowings'], 0 ): 0; 
                    $unsecured_long_term_borrowings = isset($all_item_header_list[$t_date]['unsecured_long_term_borrowings']) ? convert_round_format( $all_item_header_list[$t_date]['unsecured_long_term_borrowings'], 0 ): 0; 

                    $bank_load_non_current = convert_round_format ( ($secured_long_term_borrowings +  $unsecured_long_term_borrowings ), 0 );
                    $long_term_provisions = isset($all_item_header_list[$t_date]['long_term_provisions']) ? convert_round_format( $all_item_header_list[$t_date]['long_term_provisions'], 0 ): 0; 
                    $deferred_tax_liabilities = isset($all_item_header_list[$t_date]['deferred_tax_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax_liabilities'], 0 ): 0; 
                    $other_non_current_liabilities = isset($all_item_header_list[$t_date]['other_non_current_liabilities']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_current_liabilities'], 0 ): 0; 
                    $main_other_non_current_liabilities = convert_round_format ( ($long_term_provisions +  $deferred_tax_liabilities +  $other_non_current_liabilities ), 0 );

                    $non_current_liabilities = convert_round_format ( ($main_other_non_current_liabilities +  $bank_load_non_current ), 0 );
                    $total_liabilities = convert_round_format ( ($current_liabilities +  $non_current_liabilities ), 0 );
 
                    $equity_share_capital = isset($all_item_header_list[$t_date]['equity_share_capital']) ? convert_round_format( $all_item_header_list[$t_date]['equity_share_capital'], 0 ): 0; 
                    $other_equity = isset($all_item_header_list[$t_date]['other_equity']) ? convert_round_format( $all_item_header_list[$t_date]['other_equity'], 0 ): 0; 
                    $reserve_surplus = isset($all_item_header_list[$t_date]['reserve_surplus']) ? convert_round_format( $all_item_header_list[$t_date]['reserve_surplus'], 0 ): 0; 
                    $current_year_profit = isset($all_item_header_list[$t_date]['current_year_profit']) ? convert_round_format( $all_item_header_list[$t_date]['current_year_profit'], 0 ): 0; 

                    $equity =  convert_round_format ( ($equity_share_capital +  $other_equity +  $reserve_surplus + $current_year_profit ), 0 );

                    $validation =  convert_round_format ( ($total_assets - ( $equity +  $total_liabilities ) ), 0 );

                    $all_financial_summary_report_list['sales']['amount'][$t_date] =  $sales;
                    $all_financial_summary_report_list['main_cogs']['amount'][$t_date] =  $main_cogs;
                    $all_financial_summary_report_list['gross_profit']['amount'][$t_date] = $gross_profit;                    
                    $all_financial_summary_report_list['overheads']['amount'][$t_date] = $overheads;
                    $all_financial_summary_report_list['depreciation_amortization']['amount'][$t_date] = $depreciation_amortization;
                    $all_financial_summary_report_list['operating_profit']['amount'][$t_date] = $operating_profit;
                    $all_financial_summary_report_list['interest_bank_charges']['amount'][$t_date] = $interest_bank_charges;
                    $all_financial_summary_report_list['profit_after_interest_tax']['amount'][$t_date] = $profite_interest_tax;
                    $all_financial_summary_report_list['other_non_operating_income']['amount'][$t_date] = $other_non_operating_income;
                    $all_financial_summary_report_list['other_non_operating_expenses']['amount'][$t_date] = $other_non_operating_expenses;
                    $all_financial_summary_report_list['profit_before_exceptional_taxt']['amount'][$t_date] = $profite_before_exceptional_tax;
                    $all_financial_summary_report_list['exceptional_extraordinary_income']['amount'][$t_date] = $exceptional_extraordinary_income;   
                    $all_financial_summary_report_list['exceptional_extraordinary_expense']['amount'][$t_date] = $exceptional_extraordinary_expense;
                    $all_financial_summary_report_list['profit_before_tax']['amount'][$t_date] = $profite_before_tax;
                    $all_financial_summary_report_list['tax_paid']['amount'][$t_date] = $tax_paid;
                    $all_financial_summary_report_list['profit_after_tax']['amount'][$t_date] = $profite_after_tax;
                    $all_financial_summary_report_list['retain_dividend']['amount'][$t_date] = $retain_dividend_paid;
                    $all_financial_summary_report_list['retain_profit']['amount'][$t_date] = $retain_profit;
                    $all_financial_summary_report_list['cash_and_bank_balances']['amount'][$t_date] = $cash_and_bank_balances;
                    $all_financial_summary_report_list['accounts_receivable']['amount'][$t_date] = $accounts_receivable;
                    $all_financial_summary_report_list['other_current_assets']['amount'][$t_date] = $main_other_current_assets;                    
                    $all_financial_summary_report_list['current_assets']['amount'][$t_date] = $current_assets;                    
                    $all_financial_summary_report_list['fixed_assets']['amount'][$t_date] = $fixed_assets;
                    $all_financial_summary_report_list['other_non_current_assets']['amount'][$t_date] = $main_other_non_current_assets;                    
                    $all_financial_summary_report_list['non_current_assets']['amount'][$t_date] = $non_current_assets;                    
                    $all_financial_summary_report_list['total_assets']['amount'][$t_date] = $total_assets;                    
                    $all_financial_summary_report_list['accounts_payable']['amount'][$t_date] = $accounts_payable;
                    $all_financial_summary_report_list['secured_short_term_borrowings']['amount'][$t_date] = $secured_short_term_borrowings;                    
                    $all_financial_summary_report_list['other_current_liabilities']['amount'][$t_date] = $main_other_current_liabilities;
                    $all_financial_summary_report_list['current_liabilities']['amount'][$t_date] = $current_liabilities;
                    $all_financial_summary_report_list['bank_load_non_current']['amount'][$t_date] = $bank_load_non_current;             
                    $all_financial_summary_report_list['other_non_current_liabilities']['amount'][$t_date] = $main_other_non_current_liabilities;
                    $all_financial_summary_report_list['non_current_liabilities']['amount'][$t_date] = $non_current_liabilities;
                    $all_financial_summary_report_list['total_liabilities']['amount'][$t_date] = $total_liabilities;
                    $all_financial_summary_report_list['equity']['amount'][$t_date] = $equity;
                    $all_financial_summary_report_list['validation']['amount'][$t_date] = $validation;                    

                }
            }  

            foreach ( $all_financial_summary_report_list as $rp_key => $rp_value ) {
                $table_row_html .= '<tr>';
                $bold_flag = 0;
                if( isset($rp_value['bold']) && $rp_value['bold'] == true ){
                    $table_row_html .= '<td><b>'.$rp_value['label'].'</b></td>';
                    $bold_flag = 1;
                }else{
                    $table_row_html .= '<td>'.$rp_value['label'].'</td>';
                }
                if( isset($rp_value['amount']) && is_array($rp_value['amount']) && count($rp_value['amount']) > 0 ){
                    foreach ( $rp_value['amount'] as $a_index => $amount ) {
                        if( $bold_flag == 1 ){                            
                            $table_row_html .= '<td><b>' .  convert_decimal_format($amount,0) . '</b></td>';
                        }else{
                            $table_row_html .= '<td>' .  convert_decimal_format($amount,0) . '</td>';
                        }
                    }      
                }
                $table_row_html .= '</tr>';
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

    public function getPLReport( Request $request, $return_data = 0 ) {


        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

           
           $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
            }
            
            $finacial_titles = comman_finacial_column_title($all_dates);            
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];

            $table_row_html = '';
            $table_header = '<th>Particulars</th>';
            $all_profitloss_report_list = $this->profitloss_report_list;
            if ($all_dates) {
                foreach ( $all_dates as $header_index => $header_date ) {
                    $table_header .= '<th>' . date('F, Y',  strtotime($header_date)) . '</th>';
                }
            }
            $table_header = '<thead>' . $table_header . '</thead>';               

            if ($all_dates) {
                foreach ( $all_dates as $t_index => $t_date ) {
                    $sales = isset($all_item_header_list[$t_date]['sales']) ? convert_round_format( $all_item_header_list[$t_date]['sales'], 0 ) : 0;
                    $cogs = isset($all_item_header_list[$t_date]['cost_of_goods_sold']) ? convert_round_format( $all_item_header_list[$t_date]['cost_of_goods_sold'], 0 ) : 0;
                    $opening_stock = isset($all_item_header_list[$t_date]['opening_stock']) ? $all_item_header_list[$t_date]['opening_stock'] : 0;
                    $direct_manufacturing_expenses = isset($all_item_header_list[$t_date]['direct_manufacturing_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['direct_manufacturing_expenses'], 0 ) : 0;
                    $closing_stock = isset($all_item_header_list[$t_date]['closing_stock']) ?  convert_round_format( $all_item_header_list[$t_date]['closing_stock'], 0 ) : 0;
                    $main_cogs = convert_round_format( ( ($cogs + $opening_stock + $direct_manufacturing_expenses ) - $closing_stock), 0 );                    
                    $gross_profit = convert_round_format ( $sales - $main_cogs, 0 );
                    $employee_benefit_expenses = isset($all_item_header_list[$t_date]['employee_benefit_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['employee_benefit_expenses'], 0 ): 0;
                    $selling_general_and_administrative_expenses = isset($all_item_header_list[$t_date]['selling_general_and_administrative_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['selling_general_and_administrative_expenses'], 0 ): 0;
                    $other_expenses = isset($all_item_header_list[$t_date]['other_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_expenses'], 0 ): 0;
                    $total_expenses = convert_round_format( ($employee_benefit_expenses  +  $selling_general_and_administrative_expenses + $other_expenses), 0 );
                    $EBITDA = convert_round_format ( ($gross_profit -  $total_expenses), 0 );
                    $depreciation_amortization = isset($all_item_header_list[$t_date]['depreciation_amortization']) ? convert_round_format( $all_item_header_list[$t_date]['depreciation_amortization'], 0 ): 0;
                    $PBIT = convert_round_format ( ($EBITDA -  $depreciation_amortization), 0 );
                    $interest_bank_charges = isset($all_item_header_list[$t_date]['interest_bank_charges']) ? convert_round_format( $all_item_header_list[$t_date]['interest_bank_charges'], 0 ): 0;
                    $profite_interest_tax = convert_round_format ( ($PBIT -  $interest_bank_charges), 0 );
                    $other_non_operating_income = isset($all_item_header_list[$t_date]['other_non_operating_income']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_income'], 0 ): 0;
                    $other_non_operating_expenses = isset($all_item_header_list[$t_date]['other_non_operating_expenses']) ? convert_round_format( $all_item_header_list[$t_date]['other_non_operating_expenses'], 0 ): 0;
                    $profite_before_exceptional_tax = convert_round_format ( ( ($profite_interest_tax +  $other_non_operating_income) - $other_non_operating_expenses), 0 );
                    $exceptional_extraordinary_income = isset($all_item_header_list[$t_date]['exceptional_extraordinary_income']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_income'], 0 ): 0;
                    $exceptional_extraordinary_expense = isset($all_item_header_list[$t_date]['exceptional_extraordinary_expense']) ? convert_round_format( $all_item_header_list[$t_date]['exceptional_extraordinary_expense'], 0 ): 0;
                    $profite_before_tax = convert_round_format ( ( ($profite_before_exceptional_tax +  $exceptional_extraordinary_income) - $exceptional_extraordinary_expense), 0 );
                    $current_tax = isset($all_item_header_list[$t_date]['current_tax']) ? convert_round_format( $all_item_header_list[$t_date]['current_tax'], 0 ): 0;
                    $deferred_tax = isset($all_item_header_list[$t_date]['deferred_tax']) ? convert_round_format( $all_item_header_list[$t_date]['deferred_tax'], 0 ): 0;
                    $profite_after_tax = convert_round_format ( ($profite_before_tax - ($current_tax + $deferred_tax)), 0 );
                    $retain_dividend_paid = 0;
                    $retain_profit = convert_round_format ( ($profite_after_tax -  $retain_dividend_paid ), 0 );

                    $all_profitloss_report_list['sales']['amount'][$t_date] =  $sales;
                    $all_profitloss_report_list['main_cogs']['amount'][$t_date] =  $main_cogs;
                    $all_profitloss_report_list['gross_profit']['amount'][$t_date] = $gross_profit;
                    $all_profitloss_report_list['employee_benefit_expenses']['amount'][$t_date] = $employee_benefit_expenses;
                    $all_profitloss_report_list['selling_general_and_administrative_expenses']['amount'][$t_date] = $selling_general_and_administrative_expenses;
                    $all_profitloss_report_list['other_expenses']['amount'][$t_date] = $other_expenses;
                    $all_profitloss_report_list['total_expense']['amount'][$t_date] = $total_expenses;
                    $all_profitloss_report_list['ebita']['amount'][$t_date] = $EBITDA;
                    $all_profitloss_report_list['depreciation_amortization']['amount'][$t_date] = $depreciation_amortization;
                    $all_profitloss_report_list['pbit']['amount'][$t_date] = $PBIT;
                    $all_profitloss_report_list['interest_bank_charges']['amount'][$t_date] = $interest_bank_charges;
                    $all_profitloss_report_list['profit_after_interest_tax']['amount'][$t_date] = $profite_interest_tax;
                    $all_profitloss_report_list['other_non_operating_income']['amount'][$t_date] = $other_non_operating_income;
                    $all_profitloss_report_list['other_non_operating_expenses']['amount'][$t_date] = $other_non_operating_expenses;
                    $all_profitloss_report_list['profit_before_exceptional_taxt']['amount'][$t_date] = $profite_before_exceptional_tax;
                    $all_profitloss_report_list['exceptional_extraordinary_income']['amount'][$t_date] = $exceptional_extraordinary_income;   
                    $all_profitloss_report_list['exceptional_extraordinary_expense']['amount'][$t_date] = $exceptional_extraordinary_expense;
                    $all_profitloss_report_list['profit_before_tax']['amount'][$t_date] = $profite_before_tax;
                    $all_profitloss_report_list['current_tax']['amount'][$t_date] = $current_tax;
                    $all_profitloss_report_list['deferred_tax']['amount'][$t_date] = $deferred_tax;
                    $all_profitloss_report_list['profit_after_tax']['amount'][$t_date] = $profite_after_tax;
                    $all_profitloss_report_list['retain_dividend']['amount'][$t_date] = $retain_dividend_paid;
                    $all_profitloss_report_list['retain_profit']['amount'][$t_date] = $retain_profit;
                }
            }              

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

            if ($tbdate_list) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tbdate_list_arr[$tb_header_index]] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
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
            $all_item_rows = [];

            $p_index = 4;
            foreach ( $all_profitloss_report_list as $rp_key => $rp_value ) {
                $all_item_rows[] = 'A' . $p_index;
                $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue($rp_value['label'])->getStyle('A' . $p_index)->applyFromArray(($rp_value['bold'] == true) ? $itemStyle : $subitemStyle);                
                if( isset($rp_value['amount']) && is_array($rp_value['amount']) && count($rp_value['amount']) > 0 ){
                    foreach ( $rp_value['amount'] as $a_index => $amount ) {    
                        $spreadsheet->getActiveSheet()->getCell($data_column_range[$a_index]['column'] . $p_index)->setValue(convert_decimal_format($amount,0))->getStyle($data_column_range[$a_index]['column']. $p_index)->applyFromArray(($rp_value['bold'] == true) ? $itemStyle : $subitemStyle);
                    }      
                }
                $p_index++;
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

    public function getProfitPowerReport(Request $request, $return_data = 0) {
        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $report_type = $request->get('report_type');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

            $pl_query = KPIRecords::select(
                DB::raw("DATE_FORMAT(tbdate, '%Y-%m-%d') as tbdate"),
                'kpi_name',
                DB::raw("SUM(amount) as total")
            );

            if ($isAdmin && $assignUserId != null) {
                if ($assignUserId && $assignUserId !== 'all') {
                    $pl_query->where('kpi_records.user_id', $assignUserId);
                }
            } else {
                $pl_query->where('kpi_records.user_id', $userId);
            }
            $all_dates = [];

            if (!empty($date_filter)) {
                $between_date = explode('/', $date_filter);
                $start_date = trim($between_date[0]);
                $end_date = trim($between_date[1]);
                $all_dates = getMonthStartDates($start_date, $end_date);

                $pl_query->where(
                    function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('kpi_records.tbdate', [$start_date , $end_date ]);
                    }
                );
            }
               //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
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


            if ($tbdate_list) {
                $tb_d_cnt = count($tbdate_list);
                $tb_d_cnt = $tb_d_cnt - 1;
                $tbdate_list_arr = array_values($tbdate_list);
                $tb_header_cell = hg_generate_excel_cell_names($start_row, $tb_d_cnt, $start_col);
                if ($tb_header_cell) {
                    $start_col = end($tb_header_cell);
                    foreach ($tb_header_cell as $tb_header_index => $tb_header_range_index) {
                        if (isset($tbdate_list_arr[$tb_header_index]) && !empty($tbdate_list_arr[$tb_header_index])) {
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => $td_date,
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $column_width_list[str_replace($start_row, '', $tb_header_range_index)] = 125;
                            $data_column_range[$tbdate_list_arr[$tb_header_index]] = array(
                                'column' => str_replace($start_row, '', $tb_header_range_index),
                                //'data' => $tbdate_list_arr[$tb_header_index],
                            );
                        }
                    }

                }
            }
           

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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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

                                    $dataColCell[$p_index][$s_tbdate_column_group['column']] = $s_cell_val;
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
                                            $dataColCell[$p_index][$n_tbdate_column_group['column']] = $n_cell_val;
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



}