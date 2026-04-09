<?php

use Spatie\Permission\Models\Role;
use DB as DB;
use Validator as Validator;
use File as File;
use App\Models\Attachments;
use App\Models\UserMeta;
use App\Models\ChartAccount;
use Carbon\Carbon;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

function hg_check_permission_flag( $permission_name = '' , $authUserData = [] ){
  $flag = 0;
  if( $authUserData && is_array( $authUserData ) && in_array( $permission_name , $authUserData) ){
    $flag = 1;
  }
  return $flag;
}

function hg_get_all_permission_by_user(){
  $authUserData = [];
   $authUserData = Role::Join("role_has_permissions", "role_has_permissions.role_id", "=", "roles.id")
  ->join("permissions","permissions.id","=","role_has_permissions.permission_id")
  ->join("model_has_roles","model_has_roles.role_id","=","roles.id")
  ->where( "model_has_roles.model_id", "=", auth()->user()->id )
  ->pluck("permissions.name")
  ->toArray();
  return $authUserData;
}

function hg_check_permission_flag_by_user( $permission_name = '' ){
  $flag = 0;
  $authUserData = hg_get_all_permission_by_user();
  if( $authUserData && is_array( $authUserData ) && in_array( $permission_name , $authUserData) ){
    $flag = 1;
  }
  return $flag;
}
function array_minus( $array ) {
    $array = array_values($array); // reindex in case it's associative
    $result = array_shift($array); // take the first value
    foreach ($array as $value) {
        $result -= $value;
    }
    return $result;
}
function generateAccountCode( $tableName, $columnName = 'account_code', $prefix = 'ACC', $userId = 0 ){
   
    $year = Carbon::now()->year;
    $records = DB::table($tableName)
        ->where('user_id', $userId)
        ->where($columnName, 'like', $prefix . '%' . $year)
        ->pluck($columnName);

    // Get the highest number from the existing codes
    $maxNumber = 0;

    foreach ($records as $code) {
        $numberPart = substr($code, strlen($prefix), 6); // Extract 6 digits
        $number = (int) $numberPart;
        if ($number > $maxNumber) {
            $maxNumber = $number;
        }
    }

    $nextNumber = str_pad($maxNumber + 1, 6, '0', STR_PAD_LEFT);

    return $prefix . $nextNumber . $year;

}

function update_user_meta( $user_id, $meta_key, $meta_value ) {
    return UserMeta::updateOrCreate(
        [
            'user_id' => $user_id,
            'meta_key' => $meta_key,
        ],
        [
            'meta_value' => $meta_value,
        ]
    );
}


function get_user_meta($user_id, $meta_key = null, $single = true) {
  if ($meta_key) {
    $query = UserMeta::where('user_id', $user_id)
    ->where('meta_key', $meta_key);

    if ($single) {
      $meta = $query->first();
      return $meta ? $meta->meta_value : null;
    }

    return $query->pluck('meta_value')->toArray();
  }

// Return all meta for user if no key is provided
  return UserMeta::where('user_id', $user_id)
  ->pluck('meta_value', 'meta_key')
  ->toArray();
}

function create_csv_file_outstream( $main_header, $not_import_data, $only_header = 'no' ){
    $csv_file_data = '';
    ob_start();
    if( is_array( $not_import_data ) && count($not_import_data) > 0 || $only_header == 'yes' ){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=LeadDataNotImport.csv');
        $output = fopen("php://output", "w");
        if( is_array($main_header) && count($main_header) > 0 ){
            $data_arr = $main_header;
            fputcsv( $output, $data_arr );    
        }     
        if( $only_header == 'no' ){
            foreach( $not_import_data as $csv_download ){       
                fputcsv($output, $csv_download );       
            }    
        }
        fclose($output);
    }
    $csv_file_data = ob_get_clean();
    return  $csv_file_data ;
}

function convert_decimal_format( $value, $decimal = 2 ){
    return number_format($value,$decimal);
}
function convert_round_format( $value, $decimal = 2 ){
    return round($value,$decimal);
}

