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

    public function getBLReport(Request $request, $return_data = 0){

        try {
            $spreadsheet = new Spreadsheet();
            $assignUserId = $request->get('assign_by');
            $date_filter = $request->get('date_filter');
            $user = auth()->user();
            $isAdmin = $user->hasRole('Super Admin');
            $userId = $user->id;

           $profit_lossdata = $this->getPLReport($request, 1);
            $retain_profit_arr = isset($profit_lossdata[26]) ? $profit_lossdata[26] : [];
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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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


            $profit_lossdata = $this->getPLReport($request, 1);
            $retain_profit_arr = isset($profit_lossdata[26]) ? $profit_lossdata[26] : [];
            
            
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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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

                            $extra_rows = $sub_items['label'];
                           
                            $parent_tr_hide = isset($sub_items['parent_tr_hide']) ? 'hide-his-tr' : '';
                            $item_css = '';
                            if (isset($sub_items['sub_row'])) {
                                $first_tr_item .= '<tr class="sub-row ' . $parent_tr_hide . '" data-label="' . $sub_items['sub_row'] . '" data-main-key="' . $sub_items['sub_row'] . '" >';
                                $item_css = 'style="padding-left:60px !important;"';
                            } else {
                                $first_tr_item .= '<tr class="' . $parent_tr_hide . '" >';
                            }
                            
                            $first_tr_item .= '<td ' . $item_css . '>' . $sub_items['label'] . '</td>';

                            $all_item_rows[] = 'A' . $p_index;
                            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];
                            $year_wise_sub_types = $date_wise_sub_types = [];

                            if ($data_column_range) {
                                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {
                                    $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key])) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
                            $second_tr_item .= '<td>'.$total_label.'</td>';
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
                        $query->whereBetween('kpi_records.tbdate', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                    }
                );
            }

            $request->merge([
                'date_filter' => $start_date.' / '.$end_date
            ]);

                 //->whereIn('kpi_name', ['Accounts Payable', 'Accounts Receivable'])
            

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

            $pl_query->groupBy('tbdate', 'kpi_name');
            $blData = $pl_query->get();


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

            $tbdate_list = $all_item_header_list = [];
            foreach ( $blData as $row ) {
                if( date('Y-m',strtotime($start_date)) == date('Y-m',strtotime($row->tbdate)) ){
                    $hide_column = 1;
                }
                $tbdate_list[date('m/d/Y', strtotime($row->tbdate))] = date('m/d/Y', strtotime($row->tbdate));
                $all_item_header_list[date('m/d/Y', strtotime($row->tbdate))][$row->kpi_name] = $row->total;
            }
            
            $finacial_titles = comman_finacial_column_title($all_dates);            
            $year_grouped = $finacial_titles['year_grouped'];
            $quater_grouped = $finacial_titles['quater_grouped'];
            $month_wise_grouped = $finacial_titles['month_wise_grouped'];
            $year_wise_grouped  = $finacial_titles['year_wise_grouped'];
