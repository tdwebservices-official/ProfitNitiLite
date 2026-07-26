@extends('layouts.master')
@section('css')
<style>
      .report-box .card-body span.chart-btn {
    position: absolute;
    right: 0;
    top: 0;
}

.report-box .card-body {
    position: relative;
}
.chart-btn i {
    font-size: 20px;
    color: #000;
}
</style>
<style>
@import url("https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap");
</style>
<link rel="stylesheet" href="{{asset('assets/css/pages/dashboard/dashboard.css?ver=1.6')}}">
@endsection
@section('content')
 <div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Dashboard</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Home
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Dashboard</li>
  </ul>
</div>

 <div class="card h-100 radius-12 mb-1 hg-filter-box">
    <div class="card-body">
      @if (count($errors) > 0)
              <div class="alert alert-danger">                        
                <ul>
                  <li><strong>Whoops!</strong> There were some problems with your input.</li>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif                                
              <form method="post" enctype="multipart/form-data" class="cashflow-report-form" id="cashflow-report-form">
                @csrf
                <div class="row  align-items-center">
                  <div class="col-md-4 col-sm-12">
                    <div class="">
                      <label class="d-block mb-1"><strong>Assign By :</strong></label>
                      <select class="assign_by form-control" name="assign_by">
                        <option value="all">All</option>
                        @foreach( $all_users as $u_key => $user_item )
                        <option value="{{$user_item->id}}" @if ( auth()->user()->id == $user_item->id ) selected @endif>{{$user_item->name}}</option>
                        @endforeach
                      </select>  
                    </div>
                  </div>
                  
                  <div class="col-md-4 col-sm-12">
                    <div class="">
                      <label class="d-block mb-1"><strong>Date range :</strong></label>
                      <?php
                      $currentYear = $lastYear;
                      $currentMonth = $lastMonth;
                      $startYear = $currentYear - 50;
                      $endYear = $currentYear + 50;
                      $months = [
                          1 => 'January', 2 => 'February', 3 => 'March',
                          4 => 'April',   5 => 'May',      6 => 'June',
                          7 => 'July',    8 => 'August',   9 => 'September',
                          10 => 'October',11 => 'November',12 => 'December'
                      ];
                      ?>

                      <div class="d-flex gap-3">
                          <!-- Month Dropdown -->
                        <select name="month" class="form-control month-select">
                            <?php foreach ($months as $num => $name): ?>
                                <option value="<?= $num ?>" <?php echo ($currentMonth == $num) ? "selected" : '';  ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Year Dropdown -->
                        <select name="year" class="form-control year-select">
                            <?php for ($year = $startYear; $year <= $endYear; $year++): ?>
                                <option value="<?= $year ?>" <?php echo ($currentYear == $year) ? "selected" : '';  ?>><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                        <input type="hidden" name="report_type" class="report_type" value="">
                      </div>
                    </div>   
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="d-flex align-items-center gap-3">
                      <div>
                        <button type="submit" class="btn btn-primary btn-view-report border border-primary-600 text-md px-56 py-12 radius-8"> 
                          View
                        </button>
                        <div class="spinner-border text-primary" role="status" style="display: none;"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
    </div>
  </div>
    
    <!-- Widgets start -->
    <div class="row gy-4 report-data-list">
       
    </div>
    <!-- Widgets end -->

  </div>