function convertDateToYMD($dateString, $inputFormat) {
    $formatMap = [
        'dd-mm-yyyy' => 'd-m-Y',
        'mm-dd-yyyy' => 'm-d-Y',
        'yyyy-mm-dd' => 'Y-m-d',
        'dd/mm/yyyy' => 'd/m/Y',
        'mm/dd/yyyy' => 'm/d/Y',
        'yyyy/mm/dd' => 'Y/m/d',
        'dd.mm.yyyy' => 'd.m.Y',
        'mm.dd.yyyy' => 'm.d.Y',
        'yyyy.mm.dd' => 'Y.m.d',
        'dd Month yyyy' => 'd F Y',
        'Month dd, yyyy' => 'F d, Y',
        'dd-Mon-yy' => 'd-M-y', 
        'dd-Mon-yyyy' => 'd-M-Y',
        'dd-MMM-yy' => 'd-M-y',
        'yyyy Month dd' => 'Y F d',
        'Day, dd Month yyyy' => 'l, d F Y',
        'Day, Month dd, yyyy' => 'l, F d, Y',
        'Month yyyy' => 'F Y',
        'yyyy' => 'Y',
        'mm-yy' => 'm-Y',
        'yy-mm' => 'Y-m',
        'mm.yy' => 'm.Y',
        'yy.mm' => 'Y.m',
        'mm/yyyy' => 'm/Y',
        'yyyy/mm' => 'Y/m',
        'mm-yyyy' => 'm-Y',
        'yyyy-mm' => 'Y-m',
        'yy/mm' => 'Y/m',
        'yy-mm' => 'Y-m',
        'mm/yy' => 'm/y',
        'mm yy' => 'm Y',
        'yy mm' => 'Y m',
        'yyyy.mm' => 'Y.m',
        'mm.yyyy' => 'm.Y',
        'Mon-yy' => 'M-Y',
        'yy-Mon' => 'Y-M',
        'Mon.yy' => 'M.y',
        'yy.Mon' => 'Y.M',
        'Mon yy' => 'M Y',
        'yy Mon' => 'Y M',
        'Month-yyyy' => 'F-Y',
        'yyyy-Month' => 'Y-F',
        'Month.yyyy' => 'F.Y',
        'yyyy.Month' => 'Y.F',
        'Mon-yyyy' => 'M-Y',
        'yyyy-Mon' => 'Y-M',
        'Mon.yyyy' => 'M.Y',
        'yyyy.Mon' => 'Y.M',
        'Mon yy' => 'M Y',
        'yy Mon' => 'Y M',
        'Month yyyy' => 'F Y',
        'yyyy Month' => 'Y F',
    ];

     if (!isset($formatMap[$inputFormat])) {
        return false; // Unsupported format
    }

    $phpFormat = $formatMap[$inputFormat];
    $date = DateTime::createFromFormat($phpFormat, $dateString);

    if (!$date) {
        return false; // Invalid date string
    }

    // Special logic for partial month-year inputs: default to last day of month
    $partialFormats = ['F Y', 'F-Y', 'Y-F', 'M Y', 'M-Y', 'Y M', 'Y-M', 'M.y', 'Y.M', 'M-y', 'y-M'];

    if (in_array($phpFormat, $partialFormats)) {
        // Get year and month, set date to last day of that month
        $year = (int)$date->format('Y');
        $month = (int)$date->format('m');
        $lastDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
        // Return in Y-m-d format (e.g., 2025-01-31)
        return sprintf('%04d-%02d-%02d', $year, $month, $lastDay);
    }
    return $date->format('Y-m-d'); // Default full date parsing
}


function hg_generate_excel_cell_names($row_cnt, $col_cnt,$start_col = 'A'){
  $excel_cells = [];
  $excel_col = $start_col;
  $excel_row = $row_cnt;
  for ($col_index = 0; $col_index <= $col_cnt; $col_index++)
  {
    $excel_cells[$col_index] = $excel_col.$excel_row;
    $excel_col++;
  }
  return $excel_cells;
}
function hg_only_get_next_excel_cell( $col = 'A') {
    // Move to next column
    $nextCol = ++$col; 
    
    // Build the cell name
    return $nextCol;
}

function hg_get_next_excel_cell_names( $row_cnt, $start_col = 'A', $extra_step = 0 ){
  $excel_cells = '';
  $excel_col = $start_col;
  if( $extra_step ){
    for ($col_index = 0; $col_index <= $extra_step; $col_index++){      
      $excel_col++;
    }    
  }else{
    $excel_col++;  
  }  
  
  $excel_row = $row_cnt;
  $excel_cells = $excel_col.$excel_row;
  return $excel_cells;
}

function hg_get_prev_excel_cell_names( $row_cnt, $start_col = 'A', $extra_step = 0 ){
  $excel_cells = '';
  $excel_col = $start_col;

  if( $extra_step ){
    for ($col_index = 0; $col_index <= $extra_step; $col_index++){      
      $excel_col--;
    }    
  }else{
    $excel_col--;
  }    
  $excel_row = $row_cnt;
  $excel_cells = $excel_col.$excel_row;
  return $excel_cells;
}

function hg_get_prev_excel_cell(  $start_col = 'A', $extra_step = 0 ){
    $excel_cells = '';
    $excel_col = $start_col;
    if( $extra_step ){
        for ($col_index = 0; $col_index <= $extra_step; $col_index++){      
            $excel_col--;
        }    
    }else{
        $excel_col--;
    }    
    $excel_cells = $excel_col;
    return $excel_cells;
}

function hg_comman_customer_header_style( $colorCode = '',  $font_size = 11, $color = '000000' ){
    $styleArray = array(
        'font'  => [
            'size'  => $font_size,     
            'bold' => true,
            'color' => ['argb' => $color ],
        ],
        'alignment' => [                    
            'vertical' => Alignment::VERTICAL_CENTER,                    
            'horizontal' => Alignment::HORIZONTAL_CENTER,    
            'wrapText' => true                
        ]
    );
    if( $colorCode ){
        $styleArray['fill'] = [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => str_replace( '#', '', $colorCode ),
            ],
        ];
    }
    return $styleArray;
}