$chart_header = [];

            if ($tbdate_list) {
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

                            
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $chart_header[] = date('M-y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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


            $spreadsheet->getActiveSheet()->getCell('A3')->setValue('Opening Cash')->getStyle('A3')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A4')->setValue('Closing Cash')->getStyle('A4')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Cash Flows')->getStyle('A5')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Cash flows from Operating Activity')->getStyle('A6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('A7')->setValue('Cash flows from Investing Activity')->getStyle('A7')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            // $spreadsheet->getActiveSheet()->getCell('A8')->setValue('Cash flows from Financing Activity')->getStyle('A8')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('Validation')->getStyle('A6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));

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

            $p_index = 8;
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
                array('label' => 'Cash Inflows from Operating Activity', 'sum_row' => ['Trade Receivables' => 8, 'Trade Payables' => 9, 'Inventory' => 10]),
                array('label' => 'Fixed Assets', 'data_keys' => 'Fixed Assets', 'dr_cr' => -1),
                array('label' => 'Cash Inflows from Investing Activity', 'sum_row' => ['Fixed Assets' => 12]),
                array('label' => 'Equity', 'data_keys' => 'Equity', 'dr_cr' => 1),
                array('label' => 'Borrowings', 'data_keys' => 'Bank Loans - Current,Bank Loans - Non Current', 'dr_cr' => 1),
                array('label' => 'Others', 'data_plus_keys' => 'Other Non Current Liabilities,Other Current Liabilities', 'data_minus_keys' => 'Other Non Current Assets,Other Current Assets', 'dr_cr' => -1),
                array('label' => 'Cash Inflows from Financing Activity', 'sum_row' => ['Equity' => 14, 'Borrowings' => 15, 'Others' => 16 ]),
                array('label' => 'Total Cash Flows', 'sum_row' => ['Cash Inflows from Operating Activity' => 11, 'Cash Inflows from Investing Activity' => 13, 'Cash Inflows from Financing Activity' => 17 ]),
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

                        $total_cashflow_amt = isset($final_all_total['Total Cash Flows'][$n_tbdate_column_group['column'] . '18']) ? (float) str_replace(',', '',$final_all_total['Total Cash Flows'][$n_tbdate_column_group['column'] . '18']) : 0;

                        $validation = round($total_cashflow_amt - $cashFlowTotal, 2);

                        $operating_activity = isset($final_all_total['Cash Inflows from Operating Activity'][$n_tbdate_column_group['column'] . '11']) ? $final_all_total['Cash Inflows from Operating Activity'][$n_tbdate_column_group['column'] . '11'] : 0;
                        $investing_activity = isset($final_all_total['Cash Inflows from Investing Activity'][$n_tbdate_column_group['column'] . '13']) ? $final_all_total['Cash Inflows from Investing Activity'][$n_tbdate_column_group['column'] . '13'] : 0;
                        $financing_activity = isset($final_all_total['Cash Inflows from Financing Activity'][$n_tbdate_column_group['column'] . '17']) ? $final_all_total['Cash Inflows from Financing Activity'][$n_tbdate_column_group['column'] . '17'] : 0;


                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'] . '3')->setValue(convert_decimal_format($cashBankPrev))->getStyle($n_tbdate_column_group['column'].'3')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'4')->setValue(convert_decimal_format($cashBankCurrent))->getStyle($n_tbdate_column_group['column'].'4')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'5')->setValue(convert_decimal_format($cashFlowTotal))->getStyle($n_tbdate_column_group['column'].'5')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        // $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'6')->setValue(convert_decimal_format($operating_activity))->getStyle($n_tbdate_column_group['column'].'6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        // $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'7')->setValue(convert_decimal_format($investing_activity))->getStyle($n_tbdate_column_group['column'].'7')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        // $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'8')->setValue(convert_decimal_format($financing_activity))->getStyle($n_tbdate_column_group['column'].'8')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));
                        $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group['column'].'6')->setValue(convert_decimal_format($validation))->getStyle($n_tbdate_column_group['column'].'6')->applyFromArray(hg_comman_customer_header_style('#00FF00', 14, '000000'));

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
                    'chart_header' => $chart_header,
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
                            $tb_item = $tbdate_list_arr[$tb_header_index][0];
                            $td_date = date('m/d/Y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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


                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
            $chart_header = [];
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
                            $chart_header[] = date('M-y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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
                           
                            $extra_rows = $sub_items['label'];
                           
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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
                            $second_tr_item .= '<td>'.$total_label.'</td>';
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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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


                                     $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key])) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