<div class="dashboard-main-body new-dashboard-wraper d-none">

  <!-- ============ PAGE HEADER ============ -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Business owner dashboard</h1>
      <p class="page-sub">A one-page view for the month ended <span class="filterDateSet">30-Jun-2021</span></p>
    </div>

    <select id="dashboard-figureType" class="form-control" style="width:200px;">
      <option value="1">Actual</option>
      <option value="1000" selected="">Thousands</option>
      <option value="100000">Lakhs</option>
      <option value="10000000">Crores</option>
      <option value="1000000">Millions</option>
    </select>
  </div>



  <div class="pn-board">

  <div class="pn-masthead">
    <div class="pn-brandmark">
      <div class="pn-logo">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 17l5-5 4 3 7-8"/><path d="M16 4h4v4"/>
        </svg>
      </div>
      <div class="pn-brandtext">
        <div class="pn-name">CashProfit<span>Niti</span></div>
        <div class="pn-sub">Lite Dashboard</div>
      </div>
    </div>
    <div class="pn-tagchip">Profit ≠ Cash · Know the Gap</div>
  </div>

  <div class="pn-grid">

    <!-- =================== TABLE 1 =================== -->
    <section class="pn-card">
      <div class="pn-card-head">
        <div class="pn-eyebrow">Reconciliation View</div>
        <div class="pn-card-title">Profit vs Cash Flow — All Numbers Together</div>
        <div class="pn-card-note">Where your reported profit actually went on its way to the bank.</div>
      </div>
      <div class="pn-tbl-box"> 
      <table class="pn-tbl">
        <thead>
          <tr>
            <th>Profit Line Item</th>
            <th style="text-align:right">Profit&nbsp;₹</th>
            <th>Cash Flow Line Item</th>
            <th style="text-align:right">Cash&nbsp;₹</th>
            <th style="text-align:right">Variance</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="pn-item">Revenue</span></td>
            <td style="text-align:right">
              <span class="pn-num pt-sales-amt">
                <span class="pn-rupee">₹</span>66,12,000
              </span>
            </td>
            <td><span class="pn-item">Cash from Customers</span></td>            
            <td style="text-align:right">
              <span class="pn-num pt-cash-cust-amt">
                <span class="pn-rupee">₹</span>63,69,000
              </span>
            </td>
            <!-- Revenue Variance -->
            <td style="text-align:right">
              <span class="pn-var pt-revenue-diff">−2,43,000</span>
            </td>
          </tr>
          <tr>
            <td><span class="pn-item">COGS</span><span class="pn-formula">Cost of Goods Sold</span></td>
            <td style="text-align:right"><span class="pn-num pt-cogs-amt"><span class="pn-rupee">₹</span>46,94,500</span></td>
            <td><span class="pn-item">Cash to Suppliers</span></td>
            <td style="text-align:right"><span class="pn-num pt-cash-sup-amt"><span class="pn-rupee">₹</span>49,04,500</span></td>
            <td style="text-align:right"><span class="pn-var pt-cogs-diff">−2,10,000</span></td>
          </tr>
          <tr class="pn-sub">
            <td><span class="pn-item">Gross Margin</span></td>
            <td style="text-align:right"><span class="pn-num pt-gross-amt"><span class="pn-rupee">₹</span>19,17,500</span></td>
            <td><span class="pn-item">Gross Cash Profit</span></td>
            <td style="text-align:right"><span class="pn-num pt-gross-cash-amt"><span class="pn-rupee">₹</span>14,64,500</span></td>
            <td style="text-align:right"><span class="pn-var pt-gross-diff">−4,53,000</span></td>
          </tr>
          <tr>
            <td><span class="pn-item">Overheads excl. Depreciation</span></td>
            <td style="text-align:right"><span class="pn-num pt-overheads-amt"><span class="pn-rupee">₹</span>11,16,200</span></td>
            <td><span class="pn-item">Cash Overheads excl. Dep.</span></td>
            <td style="text-align:right"><span class="pn-num pt-overheads-amt"><span class="pn-rupee">₹</span>11,16,200</span></td>
            <td style="text-align:right"><span class="pn-var nil">NIL</span></td>
          </tr>
          <tr class="pn-final">
            <td>
              <span class="pn-item">Operating Cash Profit</span>
            </td>
            <td style="text-align:right"><span class="pn-num pt-operating-cash-profit-amt"><span class="pn-rupee">₹</span>8,01,300</span></td>
            <td>
              <span class="pn-item">Operating Cash Flow</span>
            </td>
            <td style="text-align:right"><span class="pn-num pt-operating-cash-flow-amt"><span class="pn-rupee">₹</span>3,48,300</span></td>
            <td style="text-align:right"><span class="pn-var pt-opt-cash-diff">−4,53,000</span></td>
            
          </tr>
        </tbody>
      </table>
      </div>
    </section>

    <!-- =================== TABLE 2 =================== -->
    <section class="pn-card">
      <div class="pn-card-head">
        <div class="pn-card-title">The Business Generated Cash</div>
        <div class="pn-card-note">Every lever that puts cash in — or pulls cash out.</div>
      </div>
      <table class="pn-tbl flow">
        <thead>
          <tr>
            <th>Line Item</th>
            <th class="col-pos">Cash Flow (+)</th>
            <th class="col-neg">Cash Flow (−)</th>
          </tr>
        </thead>
        <tbody>
          <tr class="profit-loss-tr">
            <td><span class="pn-item">Operating Cash Profit</td>
            <td class="cell-pos"><span class="pn-cond"><span class="tag up">NET PROFIT</span><br><span class="profit-loss-plus">place in the (+) column</span></span></td>
            <td class="cell-neg"><span class="pn-cond"><span class="tag down">NET LOSS</span><br><span class="profit-loss-minus">place in the (−) column</span></span></td>
          </tr>
          <tr class="working-capital-tr">
            <td><span class="pn-item">Working Capital Change</span></td>
            <td class="cell-pos"><span class="pn-cond"><span class="tag up">WC ↓</span><br><span class="working-capital-plus">cycle compressed → cash released</span></span></td>
            <td class="cell-neg"><span class="pn-cond"><span class="tag down">WC ↑</span><br><span class="working-capital-minus">more cash trapped in cycle</span></span></td>
          </tr>
          <tr class="other-capital-tr">
            <td><span class="pn-item">Other Capital Change</span></td>
            <td class="cell-pos"><span class="pn-cond"><span class="tag up">OC ↓</span><br><span class="other-capital-plus">assets reduced or sold → cash released</span></span></td>
            <td class="cell-neg"><span class="pn-cond"><span class="tag down">OC ↑</span><br><span class="other-capital-minus">new assets purchased → cash used</span></span></td>
          </tr>
          <tr class="capital-withdrawn-tr">
            <td><span class="pn-item">Capital Withdrawn</span><span class="pn-formula">dividends</span></td>
            <td class="cell-pos"><span class="pn-dash">—</span></td>
            <td class="cell-neg"><span class="pn-cond capital-withdrawn-minus">Always a use — owner takes cash out</span></td>
          </tr>
          <tr class="pn-final final-business-cash">
            <td><span class="pn-item">Business Generated Cash</span></td>
            <td class="cell-pos"><span class="pn-cond final-business-cash-a"><b>Total (+)</b> column → <span style="color:var(--pn-pos);font-weight:600">A</span></span></td>
            <td class="cell-neg"><span class="pn-cond final-business-cash-b"><b>Total (−)</b> column → <span style="color:var(--pn-neg);font-weight:600">B</span></span></td>
          </tr>
        </tbody>
      </table>
      <div class="pn-result" id="pnNetCash" data-value="215000">
        <span class="pn-result-label">Net Cash the Business Generated</span>
        <span class="pn-result-num pnNetCash"></span>
      </div>
      <div class="pn-foot">Green = cash surplus generated · Red = cash deficit, before financing.</div>
    </section>

  </div>