function getMonthEndDates($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('last day of this month');

    $dates = [];

    while ($start <= $end) {
        $monthEnd = clone $start;
        $monthEnd->modify('last day of this month');
        $dates[] = $monthEnd->format('m/d/Y'); // US format as you used earlier
        $start->modify('first day of next month');
    }

    return $dates;
}
function getMonthStartDates($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('last day of this month');

    $dates = [];

    while ($start <= $end) {
        $monthStart = clone $start;
        $monthStart->modify('first day of this month');
        $dates[] = $monthStart->format('m/d/Y');

        $start->modify('first day of next month');
    }

    return $dates;
}
function getAllMonthDates($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
   // $end->modify('last day of this month');
    $dates = [];
    while ($start <= $end) {
        $dates[] = $start->format('m/d/Y'); // US format
        $start->modify('+1 day');
    }
    return $dates;
}

function replace_space_to_dash( $value='' ){
    $value = urlencode( $value );
    return $value;
}

function generateTableRowHtml( $balance_sheet_names, $sub_items_label, $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $all_item_header_list, $month_wise_grouped, $year_wise_grouped ) {
    $sub_label_str = '';
    if( isset($balance_sheet_names[ $sub_items['label']]) && is_array($balance_sheet_names[ $sub_items['label']]) && count($balance_sheet_names[ $sub_items['label']]) > 0 ){
        foreach ( $balance_sheet_names[ $sub_items['label']] as $inner_label => $inner_value ) {
            $sub_label_str .= '<tr class="sub-row" data-label="'.$sub_key.'">';
            $sub_label_str .= '<td>' . $inner_label. '<a class="show-acc-edit" href="/chartaccount?search_value='.replace_space_to_dash( $inner_label ).'" target="_blank"><i class="ri-pencil-line"></i></a></td>';
            $year_wise_sub_types = $date_wise_sub_types = [];            

            if ( $data_column_range ) {
                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {
                    if ($sub_items['pl'] == true) {
                        $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_items_label][$inner_label]['data']) && count($all_item_header_list[$tbdate_index][$sub_items_label][$inner_label]['data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items_label][$inner_label]['data']) : 0;
                    } else {
                        $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_items_label][$inner_label]['closing_data']) && count($all_item_header_list[$tbdate_index][$sub_items_label][$inner_label]['closing_data']) > 0) ? array_sum($all_item_header_list[$tbdate_index][$sub_items_label][$inner_label]['closing_data']) : 0;
                    }
                    $cell_val = round($cell_val, 2);
                    $sub_label_str .= '<td>' . convert_decimal_format($cell_val) . '</td>';
                    $formatted_date = date('m/d/Y', strtotime($tbdate_index));
                    $month_key = $month_wise_grouped[$formatted_date];
                    $year_key = $year_wise_grouped[$formatted_date];
                    $date_wise_sub_types[$month_key][] = $cell_val;
                    $year_wise_sub_types[$year_key][] = $cell_val;
                }
            }
            if ($qt_data_column_range) {
                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                    $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                    $q_cell_val = round($q_cell_val, 2);
                    $sub_label_str .= '<td>' . convert_decimal_format($q_cell_val) . '</td>';
                }
            }
            if ($year_data_column_range) {
                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                    $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0)  ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                    $y_cell_val = round($y_cell_val, 2);
                    $sub_label_str .= '<td>' . convert_decimal_format($y_cell_val) . '</td>';
                }
            }
            $sub_label_str .= '</tr>';
        }
    }
    
    return $sub_label_str;
}

function children_all_data_with_parent( $balance_sheet_names, $children_item = [] ) {
    $main_items = [];
    if( is_array( $children_item ) && count( $children_item ) > 0 ){
        foreach ( $children_item as $s_key => $s_value ) {
            $sub_m_items = isset($balance_sheet_names[$s_value['id']]) ? $balance_sheet_names[$s_value['id']] : [];
            if( count($sub_m_items) > 0 ){
                $main_items[] = $sub_m_items;
            }
            if( isset($s_value['children']) && is_array($s_value['children']) && count($s_value['children']) > 0 ){             
                $children_data_items = children_all_data_with_parent( $balance_sheet_names, $s_value['children'] );
                if( count($children_data_items) > 0 ){
                    $main_items = array_merge( $main_items, $children_data_items );
                }
            }
        }
    }
    return $main_items;
}

function children_all_data_with_parent_filter( $filter_data = [] ) {
    $comman_main_items = [];

    if( is_array( $filter_data ) && count( $filter_data ) > 0 ){
        foreach ( $filter_data as $s_key => $s_value ) {
            foreach ( $s_value as $n_key => $n_value ) {
                if( isset($n_value['data']) ){
                    $comman_main_items[$n_key]['data'] = isset($comman_main_items[$n_key]['data']) ? array_merge($comman_main_items[$n_key]['data'], $n_value['data']) : $n_value['data'];
                }
                if( isset($n_value['closing_data']) ){                    
                    $comman_main_items[$n_key]['closing_data'] = isset($comman_main_items[$n_key]['closing_data']) ? array_merge($comman_main_items[$n_key]['closing_data'],$n_value['closing_data']) : $n_value['closing_data'];
                }
                if( isset($n_value['final_closing']) ){                    
                    $comman_main_items[$n_key]['final_closing'] = isset($comman_main_items[$n_key]['final_closing']) ? array_merge($comman_main_items[$n_key]['final_closing'],$n_value['final_closing']) : $n_value['final_closing'];
                }
                if( isset($n_value['total_closing']) ){                    
                    $comman_main_items[$n_key]['total_closing'] = isset($comman_main_items[$n_key]['total_closing']) ? array_merge($comman_main_items[$n_key]['total_closing'],$n_value['total_closing']) : $n_value['total_closing'];
                }
            }
        }
    }
    return $comman_main_items;
}

