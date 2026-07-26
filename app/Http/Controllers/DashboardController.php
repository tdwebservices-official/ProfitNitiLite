<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;
use App\Services\DeepSeekService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfService;
use DB;
use Validator;
use File;


class DashboardController extends Controller{

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
  public $cashflow_quality_report_list;
  protected $deepseek;

  function __construct( DeepSeekService $deepseek, PdfService $pdf ) {

    $this->financial_summary_list = config('chart-of-accounts.financial_summary_list');
    $this->power_profit_report_list = config('chart-of-accounts.power_profit_report_list');
    $this->balancesheet_report_list = config('chart-of-accounts.balancesheet_report_list');
    $this->cash_management_report_list = config('chart-of-accounts.cash_management_report_list');
    $this->capex_report_list = config('chart-of-accounts.capex_report_list');
    $this->profitloss_report_list = config('chart-of-accounts.profitloss_report_list');
    $this->cashflow_quality_report_list = config('chart-of-accounts.cashflow_quality_report_list');
    $this->balance_sheet_list = config('chart-of-accounts.default');
    $this->balance_sheet_list_keys = config('chart-of-accounts.default_keys');
    $this->new_financial_summary_report_list = config('chart-of-accounts.new_financial_summary_report_list');
    $this->financing_report_list = config('chart-of-accounts.financing_report_list');

    $this->deepseek = $deepseek;
    $this->pdf = $pdf;
  }

  /* Display Dashboard Page */
  public function index(){        
    if(Auth::check()) {
      $user = auth()->user();
      if ($user->hasRole('Super Admin')) {
        $all_users = User::get();
      } else {
        $all_users = User::where('id', $user->id)->get();
      }
      
      $lastDate = DB::table('kpi_records')->where('user_id', $user->id)->max('tbdate');
      if( !is_null($lastDate) ){
          $lastDate = date('Y-m-d', strtotime($lastDate));
          $lastMonth = date('m', strtotime($lastDate));
          $lastYear = date('Y', strtotime($lastDate));
      }else{
          $lastDate = date('Y-m-d');
          $lastMonth = date('m');
          $lastYear = date('Y');
      }
      
      //$this->dashboard_report()
      return view( 'dashboard/index', ['all_users' => $all_users, 'lastYear' => $lastYear, 'lastMonth' => $lastMonth ] );
    } else {
      return view('auth.login');
    }
  }

  /* Display Dashboard Page */
  public function help(){        
    return view('dashboard/help');
  }

