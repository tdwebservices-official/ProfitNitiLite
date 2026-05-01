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
  :root {
    --bg: #f6f8fb;
    --surface: #ffffff;
    --surface-2: #f1f4f9;
    --border: #e3e8ef;
    --border-strong: #cbd5e1;
    --text: #0f172a;
    --text-muted: #64748b;
    --text-soft: #94a3b8;
    --primary: #2563eb;
    --primary-50: #eff6ff;
    --primary-100: #dbeafe;
    --success: #16a34a;
    --success-50: #f0fdf4;
    --success-100: #dcfce7;
    --warning: #d97706;
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --danger: #dc2626;
    --danger-50: #fef2f2;
    --danger-100: #fee2e2;
    --shadow-sm: 0 1px 2px rgba(15,23,42,0.04);
    --shadow: 0 1px 3px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04);
    --radius: 10px;
    --radius-sm: 6px;
  }

  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    
  }
  .num { font-variant-numeric: tabular-nums; }

  /* ============ TOP BAR ============ */
  .topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 14px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .topbar-left { display: flex; align-items: center; gap: 14px; }
  .logo {
    width: 36px; height: 36px; border-radius: 8px;
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 600; font-size: 14px;
  }
  .topbar-title { font-size: 15px; font-weight: 600; color: var(--text); }
  .topbar-sub { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
  .topbar-right { display: flex; gap: 8px; align-items: center; }
  .pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; font-size: 12px; font-weight: 500;
    background: var(--surface-2); border-radius: 20px; color: var(--text-muted);
    border: 1px solid var(--border);
  }
  .btn {
    padding: 7px 14px; font-size: 12px; font-weight: 500;
    border: 1px solid var(--border); background: var(--surface);
    border-radius: var(--radius-sm); cursor: pointer; color: var(--text);
    transition: all 0.15s;
  }
  .btn:hover { background: var(--surface-2); }
  .btn-primary {
    background: var(--primary); color: white; border-color: var(--primary);
  }
  .btn-primary:hover { background: #1d4ed8; }

  /* ============ MAIN LAYOUT ============ */
  .container {
    max-width: 1480px;
    margin: 0 auto;
    padding: 24px 28px 60px;
  }

  .page-header {
    margin-bottom: 20px;
  }
  .page-title {
    font-size: 22px !important; font-weight: 600; margin: 0 0 4px; color: var(--text);
  }
  .page-sub {
    font-size: 13px; color: var(--text-muted); margin: 0;
  }

  /* ============ SECTION ============ */
  .section {
    margin-bottom: 24px;
  }
  .section-title {
    font-size: 11px !important; font-weight: 600;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 0.8px; margin: 0 0 10px;
    display: flex; align-items: center; gap: 8px;
  }
  .section-title-tag {
    width: 4px; height: 14px; background: var(--primary); border-radius: 2px;
  }

  /* ============ CARD ============ */
  .pt-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow-sm);
  }
  .pt-card-title {
    font-size: 12px; font-weight: 600;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 0.5px; margin: 0 0 14px;
  }

  /* ============ PROFIT vs CASH (Section 1) ============ */
  .profit-cash-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1.4fr;
    gap: 16px;
  }
  .walk-card { padding: 18px; }
  .walk-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px dashed var(--border);
    font-size: 13px;
  }
  .walk-row:last-child { border-bottom: none; }
  .walk-row.total {
    border-top: 2px solid var(--text); border-bottom: none;
    padding-top: 12px; margin-top: 4px;
    font-weight: 600;
  }
  .walk-row .label { color: var(--text); }
  .walk-row .label.muted { color: var(--text-muted); }
  .walk-row .value { font-variant-numeric: tabular-nums; font-weight: 500; }
  .walk-row .value.neg { color: var(--danger); }
  .walk-row .value.pos { color: var(--success); }
  .walk-card-header {
    display: flex; justify-content: space-between; align-items: baseline;
    margin-bottom: 10px;
  }
  .walk-card-label {
    font-size: 12px; font-weight: 600; color: var(--text);
    text-transform: uppercase; letter-spacing: 0.5px;
  }
  .walk-tag {
    font-size: 10px; padding: 2px 8px; border-radius: 10px;
    background: var(--surface-2); color: var(--text-muted);
  }

  /* Reconciliation right card */
  .recon-table {
    width: 100%; border-collapse: collapse; font-size: 12px;
  }
  .recon-table th {
    text-align: left; padding: 8px 10px; font-weight: 600;
    color: var(--text-muted); border-bottom: 1px solid var(--border);
    font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;
  }
  .recon-table td {
    padding: 9px 10px; border-bottom: 1px solid var(--border);
    font-variant-numeric: tabular-nums;
  }
  .recon-table .pos { color: var(--success); }
  .recon-table .neg { color: var(--danger); }
  .recon-table tr.subtotal td {
    background: var(--surface-2); font-weight: 600;
    border-top: 1px solid var(--border-strong);
  }

  /* ============ INSIGHT BOX (commentary) ============ */
  .insight-box {
    background: var(--primary-50);
    border-left: 3px solid var(--primary);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    margin-top: 12px;
    position: relative;
  }
  .insight-box.warning {
    background: var(--warning-50);
    border-left-color: var(--warning);
  }
  .insight-box.danger {
    background: var(--danger-50);
    border-left-color: var(--danger);
  }
  .insight-box.success {
    background: var(--success-50);
    border-left-color: var(--success);
  }
  .insight-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 6px;
  }
  .insight-label {
    font-size: 10px; font-weight: 700; letter-spacing: 0.8px;
    text-transform: uppercase; color: var(--primary);
  }
  .insight-box.warning .insight-label { color: var(--warning); }
  .insight-box.danger .insight-label { color: var(--danger); }
  .insight-box.success .insight-label { color: var(--success); }
  .insight-text {
    font-size: 12.5px; color: var(--text); line-height: 1.55;
  }
  .insight-text[contenteditable="true"]:focus {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
    background: white;
    border-radius: 4px;
    padding: 4px;
    margin: -4px;
  }
  .insight-edit-btn {
    background: transparent; border: none; cursor: pointer;
    color: var(--text-muted); font-size: 11px;
    padding: 2px 6px; border-radius: 4px;
    transition: all 0.15s;
  }
  .insight-edit-btn:hover {
    background: rgba(0,0,0,0.05); color: var(--text);
  }
  .ai-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; padding: 2px 6px;
    background: rgba(0,0,0,0.06); border-radius: 8px;
    color: var(--text-muted); margin-left: 6px;
  }

  /* ============ KPI STRIP (Section 2) ============ */
  .kpi-strip {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
  }
  .kpi-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
    box-shadow: var(--shadow-sm);
    position: relative;
  }
  .kpi-card.hero {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    color: white;
    border-color: transparent;
  }
  .kpi-label {
    font-size: 10.5px; font-weight: 600;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 0.6px; margin-bottom: 6px;
  }
  .kpi-card.hero .kpi-label { color: rgba(255,255,255,0.85); }
  .kpi-value {
    font-size: 26px; font-weight: 600; line-height: 1.1;
    font-variant-numeric: tabular-nums; color: var(--text);
  }
  .kpi-card.hero .kpi-value { color: white; }
  .kpi-unit { font-size: 14px; font-weight: 500; color: var(--text-muted); margin-left: 2px; }
  .kpi-card.hero .kpi-unit { color: rgba(255,255,255,0.75); }
  .kpi-sub {
    font-size: 11px; color: var(--text-muted); margin-top: 4px;
  }
  .kpi-card.hero .kpi-sub { color: rgba(255,255,255,0.7); }
  .kpi-trend {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 11px; font-weight: 500;
    padding: 2px 6px; border-radius: 10px; margin-top: 6px;
  }
  .kpi-trend.up { background: var(--success-100); color: var(--success); }
  .kpi-trend.down { background: var(--danger-100); color: var(--danger); }
  .health-ring {
    position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
  }

  /* ============ WORKING CAPITAL (Section 3) ============ */
  .wc-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr) 1.3fr;
    gap: 12px;
  }
  .wc-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
  }
  .wc-card-label {
    font-size: 10.5px; font-weight: 600;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 0.6px; margin-bottom: 8px;
  }
  .wc-balance {
    font-size: 18px; font-weight: 600; font-variant-numeric: tabular-nums;
    color: var(--text);
  }
  .wc-row {
    display: flex; justify-content: space-between;
    font-size: 11.5px; margin-top: 8px;
  }
  .wc-row .lbl { color: var(--text-muted); }
  .wc-row .val { font-variant-numeric: tabular-nums; font-weight: 500; }
  .wc-row .val.neg { color: var(--danger); }
  .wc-row .val.pos { color: var(--success); }
  .days-bar {
    margin-top: 10px; height: 4px; background: var(--surface-2);
    border-radius: 2px; overflow: hidden;
  }
  .days-bar-fill {
    height: 100%; border-radius: 2px;
  }
  .days-label {
    display: flex; justify-content: space-between; align-items: baseline;
    margin-top: 8px;
  }
  .days-value {
    font-size: 22px; font-weight: 600; font-variant-numeric: tabular-nums;
  }
  .days-text { font-size: 11px; color: var(--text-muted); }

  .wc-insight {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
    display: flex; flex-direction: column;
  }

  /* ============ RATIOS (Section 4) ============ */
  .ratio-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
  }
  .ratio-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
    position: relative;
  }
  .ratio-card-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 10px;
  }
  .ratio-label {
    font-size: 10.5px; font-weight: 600;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 0.6px;
  }
  .ratio-status {
    font-size: 10px; padding: 2px 7px; border-radius: 10px;
    font-weight: 600; letter-spacing: 0.3px;
  }
  .ratio-status.healthy { background: var(--success-100); color: var(--success); }
  .ratio-status.watch { background: var(--warning-100); color: var(--warning); }
  .ratio-status.concern { background: var(--danger-100); color: var(--danger); }
  .ratio-value {
    font-size: 28px; font-weight: 600; font-variant-numeric: tabular-nums;
    line-height: 1.1; margin-bottom: 4px;
  }
  .ratio-value.healthy { color: var(--success); }
  .ratio-value.watch { color: var(--warning); }
  .ratio-value.concern { color: var(--danger); }
  .ratio-target {
    font-size: 11px; color: var(--text-muted);
  }

  /* Comments drawer */
  .drawer-toggle {
    position: fixed; right: 24px; bottom: 24px;
    width: 48px; height: 48px; border-radius: 24px;
    background: var(--primary); color: white;
    border: none; cursor: pointer;
    box-shadow: 0 4px 12px rgba(37,99,235,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; z-index: 100;
  }
  .drawer-toggle:hover { background: #1d4ed8; }
  .drawer-badge {
    position: absolute; top: -4px; right: -4px;
    background: var(--danger); color: white;
    font-size: 10px; font-weight: 600;
    width: 18px; height: 18px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
  }
  .drawer {
    position: fixed; top: 0; right: -480px;
    width: 460px; height: 100vh; background: var(--surface);
    border-left: 1px solid var(--border);
    box-shadow: -4px 0 24px rgba(0,0,0,0.08);
    transition: right 0.3s ease; z-index: 99;
    display: flex; flex-direction: column;
  }
  .drawer.open { right: 0; }
  .drawer-header {
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center;
  }
  .drawer-title { font-size: 14px; font-weight: 600; }
  .drawer-close {
    background: none; border: none; cursor: pointer;
    color: var(--text-muted); font-size: 20px;
  }
  .drawer-body {
    flex: 1; overflow-y: auto; padding: 16px 20px;
  }
  .drawer-footer {
    padding: 14px 20px; border-top: 1px solid var(--border);
    background: var(--surface-2);
  }
  .comment-list { display: flex; flex-direction: column; gap: 12px; }
  .comment {
    background: var(--surface-2); border-radius: var(--radius-sm);
    padding: 12px; font-size: 12.5px;
  }
  .comment-meta {
    display: flex; justify-content: space-between;
    margin-bottom: 6px; font-size: 11px;
  }
  .comment-author { font-weight: 600; color: var(--text); }
  .comment-time { color: var(--text-muted); }
  .comment-section {
    display: inline-block; font-size: 10px; padding: 2px 6px;
    background: var(--primary-100); color: var(--primary);
    border-radius: 8px; margin-bottom: 6px;
    font-weight: 600;
  }
  .comment-body { color: var(--text); line-height: 1.5; }
  .comment-input {
    width: 100%; border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 10px; font-size: 13px; font-family: inherit;
    resize: vertical; min-height: 70px;
  }
  .comment-input:focus { outline: none; border-color: var(--primary); }
  .comment-section-select {
    width: 100%; padding: 8px 10px; font-size: 12px;
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    background: white; margin-bottom: 8px;
  }

  /* AI banner */
  .ai-suggest {
    background: linear-gradient(135deg, #faf5ff 0%, #eff6ff 100%);
    border: 1px solid #ddd6fe;
    border-radius: var(--radius);
    padding: 14px 16px;
    margin-bottom: 16px;
    display: flex; gap: 12px; align-items: flex-start;
  }
  .ai-icon {
    width: 28px; height: 28px; border-radius: 8px;
    background: linear-gradient(135deg, #7c3aed, #2563eb);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; flex-shrink: 0;
  }
  .ai-content { flex: 1; }
  .ai-title {
    font-size: 12px; font-weight: 600; color: var(--text);
    margin-bottom: 4px;
  }
  .ai-text {
    font-size: 12px; color: var(--text-muted); line-height: 1.5;
  }
  .ai-actions {
    display: flex; gap: 6px; margin-top: 8px;
  }
  .ai-btn {
    font-size: 11px; padding: 4px 10px;
    background: white; border: 1px solid var(--border);
    border-radius: 4px; cursor: pointer; color: var(--text);
  }
  .ai-btn.primary { background: var(--primary); color: white; border-color: var(--primary); }

  .hg-filter-box {
    background: transparent;
    box-shadow: unset;
}
.cashflow-report-form button[type="submit"] {
    padding: 8px 20px !important;
    height: auto;
    min-height: unset;
}

.skeleton-loader{
  width: 100%;
  height: 15px;
  display: block;
  background: linear-gradient(
      to right,
      rgba(255, 255, 255, 0),
      rgba(255, 255, 255, 0.5) 50%,
      rgba(255, 255, 255, 0) 80%
    ),
    #ededed;
  background-repeat: repeat-y;
  background-size: 50px 500px;
  background-position: 0 0;
  animation: shine 1s infinite;
      position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;    z-index: 1;
}

@keyframes shine {
  to {
    background-position: 100% 0;
  }
}
  
  .profit-cashflow-insight, .wc-insight, .ratio-insight{

    position: relative;

  }

</style>
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

 <div class="card h-100 p-0 radius-12 mb-1 hg-filter-box">
    <div class="card-body p-0 ">
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
    <h1 class="page-title">Business owner dashboard</h1>
    <p class="page-sub">A one-page view for the month ended <span class="filterDateSet">30-Jun-2021</span></p>
  </div>

  <!-- ============ SECTION 1: PROFIT vs CASH ============ -->
  <div class="section">
    <h2 class="section-title">
      <span class="section-title-tag"></span>
      Profit vs cash flow — where did the profit go?
    </h2>
    <div class="profit-cash-grid">

      <!-- Profit Walk -->
      <div class="pt-card walk-card">
        <div class="walk-card-header">
          <span class="walk-card-label">Profit walk</span>
          <span class="walk-tag">Accrual</span>
        </div>
        <div class="walk-row">
          <span class="label">Revenue</span>
          <span class="value num pt-sales-amt">₹3,65,19,408</span>
        </div>
        <div class="walk-row">
          <span class="label muted">Less: COGS</span>
          <span class="value num neg pt-cogs-amt">(₹2,54,64,235)</span>
        </div>
        <div class="walk-row">
          <span class="label" style="font-weight: 600;">Gross margin</span>
          <span class="value num pt-gross-amt">₹1,10,55,174</span>
        </div>
        <div class="walk-row">
          <span class="label muted">Less: Overheads & dep.</span>
          <span class="value num neg pt-overheads-amt">(₹85,91,533)</span>
        </div>
        <div class="walk-row total">
          <span class="label">Operating cash profit</span>
          <span class="value num pt-operating-cash-profit-amt" style="color: var(--primary);">₹24,63,641</span>
        </div>
      </div>

      <!-- Cash Walk -->
      <div class="pt-card walk-card">
        <div class="walk-card-header">
          <span class="walk-card-label">Cash walk</span>
          <span class="walk-tag">Cash basis</span>
        </div>
        <div class="walk-row">
          <span class="label">Cash from customer</span>
          <span class="value num pt-cash-cust-amt">₹5,75,52,465</span>
        </div>
        <div class="walk-row">
          <span class="label muted">Less: Cash to supplier</span>
          <span class="value num neg pt-cash-sup-amt">(₹1,95,85,931)</span>
        </div>
        <div class="walk-row">
          <span class="label" style="font-weight: 600;">Gross cash profit</span>
          <span class="value num pt-gross-cash-amt">₹3,79,66,534</span>
        </div>
        <div class="walk-row">
          <span class="label muted">Less: Overheads & dep.</span>
          <span class="value num neg pt-overheads-amt">(₹85,91,533)</span>
        </div>
        <div class="walk-row total">
          <span class="label">Operating cash flow</span>
          <span class="value num pt-operating-cash-flow-amt" style="color: var(--success);">₹2,68,63,333</span>
        </div>
      </div>

      <!-- Reconciliation -->
      <div class="pt-card walk-card">
        <div class="walk-card-header">
          <span class="walk-card-label">Profit → cash reconciliation</span>
          <span class="walk-tag">Bridge</span>
        </div>
        <table class="recon-table">
          <thead>
            <tr><th>Line item</th><th style="text-align: right;">Cash (+)</th><th style="text-align: right;">Cash (−)</th></tr>
          </thead>
          <tbody>
            <tr>
              <td>Profit</td>
              <td class="num pt-profit-amt" style="text-align: right;" >40,15,030</td>
              <td style="text-align: right;">—</td>
            </tr>
            <tr>
              <td>Working capital</td>
              <td style="text-align: right;">—</td>
              <td class="num neg pt-working-cap-amt" style="text-align: right;">(2,43,99,692)</td>
            </tr>
            <tr>
              <td>Other capital</td>
              <td class="num pos pt-other-cap-amt" style="text-align: right;">1,36,14,685</td>
              <td style="text-align: right;">—</td>
            </tr>
            <tr class="subtotal">
              <td>Sub-total (A)</td>
              <td class="num pos pt-sub-a-amt" style="text-align: right;">1,76,29,714</td>
              <td class="num neg pt-sub-b-amt" style="text-align: right;">(2,43,99,692)</td>
            </tr>
            <tr>
              <td>Surplus (A − B)</td>
              <td colspan="2" class="num neg pt-surplus-amt" style="text-align: right; font-weight: 600;">(67,69,978)</td>
            </tr>
            <tr>
              <td>Capital withdrawn</td>
              <td style="text-align: right;">—</td>
              <td class="num neg pt-cap-wth-amt" style="text-align: right;">(9,82,686)</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Insight (dynamic commentary) -->
    <div class="insight-box profit-cashflow-insight warning " data-section="profit-cash">
      <span class="skeleton-loader"></span>
      <div class="insight-header">
        <span class="insight-label">Key insight <span class="ai-badge">✨ AI generated</span></span>
      </div>
      <div class="insight-text" data-default="Operating cash flow (₹2.69 Cr) is HIGHER than operating cash profit (₹24.6 L) — driven by strong collections this month. However, working capital absorbed ₹2.44 Cr, and a capital surplus of ₹1.36 Cr from other capital sources partly offset it. Net result: business generated approximately (₹77.5 L) of negative cash this month — the profit shown is NOT fully converting into cash.">Operating cash flow (₹2.69 Cr) is HIGHER than operating cash profit (₹24.6 L) — driven by strong collections this month. However, working capital absorbed ₹2.44 Cr, and a capital surplus of ₹1.36 Cr from other capital sources partly offset it. Net result: business generated approximately (₹77.5 L) of negative cash this month — the profit shown is NOT fully converting into cash.</div>
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
        <div class="kpi-sub">Composite of 5 key ratios</div>
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
          <span class="lbl">Change this period</span>
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
          <span class="lbl">Change this period</span>
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
          <span class="lbl">Change this period</span>
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
          <span class="lbl">Change this period</span>
          <span class="val num neg">−₹2.44 Cr</span>
        </div>
        <div class="days-label">
          <span class="days-value num" style="color: var(--success);">−47</span>
          <span class="days-text">W/C days</span>
        </div>
      </div>

       <div class="wc-insight">
        <span class="skeleton-loader"></span>
        <div class="insight-box warning " data-section="working-capital" style="margin: 0; height: 100%; display: flex; flex-direction: column; justify-content: center;">
          <div class="insight-header">
            <span class="insight-label">Key insight <span class="ai-badge">✨ AI generated</span></span>
          </div>
          <div class="insight-text">A/P days (265) are much higher than A/R days (70) — suppliers are funding the business. Inventory of 148 days is on the heavier side.</div>
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
          <span class="ratio-status watch">Watch</span>
        </div>
        <div class="ratio-value watch num">1.19<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card quick-ratio-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Quick ratio</span>
          <span class="ratio-status watch">Watch</span>
        </div>
        <div class="ratio-value watch num">0.79<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card debt-equity-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Debt / equity</span>
          <span class="ratio-status concern">Concern</span>
        </div>
        <div class="ratio-value concern num">1.93<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card interest-coverage-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Interest coverage</span>
          <span class="ratio-status healthy">Healthy</span>
        </div>
        <div class="ratio-value healthy num">12.7<span class="kpi-unit">x</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>

      <div class="ratio-card return-equity-box">
        <div class="ratio-card-header">
          <span class="ratio-label">Return on equity</span>
          <span class="ratio-status healthy">Healthy</span>
        </div>
        <div class="ratio-value healthy num">108.8<span class="kpi-unit">%</span></div>
        <div class="ratio-target">Prev Month : <span>0.00</span></div>
      </div>
    </div>
    <!-- Overall ratios commentary -->
    <div class="insight-box ratio-insight" data-section="ratios">
      <span class="skeleton-loader"></span>
      <div class="insight-header">
        <span class="insight-label">Overall financial position <span class="ai-badge">✨ AI generated</span></span>
      </div>
      <div class="insight-text">Profitability metrics (ROE 108.8%, Interest coverage 12.7x) are exceptional, but liquidity and leverage need attention. Debt-to-equity at 1.93x is the most pressing concern — consider deleveraging or raising equity. Quick ratio below 1.0x means without selling inventory, the business cannot cover short-term obligations.</div>
    </div>
    
  </div>

</div>


@endsection

@section('scripts')

<script src="{{asset('assets/js/pages/report/dashboard-reports.js?ver='.time())}}" type="text/javascript"></script>

@endsection