function hg_rm_special_string( $string ='' ){
    $string = trim($string);
    $string = strtolower($string);
    $string = str_replace(' ', '-', $string);    
    $string = preg_replace('/[^A-Za-z0-9&\-]/', '', $string);  
    $string = preg_replace('/\s+/', ' ', $string);    
    $string = str_replace(' ', '-',$string);
    return $string;
}

function setTableDataWithChildParent( $balance_sheet_names, $category_wise_all_items, $sub_items_label, $sub_key, $sub_items, $data_column_range, $qt_data_column_range, $year_data_column_range, $all_item_header_list, $month_wise_grouped, $year_wise_grouped ) {
    $sub_label_str = '';
    
    if( isset($category_wise_all_items[$sub_key]) && is_array($category_wise_all_items[$sub_key]) && count($category_wise_all_items[$sub_key]) > 0 ){
        foreach ( $category_wise_all_items[$sub_key] as $inner_label => $inner_value ) {
            $sub_row_key = isset($sub_items['sub_row']) ? $sub_items['sub_row'] : $sub_key;
            $inner_sub_key = hg_rm_special_string($inner_value['gl_name']);
            $sub_label_str .= '<tr class="sub-row" data-label="'.$sub_key.'" data-main-key="'.$sub_row_key.'">';
            $child_label = $inner_value['gl_name'];
            if( is_array($inner_value['children']) && count($inner_value['children']) > 0 ){
                $child_label = '<a href="javascript:;" class="extra-row-btn" data-key="'.$inner_sub_key.'"><i class="ri-add-circle-line"></i>'. $inner_value['gl_name'] .'</a>';
            }
            $item_css_str = 'style="padding-left:60px !important;"';
            if( isset($sub_items['sub_row']) && !isset($sub_items['parent_tr_hide']) ){
                $item_css_str = 'style="padding-left:120px !important;"';
            }


            $sub_label_str .= '<td '.$item_css_str.'>' . $child_label. '<a class="show-acc-edit" href="/chartaccount?search_value='.replace_space_to_dash( $inner_value['gl_name'] ).'" target="_blank"><i class="ri-pencil-line"></i></a></td>';
            $year_wise_sub_types = $date_wise_sub_types = [];                                    
            $data_items = isset($balance_sheet_names[$inner_value['id']]) ? $balance_sheet_names[$inner_value['id']] : [];

            if( is_array($inner_value['children']) && count($inner_value['children']) > 0 ){
                $data_items =  children_all_data_with_parent( $balance_sheet_names, $inner_value['children'] );               
                $data_items =  children_all_data_with_parent_filter( $data_items );    
            }

            $decimal_4 = 0;
            if( isset($inner_value['decimal']) && $inner_value['decimal'] == true ){
                $decimal_4 = 2;
            }


            if ( $data_column_range ) {
                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {

                    if( isset($sub_items['r_type']) ){
                        if( $sub_items['r_type'] == 'pl' ){
                            if ($sub_items['pl'] == true) {
                                $cell_val = (isset($data_items[$tbdate_index]['data']) && count($data_items[$tbdate_index]['data']) > 0) ? array_sum($data_items[$tbdate_index]['data']) : 0;
                            } else {
                                $cell_val = (isset($data_items[$tbdate_index]['closing_data']) && count($data_items[$tbdate_index]['closing_data']) > 0) ? array_sum($data_items[$tbdate_index]['closing_data']) : 0;
                            }
                        }else{
                            if ($sub_items['pl'] == true) {
                                $cell_val = (isset($data_items[$tbdate_index]['total_closing']) && count($data_items[$tbdate_index]['total_closing']) > 0) ? array_sum($data_items[$tbdate_index]['total_closing']) : 0;
                            } else {
                                $cell_val = (isset($data_items[$tbdate_index]['final_closing']) && count($data_items[$tbdate_index]['final_closing']) > 0) ? array_sum($data_items[$tbdate_index]['final_closing']) : 0;
                            }
                        } 
                    }else{
                        if ($sub_items['pl'] == true) {
                            $cell_val = (isset($data_items[$tbdate_index]['data']) && count($data_items[$tbdate_index]['data']) > 0) ? array_sum($data_items[$tbdate_index]['data']) : 0;
                        } else {
                            $cell_val = (isset($data_items[$tbdate_index]['closing_data']) && count($data_items[$tbdate_index]['closing_data']) > 0) ? array_sum($data_items[$tbdate_index]['closing_data']) : 0;
                        }
                    }

                    $cell_val = round($cell_val, 2);
                    //echo  $child_label.'--'.$cell_val;die;
                    $sub_label_str .= '<td>' . convert_decimal_format($cell_val,$decimal_4) . '</td>';
                    $formatted_date = date('m/d/Y', strtotime($tbdate_index));
                    $month_key = $month_wise_grouped[$formatted_date];
                    $year_key = $year_wise_grouped[$formatted_date];
                    $date_wise_sub_types[$month_key][] = $cell_val;
                    $year_wise_sub_types[$year_key][] = $cell_val;
                }
            }
            $check_bl_flag = 0;
            if( isset($sub_items['bl_rule']) && $sub_items['bl_rule'] == true ){
                $check_bl_flag = 1;
            }

            if ($qt_data_column_range) {
                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {
                    if( $check_bl_flag == 1 ){                        
                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                    }else{
                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                    }
                    
                    $q_cell_val = round($q_cell_val, 2);
                    $sub_label_str .= '<td>' . convert_decimal_format($q_cell_val,$decimal_4) . '</td>';
                }
            }
            if ($year_data_column_range) {
                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);
                    if( $check_bl_flag == 1 ){
                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                    }else{
                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0)  ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                    }
                    $y_cell_val = round($y_cell_val, 2);
                    $sub_label_str .= '<td>' . convert_decimal_format($y_cell_val,$decimal_4) . '</td>';
                }
            }
            $sub_label_str .= '</tr>';
            
            
            $children_str = set_row_item_with_children( $balance_sheet_names, $sub_items, $inner_value['children'], $inner_sub_key, $sub_row_key, isset($sub_items['sub_row']) && !isset($sub_items['parent_tr_hide'])  ? 160 : 100, $data_column_range, $qt_data_column_range, $year_data_column_range, $month_wise_grouped, $year_wise_grouped );

            if( $children_str ){
                $sub_label_str .= $children_str;
            }
        }
    }
    
    return $sub_label_str;
}