  public function generateDashboardDataWithDeepSeek( Request $request  ){

    $promptObj = $request->get('promptObj'); 
    $type = $request->get('type'); 
    $month = $request->get('month'); 
    $year = $request->get('year'); 
    
   // $promptObj_text = pfnitiJsonToText($promptObj);
    
    $run_date = $year.'-'.$month.'-'.'01';

    $user = auth()->user();
    $userId = $user->id;

    $content_prompt = '';

    $prompt = file_get_contents(
        resource_path('prompts/dashboard_prompt-16.txt')
    );


    $promtObjtext = "";

    $promtObjtext .= "Company Name : ".$promptObj['company_name'].PHP_EOL;
    $promtObjtext .= "Industry : ".$promptObj['industry'].PHP_EOL;
    $promtObjtext .= "Period Type : ".$promptObj['period_type'].PHP_EOL;
    $promtObjtext .= "Current Period : ".$promptObj['period_label'].PHP_EOL;
    $promtObjtext .= "Previous Period : ".$promptObj['previous_period_label'].PHP_EOL;
    $promtObjtext .= "Currency Unit : ".$promptObj['currency_unit'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Revenue Growth % : ".$promptObj['growth_metrics']['revenue_growth_pct'].PHP_EOL;
    $promtObjtext .= "COGS Growth % : ".$promptObj['growth_metrics']['cogs_growth_pct'].PHP_EOL;
    $promtObjtext .= "Overheads Growth % : ".$promptObj['growth_metrics']['overheads_growth_pct'].PHP_EOL;
    $promtObjtext .= "Gross Margin Growth % : ".$promptObj['growth_metrics']['gross_margin_growth_pct'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Revenue Current Month : ".$promptObj['profitability']['revenue']." | Revenue Last Month : ".$promptObj['profitability']['revenue_prev'].PHP_EOL;
    $promtObjtext .= "COGS Current Month : ".$promptObj['profitability']['cogs']." | COGS Last Month : ".$promptObj['profitability']['cogs_prev'].PHP_EOL;
    $promtObjtext .= "Gross Margin Value : ".$promptObj['profitability']['gross_margin_value'].PHP_EOL;
    $promtObjtext .= "Gross Margin % Current Month : ".$promptObj['profitability']['gross_margin_pct']." | Gross Margin % Last Month : ".$promptObj['profitability']['gross_margin_pct_prev'].PHP_EOL;
    $promtObjtext .= "Overheads Current Month : ".$promptObj['profitability']['overheads']." | Overheads Last Month : ".$promptObj['profitability']['overheads_prev'].PHP_EOL;
    $promtObjtext .= "Operating Profit Value : ".$promptObj['profitability']['operating_profit_value'].PHP_EOL;
    $promtObjtext .= "Operating Profit % Current Month : ".$promptObj['profitability']['operating_profit_pct']." | Operating Profit % Current Last Month : ".$promptObj['profitability']['operating_profit_pct_prev'].PHP_EOL;
    $promtObjtext .= "Net Profit Value : ".$promptObj['profitability']['net_profit_value'].PHP_EOL;
    $promtObjtext .= "Net Profit % Current Month : ".$promptObj['profitability']['net_profit_pct']." | Net Profit % Current Last Month : ".$promptObj['profitability']['net_profit_pct_prev'].PHP_EOL;
    $promtObjtext .= "Break Even Sales Current Month : ".$promptObj['profitability']['breakeven_sales']." | Break Even Sales Last Month : ".$promptObj['profitability']['breakeven_sales_prev'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Operating Cash Profit Current Month : ".$promptObj['cash_metrics']['operating_cash_profit']." | Operating Cash Profit Last Month : ".$promptObj['cash_metrics']['operating_cash_profit_prev'].PHP_EOL;
    $promtObjtext .= "Operating Cash Flow Current Month : ".$promptObj['cash_metrics']['operating_cash_flow']." | Operating Cash Flow Last Month : ".$promptObj['cash_metrics']['operating_cash_flow_prev'].PHP_EOL;
    $promtObjtext .= "Cash From Customers : ".$promptObj['cash_metrics']['cash_from_customers'].PHP_EOL;
    $promtObjtext .= "Cash To Suppliers : ".$promptObj['cash_metrics']['cash_to_suppliers'].PHP_EOL;
    $promtObjtext .= "Working Capital Change : ".$promptObj['cash_metrics']['working_capital_change'].PHP_EOL;
    $promtObjtext .= "Other Capital : ".$promptObj['cash_metrics']['other_capital'].PHP_EOL;
    $promtObjtext .= "Surplus / Deficit : ".$promptObj['cash_metrics']['surplus_or_deficit'].PHP_EOL;
    $promtObjtext .= "Capital Withdrawn : ".$promptObj['cash_metrics']['capital_withdrawn'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Accounts Receivable : ".$promptObj['working_capital']['ar_value'].PHP_EOL;
    $promtObjtext .= "AR Days Current Month : ".$promptObj['working_capital']['ar_days']." | AR Days Last Month : ".$promptObj['working_capital']['ar_days_prev'].PHP_EOL;
    $promtObjtext .= "Accounts Payable : ".$promptObj['working_capital']['ap_value'].PHP_EOL;
    $promtObjtext .= "AP Days Current Month : ".$promptObj['working_capital']['ap_days']." | AP Days Last Month : ".$promptObj['working_capital']['ap_days_prev'].PHP_EOL;
    $promtObjtext .= "Inventory Value : ".$promptObj['working_capital']['inventory_value'].PHP_EOL;
    $promtObjtext .= "Inventory Days Current Month : ".$promptObj['working_capital']['inventory_days']." | Inventory Days Last Month : ".$promptObj['working_capital']['inventory_days_prev'].PHP_EOL;
    $promtObjtext .= "Working Capital : ".$promptObj['working_capital']['nwc_value'].PHP_EOL;
    $promtObjtext .= "Working Capital Days Current Month : ".$promptObj['working_capital']['wc_days']." | Working Capital Days Last Month : ".$promptObj['working_capital']['wc_days_prev'].PHP_EOL;
    $promtObjtext .= "Working Capital Per ₹10 Sales Current Month : ".$promptObj['working_capital']['wc_per_100_sales']." | Working Capital Per ₹10 Sales Last Month : ".$promptObj['working_capital']['wc_per_100_sales_prev'].PHP_EOL;
    $promtObjtext .= "Working Capital Turnover Current Month : ".$promptObj['working_capital']['wc_turnover']." | Working Capital Turnover Last Month : ".$promptObj['working_capital']['wc_turnover_prev'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Debt To Equity Current Month : ".$promptObj['capital_structure']['debt_to_equity']." | Debt To Equity Last Month : ".$promptObj['capital_structure']['debt_to_equity_prev'].PHP_EOL;
    $promtObjtext .= "Debt To Capital % Current Month : ".$promptObj['capital_structure']['debt_to_capital_pct']." | Debt To Capital % Last Month : ".$promptObj['capital_structure']['debt_to_capital_pct_prev'].PHP_EOL;
    $promtObjtext .= "Interest Cover Current Month : ".$promptObj['capital_structure']['interest_cover']." | Interest Cover Last Month : ".$promptObj['capital_structure']['interest_cover_prev'].PHP_EOL;
    $promtObjtext .= "Other Capital % Current Month : ".$promptObj['capital_structure']['other_capital_pct']." | Other Capital % Last Month : ".$promptObj['capital_structure']['other_capital_pct_prev'].PHP_EOL;
    $promtObjtext .= "Other Capital Turnover Current Month : ".$promptObj['capital_structure']['other_capital_turnover']." | Other Capital Turnover Last Month : ".$promptObj['capital_structure']['other_capital_turnover_prev'].PHP_EOL;
    $promtObjtext .= "Net Operating Assets % Current Month : ".$promptObj['capital_structure']['net_operating_assets_pct']." | Net Operating Assets % Last Month : ".$promptObj['capital_structure']['net_operating_assets_pct_prev'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Return On Capital % Current Month : ".$promptObj['returns']['return_on_capital_pct']." | Return On Capital % Last Month : ".$promptObj['returns']['return_on_capital_pct_prev'].PHP_EOL;
    $promtObjtext .= "Return On Equity % Current Month : ".$promptObj['returns']['return_on_equity_pct']." | Return On Equity % Last Month : ".$promptObj['returns']['return_on_equity_pct_prev'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Current Ratio Current Month : ".$promptObj['liquidity']['current_ratio']." | Current Ratio Last Month : ".$promptObj['liquidity']['current_ratio_prev'].PHP_EOL;
    $promtObjtext .= "Quick Ratio Current Month : ".$promptObj['liquidity']['quick_ratio']." | Quick Ratio Last Month : ".$promptObj['liquidity']['quick_ratio_prev'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Business Health Score Current Month : ".$promptObj['business_health_score']." | Business Health Score Last Month : ".$promptObj['previous_health_score'].PHP_EOL.PHP_EOL;

    $promtObjtext .= "Segment Name : ".$promptObj['segments'][0]['name'].PHP_EOL;
    $promtObjtext .= "Segment Revenue Current Month : ".$promptObj['segments'][0]['revenue']." | Segment Revenue Last Month : ".$promptObj['segments'][0]['revenue_prev'].PHP_EOL;
    $promtObjtext .= "Segment Margin % : ".$promptObj['segments'][0]['margin_pct'].PHP_EOL;

    $content_prompt = str_replace( '[promptObj]', $promtObjtext, $prompt );


    //$content_prompt = str_replace( '[promptObj]', $promptObj_text, $prompt );
   // $dashboardpromptObj = getOptionDta(  $userId, 'dashboardpromptObj' );
    
    $dashboardpromptObj = getAIHistoryData( $userId, 'dashboardpromptObj', $run_date );
    
    //$dashboardPromptHTML = getOptionDta(  $userId, 'dashboardPromptHTML' );
    $dashboardPromptHTML = getAIHistoryData( $userId, 'dashboardPromptHTML', $run_date );
    // if( $type == 'ProfitCashflow' ){
    //   $content_prompt = 'You are a financial analyst. Below is an HTML dashboard showing Profit vs Cash Flow.

    //   '.$prompt_html.'
    //   ---

    //   Based only on the numbers in this dashboard, write ONE short paragraph (like the example below) that explains:

    //   - The relationship between operating cash profit (from profit walk), operating cash flow (from cash walk), and reported profit (from reconciliation).
    //   - The impact of working capital and any capital surplus/withdrawal.
    //   - The final net cash result and why profit is not converting into cash.

    //   Example style (use this as a template, but replace with YOUR actual figures):
    //   "Operating cash flow (₹2.69 Cr) is HIGHER than operating cash profit (₹24.6 L) — driven by strong collections this month. However, working capital absorbed ₹2.44 Cr, and a capital surplus of ₹1.36 Cr from other capital sources partly offset it. Net result: business generated approximately (₹77.5 L) of negative cash this month — the profit shown is NOT fully converting into cash."

    //   Use your numbers: operating cash profit (from "Operating cash profit" row in profit walk), operating cash flow (from "Operating cash flow" row in cash walk), reported profit (from "Profit" row in reconciliation), working capital change (from "Working capital" row in reconciliation), and other capital or capital withdrawal as applicable.

    //   Output only the paragraph – no extra text.';
    // }else if( $type == 'WorkingCapital' ){
    //   $content_prompt = 'You are a financial analyst. Below is an HTML dashboard with Profit vs Cash Flow data.

    //   '.$prompt_html.'

    //   Based ONLY on the numbers in this dashboard, calculate the following working capital metrics:

    //   - **A/R days** (Days Sales Outstanding) = (Average receivables / Revenue) × 365  
    //     *If receivables are not directly shown, estimate using: (Revenue – Cash from customer) as the change in receivables, then derive average.*
    //   - **A/P days** (Days Payables Outstanding) = (Average payables / COGS) × 365  
    //     *Estimate payables from: (COGS + change in inventory?) – but simplest: use (Purchases – Cash to supplier). Use given COGS and cash to supplier.*
    //   - **Inventory days** = (Inventory / COGS) × 365  
    //     *If inventory not shown, infer from the gap between COGS and cash to supplier (i.e., purchases vs COGS).*

    //   Then write **exactly one sentence** (max 25 words) in this format:

    //   "A/P days (X) are much higher than A/R days (Y) — suppliers are funding the business. Inventory of Z days is on the heavier side."

    //   Replace X, Y, Z with your calculated numbers (rounded to whole days). Do not add any extra text or explanation.';
    // }else if( $type == 'FinancialRatio' ){
    //   $content_prompt = 'You are a financial analyst. Below is an HTML dashboard showing key financial ratios for a business.

    //   '.$prompt_html.'

    //   Based ONLY on the numbers in this HTML, write ONE short paragraph (2–3 sentences) that:

    //   - Comments on profitability metrics: Return on Equity (ROE) and Interest Coverage. Use the exact values shown (e.g., "ROE X%, Interest coverage Yx").
    //   - Highlights the most concerning ratio(s) among liquidity (current ratio, quick ratio) and leverage (debt/equity).
    //   - Gives a specific, actionable recommendation (e.g., deleverage, raise equity, improve quick ratio).

    //   Use this example style (but replace with YOUR actual numbers from the HTML):
    //   "Profitability metrics (ROE 108.8%, Interest coverage 12.7x) are exceptional, but liquidity and leverage need attention. Debt-to-equity at 1.93x is the most pressing concern — consider deleveraging or raising equity. Quick ratio below 1.0x means without selling inventory, the business cannot cover short-term obligations."

    //   Output only the paragraph – no extra text.';
    // }

    $messages = [
      ['role' => 'system', 'content' => 'You are "Pulse AI" — a financial co-pilot'],
      [
        'role' => 'user',
        'content' =>  $content_prompt
      ],
    ];
    try {
        
      if( $userId == 1 ){
            $resp = $this->deepseek->chat($messages, 'deepseek-v4-flash');
            $content = data_get($resp, 'choices.0.message.content', null);  
            //setOptionDta($userId, 'dashboardpromptObj', $promptObj);
            //setOptionDta($userId, 'dashboardPromptHTML', $content);
            setAIHistoryData($userId, 'dashboardpromptObj', $promptObj, $run_date);
            setAIHistoryData($userId, 'dashboardPromptHTML', $content, $run_date );
      }else{
           if( !empty($dashboardpromptObj) && serialize($dashboardpromptObj) == serialize($promptObj) ){
            $content = $dashboardPromptHTML;
          }else{
            $resp = $this->deepseek->chat($messages, 'deepseek-v4-flash');
            $content = data_get($resp, 'choices.0.message.content', null);  
            //setOptionDta($userId, 'dashboardpromptObj', $promptObj);
            //setOptionDta($userId, 'dashboardPromptHTML', $content);
            setAIHistoryData($userId, 'dashboardpromptObj', $promptObj, $run_date);
            setAIHistoryData($userId, 'dashboardPromptHTML', $content, $run_date );
          }
      }

     
      return response()->json([
        'status' => 'success',
        'content' => $content,
        'promptObj' => $promptObj
      ]);
    } catch (\Throwable $e) {

      return response()->json([
        'status' => 'error',
        'message' => $e->getMessage()
      ], 500);
    }
  }


  
  public function getDashboardReports( Request $request ){
    try {
       

      $list_data = $this->dashboard_report( $request );

      $bs_category = different_ratio_output( 'BS Category', $list_data );

      $bs_category['current_month'] = $bs_category['current_month'] .' - '. final_give_bs_category_weight( $bs_category['current_month'] );
      $bs_category['last_month'] = $bs_category['last_month'] .' - '. final_give_bs_category_weight( $bs_category['last_month'] );  
       
      $report_data = [
        
        'Sales' => [ 'data' => different_ratio_output( 'Sales', $list_data ), 'noInt' => 0 ],
        'Overheads' => [ 'data' => different_ratio_output( 'Overheads', $list_data ), 'noInt' => 0 ],
        'COGS' => [ 'data' => different_ratio_output( 'COGS', $list_data ), 'noInt' => 0 ],
        'Gross Margin' => [ 'data' => different_ratio_output( 'Gross Profit', $list_data ), 'noInt' => 0 ],
        'Gross Mrg Perc' => [ 'data' => different_ratio_output( 'Gross Mrg Perc', $list_data,2 ), 'noInt' => 0 ],
        'Revenue Growth %' => [ 'data' => different_ratio_output( 'Revenue Growth %', $list_data,2 ), 'noInt' => 0 ],
        'COGS Growth %' => [ 'data' => different_ratio_output( 'COGS Growth %', $list_data,2 ), 'noInt' => 0 ],
        'Overheads Growth %' => [ 'data' => different_ratio_output( 'Overheads Growth %', $list_data,2 ), 'noInt' => 0 ],
        'Net Mrg Perc' => [ 'data' => different_ratio_output( 'Net Mrg Perc', $list_data,2 ), 'noInt' => 0 ],
        'CurrentRatio' => [ 'data' => different_ratio_output( "CurrentRatio", $list_data, 2 ), 'noInt' => 0 ],      
        'QuickRatio' => [ 'data' => different_ratio_output( "QuickRatio", $list_data, 2 ), 'noInt' => 0 ],      
        'DebtToEquity' => [ 'data' => different_ratio_output( "DebtToEquity", $list_data, 2 ), 'noInt' => 0 ],      
        'Interest Cover' => [ 'data' => different_ratio_output( "Interest Cover", $list_data, 2 ), 'noInt' => 0 ],      
        'Return on equity' => [ 'data' => different_ratio_output( "Return on equity", $list_data, 2 ), 'noInt' =>0 ],
        'Operating Cash Flow' => [ 'data' => different_ratio_output( "Operating Cash Flow", $list_data ), 'noInt' =>0 ],
        'Operating Profit' => [ 'data' => different_ratio_output( 'Operating Profit', $list_data ), 'noInt' => 0 ],
        'Operating Profit %' => [ 'data' => different_ratio_output( 'Operating Profit %', $list_data, 2 ), 'noInt' => 0 ],
        'Debt to Capital' => [ 'data' => different_ratio_output( 'Debt to Capital', $list_data, 2 ), 'noInt' => 0 ],
        'Operating Cash Profit' => [ 'data' => different_ratio_output( 'Operating Cash Profit', $list_data, 2 ), 'noInt' => 0 ],
        'Working Capital Turnover' => [ 'data' => different_ratio_output( 'Working Capital Turnover', $list_data, 2 ), 'noInt' => 0 ],
        'Return on Capital %' => [ 'data' => different_ratio_output( 'Return on Capital %', $list_data, 2 ), 'noInt' => 0 ],
        'Other Capital Turnover' => [ 'data' => different_ratio_output( 'Other Capital Turnover', $list_data, 2 ), 'noInt' => 0 ],
        'Other Capital %' => [ 'data' => different_ratio_output( 'Other Capital %', $list_data, 2 ), 'noInt' => 0 ],
        'Return on Capital %' => [ 'data' => different_ratio_output( 'Return on Capital %', $list_data, 2 ), 'noInt' => 0 ],
        'Retained Profit' => [ 'data' => different_ratio_output( 'Retained Profit', $list_data ), 'noInt' => 0 ],
        'Net Operating Assets %' => [ 'data' => different_ratio_output( 'Net Operating Assets %', $list_data ), 'noInt' => 0 ],
        'Working Capital per ₹100' => [ 'data' => different_ratio_output( 'Working Capital per ₹100', $list_data ), 'noInt' => 0 ],
        'Break Even Sales' => [ 'data' => different_ratio_output( 'Break Even Sales', $list_data ), 'noInt' => 0 ],
        'Fixed Assets' => [ 'data' => different_ratio_output( 'Fixed Assets', $list_data ), 'noInt' => 0 ],
        'Other Assets' => [ 'data' => different_ratio_output( 'Other Assets List', $list_data ), 'noInt' => 0 ],
        'Other Liabilities' => [ 'data' => different_ratio_output( 'Other Liabilities List', $list_data ), 'noInt' => 0 ],
        'Other Capital' => [ 'data' => different_ratio_output( 'Other Capital', $list_data ), 'noInt' => 0 ],
        'Cash & Bank' => [ 'data' => different_ratio_output( 'Cash & Bank', $list_data ), 'noInt' => 0 ],
        'Total Debt' => [ 'data' => different_ratio_output( 'Total Debt', $list_data ), 'noInt' => 0 ],
        'Equity' => [ 'data' => different_ratio_output( 'Equity', $list_data ), 'noInt' => 0 ],
        'Total Funding' => [ 'data' => different_ratio_output( 'Total Funding', $list_data ), 'noInt' => 0 ],
        'Accounts Receivable' => [ 'data' => different_ratio_output( 'Accounts Receivable', $list_data ), 'noInt' => 0 ],
        'A/R Days' => [ 'data' => different_ratio_output( 'A/R Days', $list_data ), 'noInt' => 0 ],
        'Accounts Payable' => [ 'data' => different_ratio_output( 'Accounts Payable', $list_data ), 'noInt' => 0 ],
        'A/P Days' => [ 'data' => different_ratio_output( 'A/P Days', $list_data ), 'noInt' => 0 ],
        'Working Capital' => [ 'data' => different_ratio_output( 'Working Capital', $list_data ), 'noInt' => 0 ],
        'Capital Withdrawn' => [ 'data' => different_ratio_output( 'Capital Withdrawn', $list_data ), 'noInt' => 0 ],
        'Dividend Paid' => [ 'data' => different_ratio_output( 'Dividend Paid', $list_data ), 'noInt' => 0 ],
        'W/C Days' => [ 'data' => different_ratio_output( 'W/C Days', $list_data ), 'noInt' => 0 ],
        'Closing Stock' => [ 'data' => different_ratio_output( 'Closing Stock', $list_data ), 'noInt' => 0 ],
        'Inventory Days' => [ 'data' => different_ratio_output( 'Inventory Days', $list_data ), 'noInt' => 0 ],
        'BS Category' => [ 'data' => $bs_category, 'noInt' => 1 ],
        'IMPACT OF MANAGEMENT DECISIONS' => [ 'data' => different_ratio_output( 'IMPACT OF MANAGEMENT DECISIONS', $list_data ), 'noInt' => 0 ],
      ];

      $user = auth()->user();
      $company_name = $user->name;
      if( isset($user->company_name) && !empty(trim($user->company_name)) ){
          $company_name = $user->company_name;
      }
      
      $promptData = [
        "company_name" => $user->name,
        "industry" => $list_data['industryData']->name,
        "period_type" => "month",
        "period_label" => "July 2025",
        "previous_period_label" => "July 2025",
        "currency_unit" => "Lakhs | Crores | Thousands",
        "growth_metrics" => [
          "revenue_growth_pct" => $report_data["Revenue Growth %"]["data"]["current_month"] ?? 0,           
          "cogs_growth_pct" => $report_data["COGS Growth %"]["data"]["current_month"] ?? 0,
          "overheads_growth_pct" => $report_data["Overheads Growth %"]["data"]["current_month"] ?? 0,
          "gross_margin_growth_pct" => $report_data["Gross Mrg Perc"]["data"]["current_month"] ?? 0
        ],
        "profitability" => [
          "revenue"                   => $report_data["Sales"]["data"]["current_month"] ?? 0,
          "revenue_prev"              => $report_data["Sales"]["data"]["last_month"] ?? 0,
          "cogs"                      => $report_data["COGS"]["data"]["current_month"] ?? 0,
          "cogs_prev"                 => $report_data["COGS"]["data"]["last_month"] ?? 0,
          "gross_margin_value"        => $report_data["Gross Margin"]["data"]["current_month"] ?? 0,
          "gross_margin_pct"          => $report_data["Gross Mrg Perc"]["data"]["current_month"] ?? 0,
          "gross_margin_pct_prev"     => $report_data["Gross Mrg Perc"]["data"]["last_month"] ?? 0,
          "overheads"                 => $report_data["Overheads"]["data"]["current_month"] ?? 0,
          "overheads_prev"            => $report_data["Overheads"]["data"]["last_month"] ?? 0,
          "operating_profit_value"    => $report_data["Operating Profit"]["data"]["current_month"] ?? 0,
          "operating_profit_pct"      => $report_data["Operating Profit %"]["data"]["current_month"] ?? 0,
          "operating_profit_pct_prev" => $report_data["Operating Profit %"]["data"]["last_month"] ?? 0,
          "net_profit_value"          => $report_data["Retained Profit"]["data"]["current_month"] ?? 0,
          "net_profit_pct"            => $report_data["Net Mrg Perc"]["data"]["current_month"] ?? 0,
          "net_profit_pct_prev"       => $report_data["Net Mrg Perc"]["data"]["last_month"] ?? 0,
          "breakeven_sales"           => $report_data["Break Even Sales"]["data"]["current_month"] ?? 0,
          "breakeven_sales_prev"      => $report_data["Break Even Sales"]["data"]["last_month"] ?? 0,
        ],
        "cash_metrics" => [
          "operating_cash_profit"     => $report_data["Operating Cash Profit"]["data"]["current_month"] ?? 0,
          "operating_cash_profit_prev"=> $report_data["Operating Cash Profit"]["data"]["last_month"] ?? 0,
          "operating_cash_flow"       => $report_data["Operating Cash Flow"]["data"]["current_month"] ?? 0,
          "operating_cash_flow_prev"  => $report_data["Operating Cash Flow"]["data"]["last_month"] ?? 0,
          "cash_from_customers"       => 0,
          "cash_to_suppliers"         => 0,
          "working_capital_change"    => 0,
          "other_capital"             => 0,
          "surplus_or_deficit"        => 0,
          "capital_withdrawn"         => $report_data["Dividend Paid"]["data"]["current_month"] ?? 0,
        ],
        "working_capital" => [
          "ar_value"                  => $report_data["Accounts Receivable"]["data"]["current_month"] ?? 0,
          "ar_days"                   => $report_data["A/R Days"]["data"]["current_month"] ?? 0,
          "ar_days_prev"              => $report_data["A/R Days"]["data"]["last_month"] ?? 0,
          "ap_value"                  => $report_data["Accounts Payable"]["data"]["current_month"] ?? 0,
          "ap_days"                   => $report_data["A/P Days"]["data"]["current_month"] ?? 0,
          "ap_days_prev"              => $report_data["A/P Days"]["data"]["last_month"] ?? 0,
          "inventory_value"           => $report_data["Closing Stock"]["data"]["current_month"] ?? 0,
          "inventory_days"            => $report_data["Inventory Days"]["data"]["current_month"] ?? 0,
          "inventory_days_prev"       => $report_data["Inventory Days"]["data"]["last_month"] ?? 0,
          "nwc_value"                 => $report_data["Working Capital"]["data"]["current_month"] ?? 0,
          "wc_days"                   => $report_data["W/C Days"]["data"]["current_month"] ?? 0,
          "wc_days_prev"              => $report_data["W/C Days"]["data"]["last_month"] ?? 0,
          "wc_per_100_sales" => $report_data["Working Capital per ₹10"]["data"]["current_month"] ?? 0, 
          "wc_per_100_sales_prev" => $report_data["Working Capital per ₹10"]["data"]["last_month"] ?? 0,
          "wc_turnover" => $report_data["Working Capital Turnover"]["data"]["current_month"] ?? 0, 
          "wc_turnover_prev" =>$report_data["Working Capital Turnover"]["data"]["current_month"] ?? 0
        ],
        "capital_structure" => [
          "debt_to_equity"            => $report_data["DebtToEquity"]["data"]["current_month"] ?? 0,
          "debt_to_equity_prev"       => $report_data["DebtToEquity"]["data"]["last_month"] ?? 0,
          "debt_to_capital_pct" => $report_data["Debt to Capital"]["data"]["current_month"] ?? 0, 
          "debt_to_capital_pct_prev" => $report_data["Debt to Capital"]["data"]["last_month"] ?? 0,
          "interest_cover"            => $report_data["Interest Cover"]["data"]["current_month"] ?? 0,
          "interest_cover_prev"       => $report_data["Interest Cover"]["data"]["last_month"] ?? 0,
          "other_capital_pct" => $report_data["Other Capital %"]["data"]["current_month"] ?? 0, 
          "other_capital_pct_prev" => $report_data["Other Capital %"]["data"]["last_month"] ?? 0,
          "other_capital_turnover" => $report_data["Other Capital Turnover"]["data"]["current_month"] ?? 0, 
          "other_capital_turnover_prev" => $report_data["Other Capital Turnover"]["data"]["last_month"] ?? 0,
          "net_operating_assets_pct" => $report_data["Net Operating Assets %"]["data"]["current_month"] ?? 0, 
          "net_operating_assets_pct_prev" => $report_data["Net Operating Assets %"]["data"]["last_month"] ?? 0
        ],
        "returns" => [
          "return_on_capital_pct" => $report_data["Return on Capital %"]["data"]["last_month"] ?? 0, 
          "return_on_capital_pct_prev" => $report_data["Return on Capital %"]["data"]["last_month"] ?? 0,
          "return_on_equity_pct"      => $report_data["Return on equity"]["data"]["current_month"] ?? 0,
          "return_on_equity_pct_prev" => $report_data["Return on equity"]["data"]["last_month"] ?? 0,
        ],
        "liquidity" => [
          "current_ratio"             => round($report_data["CurrentRatio"]["data"]["current_month"] ?? 0, 2),
          "current_ratio_prev"        => round($report_data["CurrentRatio"]["data"]["last_month"] ?? 0, 2),
          "quick_ratio"               => round($report_data["QuickRatio"]["data"]["current_month"] ?? 0, 2),
          "quick_ratio_prev"          => round($report_data["QuickRatio"]["data"]["last_month"] ?? 0, 2),
        ],
        "business_health_score" => $report_data["BS Category"]["data"]["current_month"] ?? 0, "previous_health_score" => $report_data["BS Category"]["data"]["last_month"] ?? 0,
        "segments" => [
          [ "name" => "ProfitNiti", "revenue" => $report_data["Sales"]["data"]["current_month"] ?? 0, "revenue_prev" => $report_data["Sales"]["data"]["last_month"] ?? 0, "margin_pct" =>  $report_data["Gross Mrg Perc"]["data"]["current_month"] ?? 0 ] 
        ]
      ];

       return response()->json([
        'status' => 'success',
        'report_data' => $report_data,
        'promptData' => $promptData,
        'message' => 'Dashboard Data Generated Successfully'
       ], 200);
    
    } catch (\Exception $e) {
        return response()->json([
        'status' => 'error',
        'message' => $e->getMessage()
        ], 500);
    }
  }

  public function dashboard_report( $request ){
    
    $spreadsheet = new Spreadsheet();
    $assignUserId = $request->get('assign_by'); 
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

    $month = $request->get('month');            
    $year =  $request->get('year');         

    $end_date = date("Y-m-t", strtotime("$year-$month-01"));

    $start_date = date("Y-m-d", strtotime("-2 month", strtotime("$year-$month-01"))); 
     
    $all_dates = getMonthStartDates( $start_date, $end_date );
 
    $pl_query->where(
    function ($query) use ($start_date, $end_date) {
        $query->whereBetween('kpi_records.tbdate', [$start_date, $end_date]);
    });
    
    $industry_id = get_user_meta( $user->id, 'industry_id', true );

    if ( empty($industry_id) || $industry_id == 0 || $industry_id == null ) {
      $industry_id = 1;
    }
    
    $single_industry_list = DB::table( 'bs_category_ratio' )->where( 'industry_id', $industry_id )->get();
    $industryData = DB::table( 'industry' )->where( 'id', $industry_id )->first();
    $single_industry_arr = [];
    if( $single_industry_list->count() > 0 ){
        foreach ( $single_industry_list as $s_key => $single_industry_item ) {
            $single_industry_arr[$single_industry_item->ratio_name] = $single_industry_item;
        }
    }
    $item_list = ['Current Ratio', 'Quick Ratio', 'Debt-to-Equity', 'Asset Turnover', 'ROE', 'ROA', 'Gross Margin', 'Net Margin', 'Interest Coverage', 'Receivables Days', 'Payable Days', 'Working Capital Days', 'Operating CF Margin', 'CF Coverage', 'CF to Debt', 'Capex Coverage', 'Total Score'];

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

    $pl_balance_items = $this->new_financial_summary_report_list;
    $column_width_list = [
      'A' => 200
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

    if( $tbdate_list ){                                
      $tb_d_cnt = count( $tbdate_list );
      $tb_d_cnt  = $tb_d_cnt  - 1;
      $tbdate_list_arr = array_values( $tbdate_list );
      $tb_header_cell = hg_generate_excel_cell_names( $start_row, $tb_d_cnt, $start_col ); 
      if( $tb_header_cell ){
        $start_col = end($tb_header_cell);                        
        foreach( $tb_header_cell as $tb_header_index => $tb_header_range_index ){
          if( isset( $tbdate_list_arr[$tb_header_index] ) && !empty( $tbdate_list_arr[$tb_header_index] ) ){   
            $td_date = date( 'm/d/Y', strtotime( $tbdate_list_arr[$tb_header_index] ) );
            $tb_header[ $tb_header_range_index ] = [
              'label' => $td_date,
              'style' => hg_comman_customer_header_style( 'bfbfbf' )      
            ];    
            $column_width_list[str_replace( $start_row , '', $tb_header_range_index )] = 125;
            $data_column_range[ $tbdate_list_arr[$tb_header_index] ] = array(
              'column' => str_replace( $start_row , '', $tb_header_range_index ),
            ); 
          }
        }

      }            
    }  

    $end_cell = str_replace( $start_row , '', $start_col );

    $header_arr = array_merge( $header_arr, $tb_header ); 

    $local_path = $path = public_path().'/reports/fns-report/';

    $rp_po_path = 'fns-report';

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
    $spreadsheet->getActiveSheet()->mergeCells( 'A1:'.$end_cell.'1' ); 
    $spreadsheet->getActiveSheet()->getCell( 'A1' )->setValue( 'Financial Summary Report' )->getStyle( 'A1:'.$end_cell.'1' )->applyFromArray(hg_comman_customer_header_style( '#bfbfbf', 14, '000000' ));


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


    $p_index = 4;
    $main_net_profit_year_wise_arr = $main_net_profit_month_wise_arr = $main_net_profit_check = [];
    $i = 0;


    $all_item_rows = [];
    $final_all_total = [];
    if( $pl_balance_items ){
      foreach( $pl_balance_items as $b_key => $bl_key ){  
        $sub_year_wise_sub_types = $sub_date_wise_sub_types = $sub_total_date_wise_arr = [];
        if( isset($bl_key['data']['items']) && is_array($bl_key['data']['items']) && count($bl_key['data']['items']) > 0 ){
          foreach ( $bl_key['data']['items'] as $sub_key => $sub_items ) {    
               
            if( !isset($sub_items['download_tr_hide']) ){                  
              $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $sub_items['label'] )->getStyle( 'A'.$p_index )->applyFromArray($subitemStyle); 
            }
            $all_item_rows[] = 'A'.$p_index;
            $main_total_date_wise_arr = $main_total_quater_wise_arr = $main_total_year_wise_arr = [];              
            $year_wise_sub_types = $date_wise_sub_types = [];

            if( $data_column_range ){
              foreach( $data_column_range as $tbdate_index => $tbdate_column_group ){  

                $s_label = isset($sub_items['new_label']) ? $sub_items['new_label'] : $sub_items['label'];

                $cell_val = (isset($all_item_header_list[$tbdate_index][$sub_key])) ? $all_item_header_list[$tbdate_index][$sub_key] : 0;
                $cell_val = round($cell_val, 2);


                if( $sub_items['label'] == 'Current Year Profit' ){

                  $retain_total = isset($final_all_total['Retained Profit'][$tbdate_column_group['column']]) ? str_replace(',','',$final_all_total['Retained Profit'][$tbdate_column_group['column']]) : 0;
                  if( $retain_total == null ){ $retain_total = 0; }
                  $cell_val = $cell_val + (float)$retain_total;
                  $cell_val = round($cell_val,2);

                  
                } 

                if( !isset($sub_items['download_tr_hide']) ){                                        
                  $spreadsheet->getActiveSheet()->getCell( $tbdate_column_group['column'].$p_index )->setValue( convert_decimal_format($cell_val) )->getStyle( $tbdate_column_group['column'].$p_index )->applyFromArray($subitemStyle);
                }

                $sub_total_date_wise_arr[$tbdate_column_group['column']][] = $cell_val;

                $final_all_total[ $sub_items['label'] ][$tbdate_column_group['column']] =  $cell_val;
                $date_wise_sub_types[$month_wise_grouped[date( 'm/d/Y', strtotime( $tbdate_index ) )]][] = $cell_val;
                $year_wise_sub_types[$year_wise_grouped[date( 'm/d/Y', strtotime( $tbdate_index ) )]][] = $cell_val;
                $sub_date_wise_sub_types[$month_wise_grouped[date( 'm/d/Y', strtotime( $tbdate_index ) )]][] = $cell_val;
                $sub_year_wise_sub_types[$year_wise_grouped[date( 'm/d/Y', strtotime( $tbdate_index ) )]][] = $cell_val;
              }
            }

            if( !isset($sub_items['download_tr_hide']) ){   
              $p_index++; 
            }
          }
        }

        if( isset($bl_key['data']['total']) && is_array($bl_key['data']['total']) && count($bl_key['data']['total']) > 0 ){
          $minus_items_list = isset($bl_key['data']['total']['minus_items']) ? $bl_key['data']['total']['minus_items'] : [];
          $total_label = isset($bl_key['data']['total']['show_label']) ? $bl_key['data']['total']['show_label'] : $bl_key['data']['total']['label'];
          if( !isset($bl_key['data']['total']['hide_tr']) ){
            $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $total_label )->getStyle( 'A'.$p_index )->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
          }

          $s_year_wise_sub_types = $s_date_wise_sub_types = [];                           
          if( $data_column_range ){
            foreach( $data_column_range as $s_tbdate_index => $s_tbdate_column_group ){  
// $s_cell_val = (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) ? array_sum($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) : 0;
// $s_cell_val = round($s_cell_val,2);

              $s_cell_val = 0;

              if (isset($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) && count($sub_total_date_wise_arr[$s_tbdate_column_group['column']]) > 0) {
                $all_values = $sub_total_date_wise_arr[$s_tbdate_column_group['column']];
                $s_cell_val = array_sum($all_values);
                if( is_array($minus_items_list) && count($minus_items_list) > 0 ){
                  foreach ($minus_items_list as $index) {
                    if (isset($all_values[$index])) {
                      $s_cell_val -= $all_values[$index] * 2;
                    }
                  }    
                }

              }
              $s_cell_val = round($s_cell_val,2);

              if( !isset($bl_key['data']['total']['hide_tr']) ){

                $spreadsheet->getActiveSheet()->getCell( $s_tbdate_column_group['column'].$p_index )->setValue( convert_decimal_format($s_cell_val) )->getStyle( $s_tbdate_column_group['column'].$p_index )->applyFromArray(($bl_key['data']['total']['bold'] == true) ? $itemStyle : $subitemStyle);
              }

              $s_date_wise_sub_types[$month_wise_grouped[date( 'm/d/Y', strtotime( $s_tbdate_index ) )]][] = $s_cell_val;
              $s_year_wise_sub_types[$year_wise_grouped[date( 'm/d/Y', strtotime( $s_tbdate_index ) )]][] = $s_cell_val;

              $final_all_total[ $bl_key['data']['total']['label'] ][$s_tbdate_column_group['column']] =  $s_cell_val;
            }
          }

          
          if( !isset($bl_key['data']['total']['hide_tr']) ){
            $p_index++;
          }
        }



        if( isset($bl_key['final']) && is_array($bl_key['final']) && count($bl_key['final']) > 0 ){
          foreach( $bl_key['final'] as $final_index => $final_items ){  

            if( isset($final_items['label']) &&  !empty($final_items['label']) ){

              $f_year_wise_sub_types = $f_date_wise_sub_types = []; 

              $spreadsheet->getActiveSheet()->getCell( 'A'.$p_index )->setValue( $final_items['label'] )->getStyle( 'A'.$p_index )->applyFromArray(($final_items['bold'] == true) ? $itemStyle : $subitemStyle);                        
              if( $data_column_range ){
                foreach( $data_column_range as $n_tbdate_index => $n_tbdate_column_group ){  
                  $n_cell_val = 0;

                  if( isset($final_items['direct']) && $final_items['direct'] == true ){
                    $n_cell_val = isset($final_items['value']) ? $final_items['value'] : 0;
                  }else{
                    if( isset($final_items['items']) && is_array($final_items['items']) && count($final_items['items']) > 0 ){
                      $s_data = $final_items['items'];
                      $operators = $final_items['operators'];
                      foreach ($s_data as $i => $value) {
                        $n_cell_val += ($operators[$i] === '+') ? $final_all_total[$value][$n_tbdate_column_group['column']] : -$final_all_total[$value][$n_tbdate_column_group['column']];
                      }
                    }
                  }

                  $n_cell_val = round($n_cell_val,2);
                  $spreadsheet->getActiveSheet()->getCell( $n_tbdate_column_group['column'].$p_index )->setValue( convert_decimal_format($n_cell_val) )->getStyle( $n_tbdate_column_group['column'].$p_index )->applyFromArray(($final_items['bold'] == true)  ? $itemStyle : $subitemStyle);

                  $f_date_wise_sub_types[$month_wise_grouped[date( 'm/d/Y', strtotime( $s_tbdate_index ) )]][] = $n_cell_val;
                  $f_year_wise_sub_types[$year_wise_grouped[date( 'm/d/Y', strtotime( $s_tbdate_index ) )]][] = $n_cell_val;

                  $final_all_total[$final_items['label']][$n_tbdate_column_group['column']] =  $n_cell_val;

                }
              }

              
              $p_index++;
            }

          }
        }


        $all_item_rows[] = 'A'.$p_index;
      }

      $all_item_rows[] = 'A'.$p_index;

    }
    
    $sheet = $spreadsheet->getActiveSheet();
    $cellArray = $sheet->toArray(null, true, true, true);
    $cell_wise_item = [];
    
    if( $cellArray ){
        foreach( $cellArray as $cellItem ){
            if( isset($cellItem['A']) && !is_null($cellItem['A']) && !empty($cellItem['A']) ){
                $cell_wise_item[$cellItem['A']] = $cellItem;
            }
        }
    }
   
    $cell_wise_item['Other Liabilities List'] = ['A' => 'Other Liabilities List'];
    if( isset($cell_wise_item['Other Current Liabilities']) && is_array($cell_wise_item['Other Current Liabilities']) && count($cell_wise_item['Other Current Liabilities']) > 0 ){
        foreach( $cell_wise_item['Other Current Liabilities'] as $other_key => $o_value ){
            if( $other_key != 'A' ){
                if( !isset($cell_wise_item['Other Liabilities List'][$other_key]) ){
                    $cell_wise_item['Other Liabilities List'][$other_key] = (float)str_replace(',','',$o_value);    
                }else{
                    $cell_wise_item['Other Liabilities List'][$other_key] += (float)str_replace(',','',$o_value);
                }
                
            }
        }
    }
    if( isset($cell_wise_item['Other Non Current Liabilities']) && is_array($cell_wise_item['Other Non Current Liabilities']) && count($cell_wise_item['Other Non Current Liabilities']) > 0 ){
      
        foreach( $cell_wise_item['Other Non Current Liabilities'] as $other_key => $o_value ){
            if( $other_key != 'A' ){
                if( !isset($cell_wise_item['Other Liabilities List'][$other_key]) ){
                    $cell_wise_item['Other Liabilities List'][$other_key] = (float)str_replace(',','',$o_value);    
                }else{
                    $cell_wise_item['Other Liabilities List'][$other_key] += (float)str_replace(',','',$o_value);
                }
            }
        }
    }
    
     $cell_wise_item['Other Assets List'] = ['A' => 'Other Assets List'];
    if( isset($cell_wise_item['Other Current Assets']) && is_array($cell_wise_item['Other Current Assets']) && count($cell_wise_item['Other Current Assets']) > 0 ){
        foreach( $cell_wise_item['Other Current Assets'] as $other_key => $o_value ){
            if( $other_key != 'A' ){
                if( !isset($cell_wise_item['Other Assets List'][$other_key]) ){
                    $cell_wise_item['Other Assets List'][$other_key] = (float)str_replace(',','',$o_value);    
                }else{
                    $cell_wise_item['Other Assets List'][$other_key] += (float)str_replace(',','',$o_value);
                }
                
            }
        }
    }
    if( isset($cell_wise_item['Other Non Current Assets']) && is_array($cell_wise_item['Other Non Current Assets']) && count($cell_wise_item['Other Non Current Assets']) > 0 ){
        foreach( $cell_wise_item['Other Non Current Assets'] as $other_key => $o_value ){
            if( $other_key != 'A' ){
                if( !isset($cell_wise_item['Other Assets List'][$other_key]) ){
                    $cell_wise_item['Other Assets List'][$other_key] = (float)str_replace(',','',$o_value);    
                }else{
                    $cell_wise_item['Other Assets List'][$other_key] += (float)str_replace(',','',$o_value);
                }
                
            }
        }
    }

    
    $cell_wise_item['Other Capital'] = ['A' => 'Other Capital'];
    $cell_wise_item['Total Debt'] = ['A' => 'Total Debt'];
    $cell_wise_item['Total Funding'] = ['A' => 'Total Funding'];
    $cell_wise_item['Break Even Sales'] = ['A' => 'Break Even Sales'];
    $cell_wise_item['A/R Days'] = ['A' => 'A/R Days'];
    $cell_wise_item['A/P Days'] = ['A' => 'A/P Days'];
    $cell_wise_item['Inventory Days'] = ['A' => 'Inventory Days'];
    $cell_wise_item['W/C Days'] = ['A' => 'W/C Days'];
    $cell_wise_item['Working Capital'] = ['A' => 'Working Capital'];
    $cell_wise_item['BS Category'] = ['A' => 'BS Category'];
    $cell_wise_item['Total Overheads'] = ['A' => 'Total Overheads'];
    $cell_wise_item['Gross Mrg Perc'] = ['A' => 'Gross Mrg Perc'];
    $cell_wise_item['Net Mrg Perc'] = ['A' => 'Net Mrg Perc'];
    $cell_wise_item['CurrentRatio'] = ['A' => 'Current Ratio'];
    $cell_wise_item['QuickRatio'] = ['A' => 'Quick Ratio'];
    $cell_wise_item['DebtToEquity'] = ['A' => 'Debt to Equity'];
    $cell_wise_item['Interest Cover'] = ['A' => 'Interest Cover'];
    $cell_wise_item['Return on equity'] = ['A' => 'Return on equity'];
    $cell_wise_item['Capital Withdrawn'] = ['A' => 'Capital Withdrawn'];
    $cell_wise_item['Operating Cash Flow'] = ['A' => 'Operating Cash Flow'];
    $cell_wise_item['Revenue Growth %'] = ['A' => 'Revenue Growth %'];
    $cell_wise_item['COGS Growth %'] = ['A' => 'COGS Growth %'];
    $cell_wise_item['Overheads Growth %'] = ['A' => 'Overheads Growth %'];
    $cell_wise_item['Operating Profit %'] = ['A' => 'Operating Profit %'];
    $cell_wise_item['Operating Cash Profit'] = ['A' => 'Operating Cash Profit'];
    $cell_wise_item['Working Capital per ₹100'] = ['A' => 'Working Capital per ₹100'];
    $cell_wise_item['Working Capital Turnover'] = ['A' => 'Working Capital Turnover'];
    $cell_wise_item['Debt to Capital'] = ['A' => 'Debt to Capital'];
    $cell_wise_item['Other Capital Turnover'] = ['A' => 'Other Capital Turnover'];
    $cell_wise_item['Net Operating Assets %'] = ['A' => 'Net Operating Assets %'];
    $cell_wise_item['Return on Capital %'] = ['A' => 'Return on Capital %'];
    $cell_wise_item['Other Capital %'] = ['A' => 'Other Capital %'];
    $cell_wise_item['demotest'] = ['A' => 'demotest'];
    $cell_wise_item['industryData'] = $industryData;

    $next_index = 'A';

    foreach($column_width_list as $column_key => $column_width ){
        if( $column_key != 'A' ){
           //  $other_capital = ( (float)str_replace(',','', $cell_wise_item['Fixed Assets'][$column_key] ) + (float)str_replace(',','', $cell_wise_item['Other Non Current Assets'][$column_key] ) + (float)str_replace(',','', $cell_wise_item['Other Current Assets'][$column_key] ) ) - ( (float)str_replace(',','', $cell_wise_item['Other Current Liabilities'][$column_key] ) - (float)str_replace(',','', $cell_wise_item['Other Non Current Liabilities'][$column_key] ) );

             $other_capital = ( (float)str_replace(',','', $cell_wise_item['Fixed Assets'][$column_key] ) + (float)str_replace(',','', $cell_wise_item['Other Non Current Assets'][$column_key] ) ) - ( (float)str_replace(',','', $cell_wise_item['Other Non Current Liabilities'][$column_key] ) );

             $other_capital = round($other_capital,2);
             $cell_wise_item['Other Capital'][ $column_key ] = convert_decimal_format($other_capital);
             $total_debt = (float)str_replace(',','', $cell_wise_item['Bank Loans - Current'][$column_key] ) + (float)str_replace(',','', $cell_wise_item['Bank Loans - Non Current'][$column_key] );
             $total_debt = round($total_debt, 2);    
             $cell_wise_item['Total Debt'][ $column_key ] = convert_decimal_format($total_debt);
             
             $total_funding = $total_debt + ( (float)str_replace(',','', $cell_wise_item['Equity'][$column_key] ) - (float)str_replace(',','', $cell_wise_item['Cash & Bank'][$column_key] ) );
            $total_funding = round($total_funding, 2);
            $cell_wise_item['Total Funding'][ $column_key ] = convert_decimal_format($total_debt);
            
            $total_overheads = (float)str_replace(',','', $cell_wise_item['Overheads'][$column_key] ) + (float)str_replace(',','', $cell_wise_item['Depreciation'][$column_key] );
            $cell_wise_item['Total Overheads'][ $column_key ] = convert_decimal_format( $total_overheads );
            $gross_mrg_perc = comman_module_formula( (float)str_replace(',','',$cell_wise_item['Sales'][$column_key]), (float)str_replace(',','',$cell_wise_item['Gross Profit'][$column_key]), 2 );
            $cell_wise_item['Gross Mrg Perc'][ $column_key ] = $gross_mrg_perc;

            $net_mrg_perc = comman_module_formula( (float)str_replace(',','',$cell_wise_item['Sales'][$column_key]), (float)str_replace(',','',$cell_wise_item['Profit after Tax'][$column_key]), 2 );
            $cell_wise_item['Net Mrg Perc'][ $column_key ] = $net_mrg_perc;


            $break_even_sales = comman_module_formula( $gross_mrg_perc, $total_overheads, 2 );
            $cell_wise_item['Break Even Sales'][ $column_key ] = convert_decimal_format($break_even_sales);
            
            //$acc_rec_days = comman_cashmg_formula( (float)str_replace(',','',$cell_wise_item['Sales'][$column_key]), (float)str_replace(',','',$cell_wise_item['Accounts Receivable'][$column_key]), 1,  1, 2 );
            //$inventory_days = comman_cashmg_formula( (float)str_replace(',','',$cell_wise_item['COGS'][$column_key]), (float)str_replace(',','',$cell_wise_item['Closing Stock'][$column_key]), 1,  1, 2 );
            //$acc_pay_days = comman_cashmg_formula( (float)str_replace(',','',$cell_wise_item['COGS'][$column_key]), (float)str_replace(',','',$cell_wise_item['Accounts Payable'][$column_key]), 1,  1, 2 );

            $previousMonthAR = $previousMonthInventory = $previousMonthAP = 0;  
            $currentMonthAR = isset($cell_wise_item['Accounts Receivable'][$column_key]) ? str_replace(',', '', $cell_wise_item['Accounts Receivable'][$column_key] ) : 0;
            $currentMonthAP = isset($cell_wise_item['Accounts Payable'][$column_key]) ? str_replace(',', '', $cell_wise_item['Accounts Payable'][$column_key] ) : 0;
            $currentMonthInventory = isset($cell_wise_item['Closing Stock'][$column_key]) ? str_replace(',', '', $cell_wise_item['Closing Stock'][$column_key] ) : 0;

            $r_revenue = isset($cell_wise_item['Sales'][$column_key]) ? str_replace(',', '', $cell_wise_item['Sales'][$column_key] ) : 0;
            $c_cogs = isset($cell_wise_item['COGS'][$column_key]) ? str_replace(',', '', $cell_wise_item['COGS'][$column_key] ) : 0;

            if( $next_index != 'A' ){
              $previousMonthAR = isset($cell_wise_item['Accounts Receivable'][$next_index]) ? str_replace(',', '', $cell_wise_item['Accounts Receivable'][$next_index] ) : 0;
             
              $previousMonthAP = isset($cell_wise_item['Accounts Payable'][$next_index]) ? str_replace(',', '', $cell_wise_item['Accounts Payable'][$next_index] ) : 0;
              $previousMonthInventory = isset($cell_wise_item['Closing Stock'][$next_index]) ? str_replace(',', '', $cell_wise_item['Closing Stock'][$next_index] ) : 0;
            }

            $acc_rec_days = $r_revenue != 0 ? ((($previousMonthAR + $currentMonthAR) / 2) / $r_revenue) * 30 : 0;
            $acc_rec_days = round($acc_rec_days,2);
            $acc_pay_days = $c_cogs != 0  ? ((($previousMonthAP + $currentMonthAP) / 2) / $c_cogs) * 30  : 0;
            $acc_pay_days = round($acc_pay_days,2);
            $inventory_days = $c_cogs != 0 ? ((($previousMonthInventory + $currentMonthInventory) / 2) / $c_cogs) * 30 : 0;
            $inventory_days = round($inventory_days,2);
            $wc_capital_days = ( $acc_rec_days + $inventory_days ) - $acc_pay_days;
            $wc_capital_days = round($wc_capital_days,2);
            $cell_wise_item['A/R Days'][ $column_key ] = convert_decimal_format($acc_rec_days);
            $cell_wise_item['A/P Days'][ $column_key ] = convert_decimal_format($acc_pay_days);
            $cell_wise_item['Inventory Days'][ $column_key ] = convert_decimal_format($inventory_days);
            $cell_wise_item['W/C Days'][ $column_key ] = convert_decimal_format($wc_capital_days);
            
            $working_capital = ( (float)str_replace(',','',$cell_wise_item['Accounts Receivable'][$column_key]) + (float)str_replace(',','',$cell_wise_item['Closing Stock'][$column_key]) ) - (float)str_replace(',','',$cell_wise_item['Accounts Payable'][$column_key]);
            $working_capital = round($working_capital,2);
             
            $cell_wise_item['Working Capital'][ $column_key ] = convert_decimal_format($working_capital); 

            if ( (float)str_replace(',', '', $cell_wise_item['Sales'][$column_key]) != 0) {
              $wc_per_100 = ($working_capital / (float)str_replace(',', '', $cell_wise_item['Sales'][$column_key])) * 100;
              $wc_per_100 = round($wc_per_100, 2);
            } else {
              $wc_per_100 = 0;
            }

             if ($other_capital != 0) {
              $wc_turn_over = ((float)str_replace(',', '', $cell_wise_item['Sales'][$column_key]) / $working_capital) * (12 / 1);
              $wc_turn_over = round($wc_turn_over, 2);
            } else {
              $wc_turn_over = 0;
            }
            
            $cell_wise_item['Working Capital per ₹100'][ $column_key ] = $wc_per_100; 
            $cell_wise_item['Working Capital Turnover'][ $column_key ] = $wc_turn_over; 

            $current_ratio = 0;
            $Current_Liabilities = isset($cell_wise_item['Current Liabilities'][$column_key]) ? str_replace(',', '', $cell_wise_item['Current Liabilities'][$column_key]) : 0;
            $Current_Assets = isset($cell_wise_item['Current Assets'][$column_key]) ? str_replace(',', '', $cell_wise_item['Current Assets'][$column_key]) : 0;
            $closing_stocks = isset($cell_wise_item['Closing Stock'][$column_key]) ? str_replace(',', '', $cell_wise_item['Closing Stock'][$column_key]) : 0;
            $bank_loan_current = isset($cell_wise_item['Bank Loans - Current'][$column_key]) ? str_replace(',', '', $cell_wise_item['Bank Loans - Current'][$column_key]) : 0;
            $bank_loan_non_current = isset($cell_wise_item['Bank Loans - Non Current'][$column_key]) ? str_replace(',', '', $cell_wise_item['Bank Loans - Non Current'][$column_key]) : 0;
            $Equity = isset($cell_wise_item['Equity'][$column_key]) ? str_replace(',', '', $cell_wise_item['Equity'][$column_key]) : 0;
            $acc_rec = isset($cell_wise_item['Accounts Receivable'][$column_key]) ? str_replace(',', '', $cell_wise_item['Accounts Receivable'][$column_key]) : 0;
            $acc_pay = isset($cell_wise_item['Accounts Payable'][$column_key]) ? str_replace(',', '', $cell_wise_item['Accounts Payable'][$column_key]) : 0;
            $fixed_assets = isset($cell_wise_item['Fixed Assets'][$column_key]) ? str_replace(',', '', $cell_wise_item['Fixed Assets'][$column_key]) : 0;
            $other_non_current_assets = isset($cell_wise_item['Other Non Current Assets'][$column_key]) ? str_replace(',', '', $cell_wise_item['Other Non Current Assets'][$column_key]) : 0;
            $other_current_assets = isset($cell_wise_item['Other Current Assets'][$column_key]) ? str_replace(',', '', $cell_wise_item['Other Current Assets'][$column_key]) : 0;
            $other_current_liability = isset($cell_wise_item['Other Current Liabilities'][$column_key]) ? str_replace(',', '', $cell_wise_item['Other Current Liabilities'][$column_key]) : 0;
            $other_none_current_liability = isset($cell_wise_item['Other Non Current Liabilities'][$column_key]) ? str_replace(',', '', $cell_wise_item['Other Non Current Liabilities'][$column_key]) : 0;
            $revenue = isset($cell_wise_item['Sales'][$column_key]) ? str_replace(',', '', $cell_wise_item['Sales'][$column_key]) : 0;
            $cogs = isset($cell_wise_item['COGS'][$column_key]) ? str_replace(',', '', $cell_wise_item['COGS'][$column_key]) : 0;
            $profit_after_tax = isset($cell_wise_item['Profit after Tax'][$column_key]) ? str_replace(',', '', $cell_wise_item['Profit after Tax'][$column_key]) : 0;
            $operating_profit = isset($cell_wise_item['Operating Profit'][$column_key]) ? str_replace(',', '', $cell_wise_item['Operating Profit'][$column_key]) : 0;
            $total_assets = isset($cell_wise_item['Total Assets'][$column_key]) ? str_replace(',', '', $cell_wise_item['Total Assets'][$column_key]) : 0;
            $gross_margin = isset($cell_wise_item['Gross Profit'][$column_key]) ? str_replace(',', '', $cell_wise_item['Gross Profit'][$column_key]) : 0;
            $interest_charge = isset($cell_wise_item['Finance Cost'][$column_key]) ? str_replace(',', '', $cell_wise_item['Finance Cost'][$column_key]) : 0;
            $old_acc_rec = isset($cell_wise_item['Accounts Receivable'][$next_index]) ? str_replace(',', '', $cell_wise_item['Accounts Receivable'][$next_index]) : 0;
            $old_closing_st = isset($cell_wise_item['Closing Stock'][$next_index]) ? str_replace(',', '', $cell_wise_item['Closing Stock'][$next_index]) : 0;
            $old_acc_pay = isset($cell_wise_item['Accounts Payable'][$next_index]) ? str_replace(',', '', $cell_wise_item['Accounts Payable'][$next_index]) : 0;
            $Depreciation = isset($cell_wise_item['Depreciation'][$column_key]) ? str_replace(',', '', $cell_wise_item['Depreciation'][$column_key]) : 0;
            $retPFT = isset($cell_wise_item['Retained Profit'][$next_index]) ? str_replace(',', '', $cell_wise_item['Retained Profit'][$next_index]) : 0;
            $oldSales = isset($cell_wise_item['Sales'][$next_index]) ? str_replace(',', '', $cell_wise_item['Sales'][$next_index]) : 0;
            $oldCogs = isset($cell_wise_item['COGS'][$next_index]) ? str_replace(',', '', $cell_wise_item['COGS'][$next_index]) : 0;
            $oldoverheads = isset($cell_wise_item['Overheads'][$next_index]) ? str_replace(',', '', $cell_wise_item['Overheads'][$next_index]) : 0;
            $oldDepreciation = isset($cell_wise_item['Depreciation'][$next_index]) ? str_replace(',', '', $cell_wise_item['Depreciation'][$next_index]) : 0;

            $old_overheads = (float)$oldoverheads + (float)$oldDepreciation;

            $cell_wise_item['Revenue Growth %'][ $column_key ] = comman_growth_formula((float)$oldSales, (float)str_replace(',', '', $cell_wise_item['Sales'][$column_key]), 2);
            $cell_wise_item['COGS Growth %'][ $column_key ] = comman_growth_formula((float)$oldCogs, (float)str_replace(',', '', $cell_wise_item['COGS'][$column_key]), 2);
            $cell_wise_item['Overheads Growth %'][ $column_key ] = comman_growth_formula((float)$oldoverheads, (float)str_replace(',','', $cell_wise_item['Overheads'][$column_key] ), 2);       
            $cell_wise_item['Operating Profit %'][ $column_key ] = comman_module_formula( (float)str_replace(',', '', $cell_wise_item['Sales'][$column_key]), $operating_profit, 2 );       

            if( $Current_Liabilities != 0 ){
               $current_ratio =  round( (float)$Current_Assets / (float)$Current_Liabilities,2);
            }

            $quick_ratio = 0;
            if( $Current_Liabilities != 0 ){
                $quick_ratio = ( (float)$Current_Assets - (float)$closing_stocks ) / (float)$Current_Liabilities;
            }


            $cell_wise_item['CurrentRatio'][ $column_key ] = convert_decimal_format($current_ratio,2);
            $cell_wise_item['QuickRatio'][ $column_key ] = convert_decimal_format($quick_ratio,2);

            $cell_wise_item['Capital Withdrawn'][ $column_key ] = convert_decimal_format((float)$retPFT + (float)$Equity);

            $total_debt = $bank_loan_current  +  $bank_loan_non_current;
            $total_debt = round($total_debt, 2);
            $debt_equity = 0;
            if( $total_debt > 0 ){
                $debt_equity = round( $total_debt / $Equity, 2 );
            }

            $debt_capital = 0;
            if (  $total_debt > 0) {
              $debt_capital = round(  $total_debt/ (  $total_debt +  $Equity ),2);
            }

            $cell_wise_item['DebtToEquity'][ $column_key ] = convert_decimal_format($debt_equity,2);
            $cell_wise_item['Debt to Capital'][ $column_key ] = convert_decimal_format($debt_capital,2);


            //$n_cell_val1 = ( $fixed_assets + $other_non_current_assets + $other_current_assets ) - ( $other_current_liability -  $other_none_current_liability );
            $n_cell_val1 = ( $fixed_assets + $other_non_current_assets  ) - ( $other_none_current_liability );

            $n_cell_val1 = round($n_cell_val1,2);

            $cell_wise_item['demotest'][ $column_key ] = $n_cell_val1;

            $celvv2 = ( (float)str_replace(',', '', $cell_wise_item['Sales'][$column_key]) * count($data_column_range)) * (1 / count($data_column_range));
            $other_capital_perc = 0;
            if ($celvv2 != 0) {
              $other_capital_perc = ($n_cell_val1 / $celvv2) / (count($data_column_range) / 100);
              $other_capital_perc = round($other_capital_perc, 2);
            }
  
            $cell_wise_item['Other Capital %'][ $column_key ] = convert_decimal_format($debt_capital,2);

            $oc_turnover = 0;
            if ($n_cell_val1 != 0) {
              $oc_turnover = ( (float)str_replace(',', '', $cell_wise_item['Sales'][$column_key]) * count($data_column_range)) / $n_cell_val1;
              $oc_turnover = round($oc_turnover, 2);
            }

            $n_cell_val4 = ( $acc_rec + $closing_stocks ) - $acc_pay;
            $n_cell_val4 = $n_cell_val4 + $n_cell_val1;
            $n_cell_val4 = round($n_cell_val4,2);

            $cell_wise_item['Other Capital Turnover'][ $column_key ] = convert_decimal_format($oc_turnover,2);

            $net_os_assets = 0;
            if ($celvv2 != 0) {
              $net_os_assets = ($n_cell_val4 / $celvv2) / (count($data_column_range) / 100);
              $net_os_assets = round($net_os_assets,2);
            }

            $cell_wise_item['Net Operating Assets %'][ $column_key ] = convert_decimal_format($net_os_assets);
            
           
            $asset_tunover = 0;
            if (  $n_cell_val4 != 0) {
                $asset_tunover = ( $revenue *  count($data_column_range) / 1 ) / $n_cell_val4;
                $asset_tunover = round($asset_tunover,2);
            } 

            $return_capital_perc = 0;
            if (($n_cell_val4 / 100) != 0) {
              $return_capital_perc = ((float)str_replace(',', '', $cell_wise_item['Operating Profit'][$column_key]) * count($data_column_range) / 1) / ($n_cell_val4 / 100);
              $return_capital_perc = round($return_capital_perc,2);
            }


            $cell_wise_item['Return on Capital %'][ $column_key ] = convert_decimal_format($return_capital_perc);

            //   if( $column_key == 'C' ){


            //   dd(['Current Ratio' =>  $current_ratio,
            //   'Quick Ratio' =>  $quick_ratio,
            //   'Debt-to-Equity' =>  $debt_equity, 
            //   'Asset Turnover' =>  $asset_tunover,
            //   'ROE' => $roe,
            //   'ROA' => $rot,
            //   'Gross Margin' =>  $gross_margin_per,
            //   'Net Margin' =>  $net_margin_per,
            //   'Interest Coverage' =>  $Interest_Cover,
            //   'Receivables Days' =>  $rec_days, 
            //   'Payable Days' =>  $pay_days,
            //   'Working Capital Days' =>  $work_days, 
            //   'Operating CF Margin' =>  $Operating_CF_Margin,
            //   'CF Coverage' =>  $Cash_Flow_Coverage,
            //   'CF to Debt' => $Cash_Flow_Debt,
            //   'Capex Coverage' =>  $Capex_Coverage ]);
            // }

             
            $roe = 0;
            if ( (float)$Equity / 100 != 0) {
                //$roe  = ( (float)$profit_after_tax * count($data_column_range) / 1 ) / ( (float)$Equity  / 100);              
                $roe = ( (float)$profit_after_tax / (float)$Equity) * 100 * (12 / 1);
                $roe = round($roe,2);
            }

            $cell_wise_item['Return on equity'][ $column_key ] = convert_decimal_format($roe,2);
    
            $rot = 0;
            if ( (float)$total_assets  / 100 != 0) {
                $rot  = ( $operating_profit * count($data_column_range) / 1 ) / ( (float)$total_assets / 100);
                $rot = round($rot,2);
            } 
    
            $gross_margin_per = comman_module_formula( $revenue, $gross_margin, 2 );
            $net_margin_per = comman_module_formula( $revenue, $profit_after_tax, 2 );
    
           $Interest_Cover = 0;
           if( (float)$interest_charge > 0 ){
              //  $Interest_Cover = round( ((float)$interest_charge / (float)$operating_profit) * 100, 2 );
              $Interest_Cover = round( ((float)$operating_profit / (float)$interest_charge), 2 );
            }
      
            $cell_wise_item['Interest Cover'][ $column_key ] = convert_decimal_format($Interest_Cover,2);

            $rec_days = comman_cashmg_formula( $revenue, $acc_rec, 1,  count($data_column_range), 2 );
            $invt_days = comman_cashmg_formula( $cogs, $closing_stocks, 1,  count($data_column_range), 2 );
            $pay_days = comman_cashmg_formula( $cogs, $acc_pay, 1,  count($data_column_range), 2 );
            $work_days = ( $rec_days + $invt_days ) - $pay_days;
    
            $operating_cash_profit =  (float)$operating_profit + (float)$Depreciation;
            $operating_cash_profit = round($operating_cash_profit, 2);
    
            $cash_acc_rec = (float)$acc_rec - (float)$old_acc_rec;
            $cash_closing_st = (float)$closing_stocks - (float)$old_closing_st;
            $cash_acc_pay = (float)$acc_pay - (float)$old_acc_pay;
    
            $operating_cash_flow =  $operating_cash_profit - $cash_acc_rec - $cash_closing_st + ( $cash_acc_pay );


            $cell_wise_item['Operating Cash Flow'][ $column_key ] = convert_decimal_format($operating_cash_flow);
            $cell_wise_item['Operating Cash Profit'][ $column_key ] = convert_decimal_format($operating_cash_profit,2);

            $Operating_CF_Margin = ($revenue > 0) ? round( $operating_cash_flow / $revenue, 2 ) : 0;
            $Cash_Flow_Coverage = ($Current_Liabilities > 0) ? round( $operating_cash_flow / $Current_Liabilities, 2 ) : 0;                    
            $Cash_Flow_Debt = ($total_debt > 0) ? round( $operating_cash_flow / $total_debt, 2 ) : 0;                    
            $Capex_Coverage = ($fixed_assets > 0) ? round( $operating_cash_flow / $fixed_assets, 2 ) : 0;   
    
    
            $current_ratio_res = give_bs_category_weight( 'Current Ratio', $current_ratio, $single_industry_arr );
            $quick_ratio_res = give_bs_category_weight( 'Quick Ratio', $quick_ratio, $single_industry_arr );
            $debt_equity_res = give_bs_category_weight( 'Debt-to-Equity', $debt_equity, $single_industry_arr, 1 );
            $asset_tunover_res = give_bs_category_weight( 'Asset Turnover', $asset_tunover, $single_industry_arr );
            $roe_res = give_bs_category_weight( 'ROE', $roe, $single_industry_arr );
            $rot_res = give_bs_category_weight( 'ROA', $rot, $single_industry_arr );
            $gross_margin_per_res = give_bs_category_weight( 'Gross Margin', $gross_margin_per, $single_industry_arr );
            $net_margin_per_res = give_bs_category_weight( 'Net Margin', $net_margin_per, $single_industry_arr );
            $Interest_Cover_res = give_bs_category_weight( 'Interest Coverage', $Interest_Cover, $single_industry_arr );
            $rec_days_res = give_bs_category_weight( 'Receivables Days', $rec_days, $single_industry_arr, 1 );
            $pay_days_res = give_bs_category_weight( 'Payable Days', $pay_days, $single_industry_arr );
            $work_days_res = give_bs_category_weight( 'Working Capital Days', $work_days, $single_industry_arr, 1 );
            $Operating_CF_Margin_res = give_bs_category_weight( 'Operating CF Margin', $Operating_CF_Margin, $single_industry_arr );
            $Cash_Flow_Coverage_res = give_bs_category_weight( 'CF Coverage', $Cash_Flow_Coverage, $single_industry_arr );
            $Cash_Flow_Debt_res = give_bs_category_weight( 'CF to Debt', $Cash_Flow_Debt, $single_industry_arr );
            $Capex_Coverage_res = give_bs_category_weight( 'Capex Coverage', $Capex_Coverage, $single_industry_arr );
    
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
    
            $final_score =  $current_ratio_score + $quick_ratio_score + $debt_equity_score + $asset_tunover_score + $roe_score + $rot_score + $gross_margin_per_score + $net_margin_per_score + $Interest_Cover_score + $rec_days_score + $pay_days_score + $work_days_score + $Operating_CF_Margin_score + $Cash_Flow_Coverage_score + $Cash_Flow_Debt_score + $Capex_Coverage_score;

           $next_index = $column_key ;

            $cell_wise_item['BS Category'][ $column_key ] = convert_decimal_format($final_score); 
              
        }
    }
    $next_index_p = 'B';
  
                
    $revenue_arr = isset($cell_wise_item['Sales']) ? $cell_wise_item['Sales'] : [];
    $cogs_arr = isset($cell_wise_item['COGS']) ? $cell_wise_item['COGS'] : [];
    $gross_mrg_percentage_arr =  isset($cell_wise_item['Gross Mrg Perc']) ?  $cell_wise_item['Gross Mrg Perc'] : [];
    $overheads_arr = isset($cell_wise_item['Total Overheads']) ?  $cell_wise_item['Total Overheads'] : [];
  
    $accounts_receivable_arr = isset($cell_wise_item['Accounts Receivable']) ? $cell_wise_item['Accounts Receivable'] : [];
    $closing_stock_arr = isset($cell_wise_item['Closing Stock']) ? $cell_wise_item['Closing Stock'] : [];
    $accounts_payable_arr = isset($cell_wise_item['Accounts Payable']) ? $cell_wise_item['Accounts Payable'] : [];
    $cell_wise_item['IMPACT OF MANAGEMENT DECISIONS'] = ['A' => 'IMPACT OF MANAGEMENT DECISIONS'];
    foreach( $column_width_list as $column_key => $column_width ){
        if( $column_key == 'A' ){
            continue;
        }
        $current_revenue = isset($revenue_arr[$column_key]) ? str_replace(',', '', $revenue_arr[$column_key]) : 0;
        $current_gsMrg = isset($gross_mrg_percentage_arr[$column_key]) ?  str_replace(',', '', $gross_mrg_percentage_arr[$column_key] ) : 0;
        $current_overhead = isset($overheads_arr[$column_key]) ?  str_replace(',', '', $overheads_arr[$column_key] ) : 0;
        $current_accrec = isset($accounts_receivable_arr[$column_key]) ?  str_replace(',', '', $accounts_receivable_arr[$column_key] ) : 0;
        $current_closest = isset($closing_stock_arr[$column_key]) ?  str_replace(',', '', $closing_stock_arr[$column_key] ) : 0;
        $current_accpay = isset($accounts_payable_arr[$column_key]) ?  str_replace(',', '', $accounts_payable_arr[$column_key] ) : 0;
        $current_cogs = isset($cogs_arr[$column_key]) ?  str_replace(',', '', $cogs_arr[$column_key] ) : 0;

        if( $column_key == 'B' ){
            $prev_gsMrg = $prev_revenue = $prev_accrec = $prev_closest = $prev_accpay = $prev_overhead = $prev_cogs = 0;
        }else{
            $prev_gsMrg = isset($gross_mrg_percentage_arr[$next_index_p]) ? str_replace(',', '', $gross_mrg_percentage_arr[$next_index_p] ) : 0;
            $prev_revenue = isset($revenue_arr[$next_index_p]) ? str_replace(',', '', $revenue_arr[$next_index_p] ) : 0;
            $prev_overhead = isset($overheads_arr[$next_index_p]) ? str_replace(',', '', $overheads_arr[$next_index_p] ) : 0;
            $prev_accrec = isset($accounts_receivable_arr[$next_index_p]) ? str_replace(',', '', $accounts_receivable_arr[$next_index_p] ) : 0;
            $prev_closest = isset($closing_stock_arr[$next_index_p]) ? str_replace(',', '', $closing_stock_arr[$next_index_p] ) : 0;
            $prev_accpay = isset($accounts_payable_arr[$next_index_p]) ? str_replace(',', '', $accounts_payable_arr[$next_index_p] ) : 0;
            $prev_cogs = isset($cogs_arr[$next_index_p]) ? str_replace(',', '', $cogs_arr[$next_index_p] ) : 0;
        }

        $gross_margin_total = ( ( (float)$current_revenue * (float)$current_gsMrg ) - ( (float)$current_revenue * (float)$prev_gsMrg ) ) * ( 1/ 12);
        $gross_margin_total = round( $gross_margin_total, 2 );
           
        $overhead_total = ( (float)$current_overhead - ( (float)$prev_overhead * ( ( (float)$prev_revenue > 0) ? (float)$current_revenue / (float)$prev_revenue : 0 ) ) ) * ( 1/ 12);
        $overhead_total = round( $overhead_total, 2 );
     
        $receivables_total  = ( (float)$current_accrec - ( (float)$prev_accrec * ( ( (float)$prev_revenue > 0) ? (float)$current_revenue / (float)$prev_revenue : 0 ) ) ) * ( 1/ 12);
        
        $receivables_total = round( $receivables_total, 2 );

        $inventory_total = ( (float)$current_closest - ( (float)$prev_closest * ( ( (float)$prev_cogs > 0) ? (float)$current_cogs / (float)$prev_cogs : 0 ) ) ) * ( 1/ 12); 
        $inventory_total = round($inventory_total,2);
        
        $payable_total = ( (float)$current_accpay - ( (float)$prev_accpay * ( ( (float)$prev_cogs > 0) ? (float)$current_cogs / (float)$prev_cogs : 0 ) ) ) * ( 1/ 12); 
        $payable_total = round($payable_total,2);
        
        $decision_total = $gross_margin_total + $overhead_total + $receivables_total + $inventory_total + $payable_total;
        $decision_total = round($decision_total,2);
        $cell_wise_item['IMPACT OF MANAGEMENT DECISIONS'][ $column_key ] = convert_decimal_format($decision_total); 
        $next_index_p = $column_key;
    }
    
    return $cell_wise_item;
  }

}