</div>

  <!-- ============ SECTION 2: HEADLINE KPIs ============ -->
  <div class="section">
    <h2 class="section-title">
      <span class="section-title-tag"></span>
      Headline KPIs — at-a-glance performance
    </h2>
    <div class="kpi-strip">
      <div class="kpi-card hero">
        <div class="kpi-label">Business health score</div>
        <div class="kpi-value num"><span class="bs-score">77</span><span class="kpi-unit">/100</span></div>
        <div class="days-bar bs-dayscore"><div class="days-bar-fill" style="width: 47%; background: var(--success);"></div></div>
      </div>
      <div class="kpi-card pt-revenue-amt-box">
        <div class="kpi-label">Revenue</div>
        <div class="kpi-value num">₹3.65<span class="kpi-unit">Cr</span></div>
        <div class="kpi-sub">Monthly sales</div>
        <span class="kpi-trend up">↑ 12.4% MoM</span>
      </div>
      <div class="kpi-card pt-grossmrg-amt-box">
        <div class="kpi-label">Gross margin %</div>
        <div class="kpi-value num">30.3<span class="kpi-unit">%</span></div>
        <div class="kpi-sub">Gross margin ÷ revenue</div>
        <span class="kpi-trend up">↑ 1.8 pts</span>
      </div>
      <div class="kpi-card pt-netmrg-amt-box">
        <div class="kpi-label">Net margin %</div>
        <div class="kpi-value num">11.0<span class="kpi-unit">%</span></div>
        <div class="kpi-sub">Net profit ÷ revenue</div>
        <span class="kpi-trend down">↓ 0.4 pts</span>
      </div>
      <div class="kpi-card pt-operating-amt-box">
        <div class="kpi-label">Operating cash flow</div>
        <div class="kpi-value num">₹2.69<span class="kpi-unit">Cr</span></div>
        <div class="kpi-sub">Cash from operations</div>
        <span class="kpi-trend up">↑ 8.1% MoM</span>
      </div>
    </div>
  </div>

  <!-- ============ SECTION 3: WORKING CAPITAL ============ -->
  <div class="section">
    <h2 class="section-title">
      <span class="section-title-tag"></span>
      Working capital — money tied up in the business
    </h2>
    <div class="wc-grid">

      <div class="wc-card acc-rec-box">
        <div class="wc-card-label">Accounts receivable</div>
        <div class="wc-balance num">₹41.73 Cr</div>
        <div class="wc-row">
          <span class="lbl">Prev. Month</span>
          <span class="val num pos">−₹2.10 Cr</span>
        </div>
        <div class="days-label">
          <span class="days-value num">70</span>
          <span class="days-text">A/R days</span>
        </div>
      </div>

      <div class="wc-card acc-ap-box">
        <div class="wc-card-label">Accounts payable</div>
        <div class="wc-balance num">₹61.44 Cr</div>
        <div class="wc-row">
          <span class="lbl">Prev. Month</span>
          <span class="val num neg">+₹1.46 Cr</span>
        </div>
        <div class="days-label">
          <span class="days-value num" style="color: var(--warning);">265</span>
          <span class="days-text">A/P days</span>
        </div>
      </div>

      <div class="wc-card  inventory-box">
        <div class="wc-card-label">Inventory</div>
        <div class="wc-balance num">₹33.72 Cr</div>
        <div class="wc-row">
          <span class="lbl">Prev. Month</span>
          <span class="val num neg">+₹1.12 Cr</span>
        </div>
        <div class="days-label">
          <span class="days-value num" style="color: var(--warning);">148</span>
          <span class="days-text">Inventory days</span>
        </div>
        
      </div>

      <div class="wc-card net-working-cap-box">
        <div class="wc-card-label">Net working capital</div>
        <div class="wc-balance num">₹14.00 Cr</div>
        <div class="wc-row">
          <span class="lbl">Prev. Month</span>
          <span class="val num neg">−₹2.44 Cr</span>
        </div>
        <div class="days-label">
          <span class="days-value num" style="color: var(--success);">−47</span>
          <span class="days-text">W/C days</span>
        </div>
      </div>

       
    </div>
  </div>

  <!-- ============ SECTION 4: KEY RATIOS ============ -->
  <div class="section">
    <h2 class="section-title">
      <span class="section-title-tag"></span>
      Key financial ratios — is the business financially healthy?
    </h2>
    <div class="ratio-grid">

      <div class="ratio-card curr-ratio-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Current ratio</span>
        </div>
        <div class="ratio-value watch num">1.19<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card quick-ratio-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Quick ratio</span>
        </div>
        <div class="ratio-value watch num">0.79<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card debt-equity-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Debt / equity</span>
        </div>
        <div class="ratio-value concern num">1.93<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card interest-coverage-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Interest coverage</span>
        </div>
        <div class="ratio-value healthy num">12.7<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card return-equity-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Return on equity</span>
        </div>
        <div class="ratio-value healthy num">108.8<span class="kpi-unit">%</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>
    </div>
  
  </div>
  <div id="dashboard-container">
    <div class="db-loading">
      <div class="spinner"></div>
      Loading dashboard...
    </div>
  </div>

</div>


@endsection

@section('scripts')

<script src="{{asset('assets/js/pages/report/dashboard-reports.js?ver='.time())}}" type="text/javascript"></script>

@endsection