function set_row_item_with_children( $balance_sheet_names, $sub_items, $children = [], $sub_key = '', $main_key = '' , $default_padding = 100,  $data_column_range, $qt_data_column_range, $year_data_column_range, $month_wise_grouped, $year_wise_grouped ) {
    $new_sub_str = '';
    if( is_array($children) && count($children) > 0 ){
        foreach ( $children as $key => $value ) {
            $new_sub_str .= '<tr class="sub-row" data-label="'.$sub_key.'" data-main-key="'.$main_key.'">';
            
            $inner_child_label = $value['gl_name'];

            $decimal_5 = 0;
            if( isset($value['decimal']) && $value['decimal'] == true ){
                $decimal_5 = 2;
            }

            
            $inner_sub_key = hg_rm_special_string($value['gl_name']);
            if( is_array($value['children']) && count($value['children']) > 0 ){
                $inner_child_label = '<a href="javascript:;" class="extra-row-btn" data-key="'.$inner_sub_key.'"><i class="ri-add-circle-line"></i>'. $value['gl_name'] .'</a>';
            }
            
            $new_sub_str .= '<td style="padding-left:'.$default_padding.'px !important;">' . $inner_child_label . '<a class="show-acc-edit" href="/chartaccount?search_value='.replace_space_to_dash( $value['gl_name'] ).'" target="_blank"><i class="ri-pencil-line"></i></a></td>';
            $data_items = isset($balance_sheet_names[$value['id']]) ? $balance_sheet_names[$value['id']] : [];
            $year_wise_sub_types = $date_wise_sub_types = [];   
            if( is_array($value['children']) && count($value['children']) > 0 ){
                $data_items =  children_all_data_with_parent( $balance_sheet_names, $value['children'] );               
                $data_items =  children_all_data_with_parent_filter( $data_items );               
            }

            if ( $data_column_range ) {
                foreach ($data_column_range as $tbdate_index => $tbdate_column_group) {                    
                   
                    if( isset($sub_items['r_type']) ){
                        if( $sub_items['r_type'] == 'pl' ){
                            if ($sub_items['pl'] == true) {
                                $cell_val = (isset($data_items[$tbdate_index]['data']) && count($data_items[$tbdate_index]['data']) > 0) ? array_sum($data_items[$tbdate_index]['data']) : 0;
                            } else {
                                $cell_val = (isset($data_items[$tbdate_index]['closing_data']) && count($data_items[$tbdate_index]['closing_data']) > 0) ? array_sum($data_items[$tbdate_index]['closing_data']) : 0;
                            }
                        }else{
                            if ($sub_items['pl'] == true) {
                                $cell_val = (isset($data_items[$tbdate_index]['total_closing']) && count($data_items[$tbdate_index]['total_closing']) > 0) ? array_sum($data_items[$tbdate_index]['total_closing']) : 0;
                            } else {
                                $cell_val = (isset($data_items[$tbdate_index]['final_closing']) && count($data_items[$tbdate_index]['final_closing']) > 0) ? array_sum($data_items[$tbdate_index]['final_closing']) : 0;
                            }
                        } 
                    }else{
                        if ($sub_items['pl'] == true) {
                            $cell_val = (isset($data_items[$tbdate_index]['data']) && count($data_items[$tbdate_index]['data']) > 0) ? array_sum($data_items[$tbdate_index]['data']) : 0;
                        } else {
                            $cell_val = (isset($data_items[$tbdate_index]['closing_data']) && count($data_items[$tbdate_index]['closing_data']) > 0) ? array_sum($data_items[$tbdate_index]['closing_data']) : 0;
                        }
                    }

                    $cell_val = round($cell_val, 2);
                    $new_sub_str .= '<td>' . convert_decimal_format($cell_val,$decimal_5) . '</td>';
                    $formatted_date = date('m/d/Y', strtotime($tbdate_index));
                    $month_key = $month_wise_grouped[$formatted_date];
                    $year_key = $year_wise_grouped[$formatted_date];
                    $date_wise_sub_types[$month_key][] = $cell_val;
                    $year_wise_sub_types[$year_key][] = $cell_val;
                }
            }

            $check_bl_flag = 0;
            if( isset($sub_items['bl_rule']) && $sub_items['bl_rule'] == true ){
                $check_bl_flag = 1;
            }

            if ($qt_data_column_range) {
                foreach ($qt_data_column_range as $qt_index => $qt_column_group) {

                    if( $check_bl_flag == 1 ){                        
                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? $date_wise_sub_types[$qt_index][array_key_last($date_wise_sub_types[$qt_index])] : 0;
                    }else{
                        $q_cell_val = (isset($date_wise_sub_types[$qt_index]) && count($date_wise_sub_types[$qt_index]) > 0) ? array_sum($date_wise_sub_types[$qt_index]) : 0;
                    }

                    $q_cell_val = round($q_cell_val, 2);
                    $new_sub_str .= '<td>' . convert_decimal_format($q_cell_val,$decimal_5) . '</td>';
                }
            }
            if ($year_data_column_range) {
                foreach ($year_data_column_range as $yrt_index => $yrt_column_group) {
                    $r_yrt_index = str_replace('YTD-', '', $yrt_index);

                    if( $check_bl_flag == 1 ){
                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0) ? $year_wise_sub_types[$r_yrt_index][array_key_last($year_wise_sub_types[$r_yrt_index])] : 0;
                    }else{
                        $y_cell_val = (isset($year_wise_sub_types[$r_yrt_index]) && count($year_wise_sub_types[$r_yrt_index]) > 0)  ? array_sum($year_wise_sub_types[$r_yrt_index]) : 0;
                    }
                                        
                    $y_cell_val = round($y_cell_val, 2);
                    $new_sub_str .= '<td>' . convert_decimal_format($y_cell_val,$decimal_5) . '</td>';
                }
            }

            $new_sub_str .= '</tr>'; 
            if( isset($value['children']) && is_array($value['children']) && count($value['children']) > 0 ){
                $new_sub_str .= set_row_item_with_children( $balance_sheet_names, $sub_items, $value['children'], $inner_sub_key, $main_key, ($default_padding + 40),  $data_column_range, $qt_data_column_range, $year_data_column_range, $month_wise_grouped, $year_wise_grouped );
            }       
        }
    }
    return $new_sub_str;
}