$chart_header = [];

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
                            $chart_header[] = date('M-y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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

           // $revenue_arr = isset($final_all_total['Sales']) ? array_values($final_all_total['Sales']) : [];
           // $cogs_arr = isset($final_all_total['COGS']) ? array_values($final_all_total['COGS']) : [];

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
                    'chart_header' => $chart_header,
                    'cogs_arr' => [],
                    'revenue_arr' => [],
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

            $chart_header = [];

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
                            $chart_header[] = date('M-y', strtotime($tbdate_list_arr[$tb_header_index]));
                            $tb_header[$tb_header_range_index] = [
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key])) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
            $spreadsheet->getActiveSheet()->getCell('A' . $p_index)->setValue('A/R Days')->getStyle('A' . $p_index)->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A5')->setValue('Inventory Days')->getStyle('A5')->applyFromArray($subitemStyle);
            $spreadsheet->getActiveSheet()->getCell('A6')->setValue('AP Days')->getStyle('A6')->applyFromArray($subitemStyle);
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
                    'chart_header' => $chart_header,
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
$chart_header = [];
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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $chart_header[] = date('M-y', strtotime($tbdate_list_arr[$tb_header_index]));
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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
                    'chart_header' => $chart_header,
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
            $chart_header = [];
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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
                                'style' => hg_comman_customer_header_style('bfbfbf')
                            ];
                            $chart_header[] = date('M-y', strtotime($tbdate_list_arr[$tb_header_index]));
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


                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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
                    'chart_header' => $chart_header,
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

            $item_list = [ 'Total Score', 'Current Ratio', 'Quick Ratio', 'Debt-to-Equity', 'Asset Turnover', 'ROE', 'ROA', 'Gross Margin', 'Net Margin', 'Interest Coverage', 'Receivables Days', 'Payable Days', 'Working Capital Days', 'Operating CF Margin', 'CF Coverage', 'CF to Debt', 'Capex Coverage'];

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

                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '5')->setValue($current_ratio_res['result'])->getStyle($n_tbdate_column_group . '5')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '6')->setValue($quick_ratio_res['result'])->getStyle($n_tbdate_column_group . '6')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '7')->setValue($debt_equity_res['result'])->getStyle($n_tbdate_column_group . '7')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '8')->setValue($asset_tunover_res['result'])->getStyle($n_tbdate_column_group . '8')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '9')->setValue($roe_res['result'])->getStyle($n_tbdate_column_group . '9')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '10')->setValue($rot_res['result'])->getStyle($n_tbdate_column_group . '10')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '11')->setValue($gross_margin_per_res['result'])->getStyle($n_tbdate_column_group . '11')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '12')->setValue($net_margin_per_res['result'])->getStyle($n_tbdate_column_group . '12')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '13')->setValue($Interest_Cover_res['result'])->getStyle($n_tbdate_column_group . '13')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '14')->setValue($rec_days_res['result'])->getStyle($n_tbdate_column_group . '14')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '15')->setValue($pay_days_res['result'])->getStyle($n_tbdate_column_group . '15')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '16')->setValue($work_days_res['result'])->getStyle($n_tbdate_column_group . '16')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '17')->setValue($Operating_CF_Margin_res['result'], )->getStyle($n_tbdate_column_group . '17')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '18')->setValue($Cash_Flow_Coverage_res['result'])->getStyle($n_tbdate_column_group . '18')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '19')->setValue($Cash_Flow_Debt_res['result'])->getStyle($n_tbdate_column_group . '19')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '20')->setValue($Capex_Coverage_res['result'])->getStyle($n_tbdate_column_group . '20')->applyFromArray($itemStyle);
                    $spreadsheet->getActiveSheet()->getCell($n_tbdate_column_group . '4')->setValue($final_score)->getStyle($n_tbdate_column_group . '4')->applyFromArray($itemStyle);

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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

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

            $tbdate_list = $item_tbdate_list = $item_header_list = $all_item_header_list = [];

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
                                'label' => date('M-y', strtotime($tbdate_list_arr[$tb_header_index])),
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

                                    $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key]) ) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;

                                    $cell_val = round($cell_val, 2);

                                    if ($sub_items['label'] == 'Current Year Profit') {
                                        $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',', '', $final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                                        if ($retain_total == null) {
                                            $retain_total = 0;
                                        }
                                        $cell_val = $cell_val + (float) $retain_total;
                                        $cell_val = round($cell_val, 2);
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

}