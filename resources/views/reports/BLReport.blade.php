@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/pages/reports/comman-reports.css')}}">
<style>
.balance-sheet-table tbody tr:nth-child(19) td b,.balance-sheet-table tbody tr:last-child td b {
    color: #893333;
}
.balance-sheet-table tbody tr td b{
  color: #4e428f;
}
/* ============================================================
   Balance Sheet Report — Premium Indian Business Theme
   Drop-in stylesheet. No HTML changes required.
   Palette: Deep Indigo + Saffron + Emerald + Gold accents
   ============================================================ */

:root {
  --bs-bg: #f6f4ee;
  --bs-surface: #ffffff;
  --bs-ink: #14223d;
  --bs-ink-soft: #4b5775;
  --bs-muted: #8893ad;
  --bs-line: #e7e2d3;

  --bs-primary: #1a2a56;          /* deep indigo */
  --bs-primary-600: #233a78;
  --bs-saffron: #ff8a1f;          /* saffron */
  --bs-saffron-soft: #fff3e2;
  --bs-emerald: #0f8a5f;          /* emerald */
  --bs-emerald-soft: #e6f4ee;
  --bs-gold: #c79a3a;
  --bs-danger: #c0392b;

  --bs-grad-header: linear-gradient(135deg, #1a2a56 0%, #233a78 55%, #2f4ea0 100%);
  --bs-grad-saffron: linear-gradient(135deg, #ff9a3c 0%, #ff7a18 100%);
  --bs-grad-gold: linear-gradient(135deg, #e9c46a 0%, #c79a3a 100%);

  --bs-shadow-sm: 0 1px 2px rgba(20, 34, 61, .06), 0 1px 1px rgba(20, 34, 61, .04);
  --bs-shadow: 0 10px 30px -12px rgba(20, 34, 61, .18), 0 4px 10px -6px rgba(20, 34, 61, .08);
  --bs-radius: 16px;
}

/* ---------- Page shell ---------- */
.dashboard-main-body {
  background:
    radial-gradient(1200px 400px at -10% -10%, rgba(255, 138, 31, .08), transparent 60%),
    radial-gradient(1000px 360px at 110% 0%, rgba(26, 42, 86, .08), transparent 55%),
    var(--bs-bg);
  padding: 28px clamp(16px, 3vw, 36px);
  color: var(--bs-ink);
  font-family: "Inter", "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
  min-height: 100vh;
}

/* ---------- Page header / breadcrumb ---------- */
.dashboard-main-body > .d-flex.mb-24 {
  background: var(--bs-surface);
  border: 1px solid var(--bs-line);
  border-radius: var(--bs-radius);
  padding: 18px 22px;
  box-shadow: var(--bs-shadow-sm);
  position: relative;
  overflow: hidden;
}
.dashboard-main-body > .d-flex.mb-24::before {
  content: "";
  position: absolute; inset: 0 auto 0 0; width: 6px;
  background: var(--bs-grad-saffron);
}
.dashboard-main-body h6.fw-semibold {
  font-size: 20px; font-weight: 700; letter-spacing: -.01em;
  color: var(--bs-primary);
  display: flex; align-items: center; gap: 10px;
}
.dashboard-main-body h6.fw-semibold::before {
  content: "₹"; display: inline-grid; place-items: center;
  width: 30px; height: 30px; border-radius: 9px;
  background: var(--bs-grad-header); color: #fff;
  font-weight: 800; font-size: 14px;
  box-shadow: 0 6px 16px -8px rgba(26,42,86,.6);
}
.dashboard-main-body ul.d-flex {
  list-style: none; margin: 0; padding: 0;
  font-size: 13px; color: var(--bs-ink-soft);
}
.dashboard-main-body ul.d-flex a {
  color: var(--bs-ink-soft); text-decoration: none;
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 10px; border-radius: 8px; transition: .2s;
}
.dashboard-main-body ul.d-flex a:hover { background: var(--bs-saffron-soft); color: var(--bs-primary); }
.dashboard-main-body ul.d-flex li:last-child { color: var(--bs-primary); font-weight: 600; }

/* ---------- Cards ---------- */
.dashboard-main-body .card {
  background: var(--bs-surface);
  border: 1px solid var(--bs-line);
  border-radius: var(--bs-radius);
  box-shadow: var(--bs-shadow);
  overflow: hidden;
}
.dashboard-main-body .card .card { box-shadow: none; border-radius: 12px; }
.dashboard-main-body .card-body { padding: 22px; }

/* ---------- Filter form ---------- */
.bl-report-form label strong {
  font-size: 12px; font-weight: 600; letter-spacing: .04em;
  text-transform: uppercase; color: var(--bs-ink-soft);
}
.bl-report-form .form-control,
.bl-report-form .select2-selection {
  height: 44px !important;
  border-radius: 10px !important;
  border: 1px solid var(--bs-line) !important;
  background: #fbfaf6 !important;
  color: var(--bs-ink) !important;
  font-size: 14px !important;
  padding: 0 14px !important;
  box-shadow: none !important;
  transition: border-color .2s, box-shadow .2s, background .2s;
}
.bl-report-form .form-control:focus,
.bl-report-form .select2-selection:focus {
  border-color: var(--bs-primary-600) !important;
  background: #fff !important;
  box-shadow: 0 0 0 4px rgba(35, 58, 120, .12) !important;
  outline: none;
}
.bl-report-form .select2-selection__rendered {
  line-height: 42px !important; color: var(--bs-ink) !important; padding-left: 0 !important;
}
.bl-report-form .select2-selection__arrow { height: 42px !important; }

/* Buttons */
.bl-report-form .btn-view-report,
.bl-report-form .btn-download-report {
  height: 44px; padding: 0 28px !important;
  border-radius: 10px !important;
  font-weight: 600; font-size: 14px; letter-spacing: .01em;
  border: 0 !important; transition: transform .15s, box-shadow .2s, filter .2s;
}
.bl-report-form .btn-view-report {
  background: var(--bs-grad-header) !important; color: #fff !important;
  box-shadow: 0 10px 22px -10px rgba(26,42,86,.55);
}
.bl-report-form .btn-download-report {
  background: var(--bs-grad-saffron) !important; color: #fff !important;
  box-shadow: 0 10px 22px -10px rgba(255,122,24,.55);
}
.bl-report-form .btn-view-report:hover,
.bl-report-form .btn-download-report:hover { transform: translateY(-1px); filter: brightness(1.05); }
.bl-report-form .btn-view-report::before {
  content: "▸ "; opacity: .9; margin-right: 4px;
}
.bl-report-form .btn-download-report::before {
  content: "↓ "; font-weight: 800; margin-right: 4px;
}

/* ---------- Balance sheet card header ---------- */
.balance-sheet-table-box .card-header {
  background: var(--bs-grad-header);
  color: #fff; padding: 18px 22px; border: 0;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
  position: relative; overflow: hidden;
}
.balance-sheet-table-box .card-header::after {
  content: ""; position: absolute; right: -40px; top: -40px;
  width: 180px; height: 180px; border-radius: 50%;
  background: radial-gradient(circle, rgba(255,154,60,.35), transparent 60%);
  pointer-events: none;
}
.balance-sheet-table-box .card-title {
  color: #fff; font-size: 18px; font-weight: 700; letter-spacing: -.01em;
  display: flex; align-items: center; gap: 10px;
}
.balance-sheet-table-box .card-title::before {
  content: "📊"; font-size: 18px;
  background: rgba(255,255,255,.14); padding: 6px 9px; border-radius: 9px;
}
.balance-sheet-table-box .card-header #figureType {
  background: rgba(255,255,255,.12) !important;
  color: #fff !important;
  border: 1px solid rgba(255,255,255,.25) !important;
  border-radius: 10px !important;
  height: 40px !important;
  font-weight: 600; font-size: 13px; padding: 0 14px !important;
  cursor: pointer;
      line-height: 36px;
}
.balance-sheet-table-box .card-header #figureType option { color: var(--bs-ink); background: #fff; }

/* ---------- Table ---------- */
.balance-sheet-table { border-radius: 0 0 var(--bs-radius) var(--bs-radius); }
.balance-sheet-table table {
  width: 100%; border-collapse: separate; border-spacing: 0;
  font-size: 13px; color: var(--bs-ink); margin: 0 !important;
}
.balance-sheet-table thead th {
  position: sticky; top: 0; z-index: 2;
  background: #fbf7ec;
  color: var(--bs-primary);
  font-weight: 700; font-size: 12px;
  letter-spacing: .03em; text-transform: uppercase;
  padding: 14px 12px;
  border-bottom: 2px solid var(--bs-gold) !important;
  white-space: nowrap; text-align: right;
}
.balance-sheet-table thead th:first-child { text-align: left; }
.balance-sheet-table thead th:nth-child(n+11) {
  background: #fff3e2; color: #8a4b00;
}

.balance-sheet-table tbody td {
  padding: 12px; border-bottom: 1px solid #f1ede0 !important;
  text-align: right; font-variant-numeric: tabular-nums;
  font-feature-settings: "tnum"; white-space: nowrap;
}
.balance-sheet-table tbody td:first-child {
  text-align: left; color: var(--bs-ink-soft); font-weight: 500;
  position: sticky; left: 0; background: #fff; z-index: 1;
  border-right: 1px solid var(--bs-line) !important;
  min-width: 220px;
}

/* Zebra rows */
.balance-sheet-table tbody tr:nth-child(even) td { background: #fbfaf6; }
.balance-sheet-table tbody tr:nth-child(even) td:first-child { background: #fbfaf6; }

/* Hover */
.balance-sheet-table tbody tr:hover td { background: #fff7ea !important; }
.balance-sheet-table tbody tr:hover td:first-child { background: #fff7ea !important; color: var(--bs-primary); }

/* Quarter / YTD columns – subtle highlight */
.balance-sheet-table tbody td:nth-child(n+11) {
  background: #fffaf0;
  font-weight: 600; color: var(--bs-primary);
}
.balance-sheet-table tbody tr:nth-child(even) td:nth-child(n+11) { background: #fdf4e2; }

/* YTD column – emphasize */
.balance-sheet-table thead th:last-child,
.balance-sheet-table tbody td:last-child {
  background: var(--bs-saffron-soft) !important;
  color: var(--bs-primary);
  font-weight: 700;
  border-left: 2px solid var(--bs-saffron) !important;
}

/* Total rows — detect by <b> in first cell */
.balance-sheet-table tbody tr:has(td:first-child b) td {
  background: linear-gradient(180deg, #f1f4fc, #e9eefa) !important;
  color: var(--bs-primary) !important;
  font-weight: 700 !important;
  border-top: 1px solid #d6deef !important;
  border-bottom: 1px solid #d6deef !important;
}
.balance-sheet-table tbody tr:has(td:first-child b) td:first-child {
  color: var(--bs-primary) !important;
  text-transform: uppercase; letter-spacing: .03em; font-size: 12.5px;
}
.balance-sheet-table tbody tr:has(td:first-child b) td:last-child {
  background: var(--bs-grad-gold) !important;
  color: #2a1c00 !important;
  font-weight: 800 !important;
}

/* Grand totals (Total Liabilites, Total Assets, Total Equity and Liabilities) — strongest emphasis */
.balance-sheet-table tbody tr:has(td:first-child b):has(td:first-child:is(:nth-last-child(1)))   td { /* fallback */ }

/* ₹ prefix on numeric cells with content */
.balance-sheet-table tbody td[data-original]:not([data-original="0"])::before {
  content: "₹ ";
  color: var(--bs-muted);
  font-weight: 500;
  margin-right: 2px;
}
/* Zero values – mute */
.balance-sheet-table tbody td[data-original="0"] {
  color: #c4cad8 !important;
}

/* Scrollbar polish */
.balance-sheet-table::-webkit-scrollbar { height: 10px; }
.balance-sheet-table::-webkit-scrollbar-thumb {
  background: linear-gradient(90deg, var(--bs-primary), var(--bs-saffron));
  border-radius: 8px;
}
.balance-sheet-table::-webkit-scrollbar-track { background: #efeadb; border-radius: 8px; }

/* Responsive tweaks */
@media (max-width: 992px) {
  .dashboard-main-body { padding: 18px; }
  .balance-sheet-table-box .card-header { flex-wrap: wrap; }
  .balance-sheet-table tbody td:first-child { min-width: 180px; }
}
</style>

@endsection

@section('content')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Balance Sheet</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Balance Sheet</li>
    </ul>
  </div>

  <div class="card h-100 p-0 radius-12">
    <div class="card-body p-0">
      <div class="row justify-content-center">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
          <div class="card border">
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
              <form method="post" enctype="multipart/form-data" class="bl-report-form" id="bl-report-form">
                @csrf
                @include('reports.comman-filer', ['all_users' => $all_users])
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="col-lg-12 mt-20 balance-sheet-table-box d-none">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between" >
            <h5 class="card-title mb-0">Balance Sheet</h5>
            <select id="figureType" class="form-control" style="width:200px;">
              <option value="1">Actual</option>
              <option value="1000" selected>Thousands</option>
              <option value="100000">Lakhs</option>
              <option value="10000000">Crores</option>
              <option value="1000000">Millions</option>
            </select>
          </div>
          <div class="card-body">
            <div class="table-responsive balance-sheet-table">
           
            </div>
          </div>
        </div><!-- card end -->
      </div>


</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/pages/report/bl-reports.js?ver='.time())}}" type="text/javascript"></script>
@endsection