function get_data_with_parent_child_rel( $items = [], $parentId = 0) {
    $branch = [];
    foreach ( $items as $element ) {
        if ( $element['parent_id'] == $parentId ) {
            $children = get_data_with_parent_child_rel( $items, $element['id'] );
            if ( $children ) {
                $element['children'] = $children;
            }else{
                $element['children'] = [];
            }
            $branch[] = $element;
        }
    }
    return $branch;
}

function get_data_with_category_wise( $items = [] ) {
    $new_branch = [];
    foreach ( $items as $item ) {
        $new_branch[$item['type']][] = $item;
    }
    return $new_branch;
}

function get_all_parent_list( $parent_id_list, $all_paren_items = [] ){
    $parentData = ChartAccount::whereIN('id', $parent_id_list )->get();    
    $m_parent_id = [];    
    if( $parentData->count() > 0 ){
        foreach ( $parentData->toArray() as $parentitem ) {
            $itemData = [
                'id' => $parentitem['id'],
                'code' => $parentitem['code'],
                'gl_name' => $parentitem['gl_name'],
                'parent_id' => $parentitem['parent_id'],
                'category' => $parentitem['category'],
                'type' => $parentitem['type'],
            ];
            $all_paren_items[$parentitem['id']] =  $itemData;
            if( $parentitem['parent_id'] > 0 && !in_array($parentitem['parent_id'], $m_parent_id) ){                                
                $m_parent_id[] = $parentitem['parent_id'];
            }
        }  
    }
    if( is_array($m_parent_id) && count($m_parent_id) > 0 ){
        $all_paren_items = get_all_parent_list( $m_parent_id, $all_paren_items );
    }
    return $all_paren_items;
}

function comman_growth_formula( $c18 = 0, $d18 = 0, $decimal = 2 ) {
    // Avoid division by zero or invalid operation
    if ($c18 == 0) {
        $result = 0;
    } else {
        $result = ($d18 - $c18) / ($c18 / 100); // divide by C18 as percent
    }
    $result = round( $result, $decimal );
    return $result;
}

function comman_module_formula( $d18 = 0, $d22 = 0, $decimal = 2 ) {
    if ($d18 == 0) {
        $result = 0;
    } else {
        $result = $d22 / ($d18 / 100);
    }
    $result = round( $result, $decimal );
    return $result;
}

function comman_cashmg_formula( $input_1 = 0, $input2 = 0, $input_3 = 0, $month_cnt = 12, $decimal ){        
    if ( $input_1 != 0) {
        $result = ($input2 / $input_1) * 365 * ( $month_cnt / 12 );
    } else {
        $result = 0;
    }
    $result = round( $result, $decimal );
    return $result;
}

function give_bs_category_weight( $ratio_name = '', $ratio_val = 0, $single_industry_list = [], $opposite = 0 ){
     $sub_ratio_data = isset($single_industry_list[$ratio_name]) ? $single_industry_list[$ratio_name] : [];
     $score = 0;
     $max_ratio = isset($sub_ratio_data->max_ratio) ? $sub_ratio_data->max_ratio : 0;
     $min_ratio = isset($sub_ratio_data->min_ratio) ? $sub_ratio_data->min_ratio : 0;
     
     if (isset($sub_ratio_data->max_ratio) && (float)$ratio_val >= (float)$sub_ratio_data->max_ratio) {
        $result = "STRONG";
        $score = $sub_ratio_data->strong;
        if( $opposite == 1 ){
            if( $result == 'STRONG' ){
                $result = "SHAKY";
                $score = $sub_ratio_data->shaky;
            }
        }
     }else if ( isset($sub_ratio_data->min_ratio, $sub_ratio_data->max_ratio) && (float)$ratio_val >= (float)$sub_ratio_data->min_ratio &&   (float)$ratio_val < (float)$sub_ratio_data->max_ratio ){

        $result = "STEADY";
        $score = $sub_ratio_data->steady;
    }else{
        $result = "SHAKY";
        $score = $sub_ratio_data->shaky;
        
        if( $opposite == 1 ){
            if( $result == 'SHAKY' ){
                $result = "STRONG";
                $score = $sub_ratio_data->strong;
            }
        }
    }
    return  [ 'result' => $result, 'score' => $score ];
    //return  [ 'result' => $result.'---'.'ratio :'.$ratio_val.'--score:'.$score.'--min_ratio:'.$min_ratio.'--max_ratio:'.$max_ratio, 'score' => $score ];
    //return  $result.'--'.$ratio_val.'--strong='.(isset($sub_ratio_data->strong) ? $sub_ratio_data->strong : '--').'||steady ='.(isset($sub_ratio_data->steady) ? $sub_ratio_data->steady : '--');
}

function final_give_bs_category_weight(  $ratio_val = 0 ){
    if( $ratio_val >= 81 ){
        $result = "STRONG";
    }else if( $ratio_val >= 61 && $ratio_val <= 80 ){
        $result = "STEADY";
    }else{
        $result = "SHAKY";
    }
      //0-50 shaky
    //51-75 steady
    //76+ strong
    return  $result;
}

function profit_loss_output( $previous = 0, $current = 0 ){
    $change = 0;
  
    $current = (float)str_replace( ',', '', $current );
    $previous = (float)str_replace( ',', '', $previous );
    $value = round($current - $previous, 2);
    if ($previous != 0) {
        $change = ($value / abs($previous)) * 100;
        $change = round($change, 0);
    } 
    return [ 'percentage' => $change, 'value' => $value ];
}

function different_ratio_output( $key = '', $data_arr = [] ){
    $sales_data = isset($data_arr[$key]) ? $data_arr[$key] : [];
    $current_data = 0;
    $last_month_data = 0;
    if( is_array($sales_data) && count($sales_data) > 0  ){
        $sales_data = array_values($sales_data);
        $arr_keys = array_keys( $sales_data );
        $lastKey = end($arr_keys);
        $lastSec = $lastKey - 1;
        
        $current_data = isset($sales_data[$lastKey]) ? round((float)str_replace(',','',$sales_data[$lastKey]),0) : 0;
        if( $lastSec ){
            $last_month_data = isset($sales_data[$lastSec]) ? round((float)str_replace(',','',$sales_data[$lastSec]),0) : 0;    
        }else{
            $last_month_data = 0;
        }
        
        $ratio_data = profit_loss_output( $last_month_data, $current_data );
    }
    return [ 'title' => $key, 'current_month' => $current_data, 'last_month' => $last_month_data, 'sales_data' => $lastSec, 'percentage' => isset($ratio_data['percentage']) ? $ratio_data['percentage'] : 0 ];
}

function comman_finacial_column_title( $all_dates = [] ){
    $single_datewise = $year_grouped = $quater_grouped = $month_wise_grouped = $year_wise_grouped = [];
       if (is_array($all_dates) && count($all_dates) > 0) {
        foreach ($all_dates as $date) {
            $timestamp = strtotime($date);
            $year = date('Y', $timestamp);
            $month = date('n', $timestamp);

            // Adjust year for Indian FY (April–March)
            // If Jan, Feb, or Mar => it belongs to the previous FY
            if ($month < 4) {
                $fy_year = $year - 1;
            } else {
                $fy_year = $year;
            }

            // Determine Indian financial quarter
            if ($month >= 4 && $month <= 6) {
                $quarter = 'Q1';
            } elseif ($month >= 7 && $month <= 9) {
                $quarter = 'Q2';
            } elseif ($month >= 10 && $month <= 12) {
                $quarter = 'Q3';
            } else { // Jan–Mar
                $quarter = 'Q4';
            }

            $fy_label = $fy_year . '-' . ($fy_year + 1);

            $single_datewise[date( 'Y-m-d', strtotime($date) )] = date( 'Y-m-d', strtotime($date) );

            // Group by FY YTD, Quarter, etc.
            $year_grouped['YTD-' . $fy_label]['label'] = 'YTD-' . $fy_label;
            $year_grouped['YTD-' . $fy_label]['dates'][] = $date;

            $quarter_key = $fy_label . '-' . $quarter;
            $quater_grouped[$quarter_key]['label'] = $quarter . ' (' . $fy_label . ')';
            $quater_grouped[$quarter_key]['dates'][] = $date;

            $month_wise_grouped[$date] = $quarter . ' (' . $fy_label . ')';
            $year_wise_grouped[$date] = $fy_label;
        }
    } 
    return ['year_grouped' => $year_grouped, 'quater_grouped' => $quater_grouped, 'month_wise_grouped' => $month_wise_grouped, 'year_wise_grouped' => $year_wise_grouped, 'single_datewise' => $single_datewise ];
}

function CommanUploadMedia($file, $userId, $folder = 'uploads', $nameType = 'unique'){

        if (!$file) {
            return null;
        }

        // Real physical path inside /public/
        $publicPath = public_path($folder);

        // Create folder if not exists
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        // Get extension
        $extension = $file->getClientOriginalExtension();

        // Generate dynamic name
        switch ($nameType) {
            case 'original':
                $name = $file->getClientOriginalName();
                break;

            case 'uuid':
                $name = uniqid() . '.' . $extension;
                break;

            case 'random':
                $name = str()->random(20) . '.' . $extension;
                break;

            case 'unique':
            default:
                $name = 'media_' . time() . '_' . rand(1000,9999) . '.' . $extension;
                break;
        }

         // Move file to public folder
        $file->move($publicPath, $name);

        // Full public URL
        $url = url('public/'.$folder . '/' . $name);

        // Save into DB
        $media = Attachments::create([
            'media_name' => $name,
            'media_path' => $folder . '/' . $name,
            'media_url'  => $url,
            'user_id'    => $userId,
        ]);
        return $media;
}


function CommanRemoveMedia( $mediaId = 0, $userId = 0 ) {
    // Find media by id AND user_id
    $media = Attachments::where('id', $mediaId)
                  ->where('user_id', $userId)
                  ->first();
    if (!$media) {
        return false;
    }


    $filePath = public_path($media->media_path);
    
    // Delete file if exists
    if (file_exists($filePath)) {
        unlink($filePath);
        // Delete DB record
        $media->delete();
        return true;
    }else{
        return false;
    }
}
function applyDecimalStyle($sheet, $cell, $style) {
    $sheet->getStyle($cell)->applyFromArray($style);
    $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0.00');
}

function format_data_by_monthwise( $sheetData = [] ){
    $newSheedata = [];
    if( is_array($sheetData) && count($sheetData) > 0 ){
        $column_arr = isset($sheetData[3]) ? $sheetData[3] : [];
        $last_index = 'A';
        if( is_array($column_arr) && count($column_arr) > 0 ){
            foreach ($column_arr as $p_header_index => $d_value) {
                if ($d_value == null) {
                    break;
                }
                $last_index = $p_header_index;
           }
        }
        foreach ( $sheetData as $s_key => $s_value ) {
            $new_s_value = [];
            if( is_array($s_value) && count($s_value) > 0 ){
                foreach ($s_value as $d_index => $c_value) {                    
                    $new_s_value[$d_index] = $c_value;
                    if ($last_index == $d_index) {
                        break;
                    }
                }
            }
            $newSheedata[$s_key] = $new_s_value;
        }
    }

    return $newSheedata;
}

function label_wise_format_data_by_monthwise( $sheetData = [], $report_name = '' ){
    $newSheedata = [];
    if( is_array($sheetData) && count($sheetData) > 0 ){
        $column_arr = isset($sheetData[3]) ? $sheetData[3] : [];
        $i = 0;
        $checkCondition = 2;
        if( $report_name == 'cashflow' ){
            $checkCondition =  1;
            $column_arr = isset($sheetData[2]) ? $sheetData[2] : [];
        }

        foreach ( $sheetData as $s_key => $s_value ) {
            $new_s_value = [];
            if( $i > $checkCondition ){
               if( is_array($s_value) && count($s_value) > 0 ){
                    foreach ($s_value as $d_index => $c_value) {       
                        if( $d_index != 'A' ){
                           $new_s_value[] = array(
                                'date' => $column_arr[$d_index],
                                'value' => $c_value,
                            );  
                        }                
                    }
                }
                if( $s_value['A'] != '' ){
                    $newSheedata[] = [ 'label' => $s_value['A'], 'monthwise_data' => $new_s_value ];
                }
            }
            $i++;            
        }

    }    
    return $newSheedata;
}

function remove_comma_format_data_by_monthwise( $sheetData = [] ){
    $newSheedata = [];
    if( is_array($sheetData) && count($sheetData) > 0 ){
        $column_arr = isset($sheetData[3]) ? $sheetData[3] : [];
        $last_index = 'A';
        if( is_array($column_arr) && count($column_arr) > 0 ){
            foreach ($column_arr as $p_header_index => $d_value) {
                if ($d_value == null) {
                    break;
                }
                $last_index = $p_header_index;
           }
        }
        foreach ( $sheetData as $s_key => $s_value ) {
            $new_s_value = [];
            if( is_array($s_value) && count($s_value) > 0 ){
                foreach ($s_value as $d_index => $c_value) {                    
                    $new_s_value[$d_index] = str_replace(',', '', $c_value);
                    if ($last_index == $d_index) {
                        break;
                    }
                }
            }
            $newSheedata[$s_key] = $new_s_value;
        }
    }
    return $newSheedata;
}



