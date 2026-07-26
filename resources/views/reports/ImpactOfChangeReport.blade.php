@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/pages/reports/comman-reports.css')}}">
  <script src="https://cdn.tailwindcss.com"></script>
    
<style>
    .icon {
            width: 1em;
            height: 1em;
            display: inline-block;
            vertical-align: middle;
        }
        .icon-lg {
            width: 2rem;
            height: 2rem;
        }
        .icon-md {
            width: 1.25rem;
            height: 1.25rem;
        }
        .icon-sm {
            width: 1rem;
            height: 1rem;
        }
        .icon-xs {
            width: 0.75rem;
            height: 0.75rem;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        main.dashboard-main * {
    font-family: Inter, sans-serif !important;
}


/* === Unified Premium Fintech Theme — Indian Business Reports Suite === */
@import url("https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap");

/* =====================================================================
   Impact of Change Report — Premium Stylesheet
   Part of the unified Reports Design System for Indian Business Owners
   Palette: Deep Indigo + Saffron + Emerald + Gold accents
   No HTML tags or class names are added/removed — visual treatment only.
   ===================================================================== */

/* ---------- Design Tokens (shared across all reports) ---------- */
:root {
  --pl-bg: #f4f6fb;
  --pl-bg-soft: #fbfcfe;
  --pl-surface: #ffffff;

  --pl-ink: #0c1628;
  --pl-ink-soft: #43526e;
  --pl-muted: #8a93a6;
  --pl-border: #e4e9f2;
  --pl-border-strong: #cdd5e3;

  --pl-primary: #0b2a6b;
  --pl-primary-600: #1741a3;
  --pl-primary-50: #e8eefb;

  --pl-saffron: #ef7a1b;
  --pl-saffron-soft: #fdeedb;
  --pl-gold: #b9883a;
  --pl-gold-soft: #f4e7c8;

  --pl-emerald: #0b7a55;
  --pl-emerald-soft: #dcf2e7;
  --pl-rose: #b6342a;
  --pl-rose-soft: #fbe1dc;

  --pl-gradient-hero: linear-gradient(125deg, #0b2a6b 0%, #1741a3 50%, #2a64d2 100%);
  --pl-gradient-card: linear-gradient(180deg, #ffffff 0%, #f7f9fd 100%);
  --pl-gradient-accent: linear-gradient(135deg, #ef7a1b 0%, #f5a256 100%);
  --pl-gradient-emerald: linear-gradient(135deg, #0b7a55 0%, #2aa57c 100%);
  --pl-gradient-purple: linear-gradient(135deg, #4b2a8a 0%, #7a4dcf 100%);

  --pl-shadow-sm: 0 1px 2px rgba(11,42,107,.06), 0 1px 3px rgba(11,42,107,.04);
  --pl-shadow-md: 0 8px 22px -10px rgba(11,42,107,.18), 0 2px 6px rgba(11,42,107,.05);
  --pl-shadow-lg: 0 24px 48px -22px rgba(11,42,107,.32);

  --pl-radius: 16px;
  --pl-radius-sm: 12px;

  --pl-font-display: "Manrope","Plus Jakarta Sans","Segoe UI",system-ui,sans-serif;
  --pl-font-body: "Inter","Segoe UI",system-ui,sans-serif;
  --pl-font-num: "JetBrains Mono","SF Mono","Roboto Mono",ui-monospace,monospace;
}

/* ---------- Base canvas ---------- */
.dashboard-main,
.dashboard-main-body {
  background:
    radial-gradient(1100px 500px at -8% -18%, rgba(239,122,27,.07), transparent 62%),
    radial-gradient(900px 600px at 108% -4%, rgba(11,42,107,.10), transparent 64%),
    var(--pl-bg);
  font-family: var(--pl-font-body);
  color: var(--pl-ink);
  padding: 24px 26px 60px;
  min-height: 100vh;
  -webkit-font-smoothing: antialiased;
}

/* ---------- Top navbar (reused across the suite) ---------- */
.navbar-header,
.dashboard-main > .d-flex:first-child {
  background: var(--pl-surface);
  border: 1px solid var(--pl-border);
  border-radius: var(--pl-radius);
  padding: 12px 18px;
  box-shadow: var(--pl-shadow-sm);
  margin-bottom: 22px;
}
.sidebar-toggle, .sidebar-mobile-toggle {
  width: 40px; height: 40px; border-radius: 12px;
  background: var(--pl-primary-50); color: var(--pl-primary);
  border: 1px solid var(--pl-border);
  display: inline-flex; align-items: center; justify-content: center;
  transition: background .18s ease, transform .18s ease;
}
.sidebar-toggle:hover, .sidebar-mobile-toggle:hover { background: #dbe5fa; transform: translateY(-1px); }

.navbar-search {
  position: relative; display: inline-flex; align-items: center;
  background: var(--pl-bg-soft); border: 1px solid var(--pl-border);
  border-radius: 999px; padding: 6px 16px 6px 40px; min-width: 280px;
  transition: border-color .18s ease, box-shadow .18s ease;
}
.navbar-search .icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--pl-muted); }
.navbar-search input { border: 0; background: transparent; outline: none; font-size: 14px; color: var(--pl-ink); width: 100%; font-family: var(--pl-font-body); }
.navbar-search:focus-within { border-color: var(--pl-primary-600); box-shadow: 0 0 0 4px rgba(23,65,163,.12); background: #fff; }

[data-theme-toggle] {
  background: var(--pl-gold-soft) !important;
  color: var(--pl-gold) !important;
  border: 1px solid var(--pl-border) !important;
  font-weight: 600;
}

img.rounded-circle { border: 2px solid var(--pl-primary-50); box-shadow: var(--pl-shadow-sm); }

.dropdown-menu, .to-top-list {
  background: var(--pl-surface); border: 1px solid var(--pl-border);
  border-radius: var(--pl-radius-sm); box-shadow: var(--pl-shadow-md);
  padding: 12px 16px;
}
.dropdown-item { color: var(--pl-ink-soft); border-radius: 8px; padding: 10px 12px; font-family: var(--pl-font-body); transition: background .15s ease, color .15s ease; }
.dropdown-item:hover { background: var(--pl-primary-50); color: var(--pl-primary) !important; }
.hover-text-danger:hover { color: var(--pl-rose) !important; }

/* ---------- Page heading + breadcrumb ---------- */
h6.fw-semibold {
  font-family: var(--pl-font-display);
  font-weight: 800; font-size: 22px; letter-spacing: -.01em;
  color: var(--pl-ink); margin-bottom: 4px;
}
ul.d-flex.align-items-center.gap-2 {
  list-style: none; padding: 0; margin: 0 0 18px;
  display: flex; gap: 8px; align-items: center;
  font-size: 13px; color: var(--pl-muted);
}
ul.d-flex.align-items-center.gap-2 a { color: var(--pl-primary-600); text-decoration: none; }
ul.d-flex.align-items-center.gap-2 a:hover { color: var(--pl-saffron); }

/* ---------- Filter form ---------- */
.impact-of-change-report-form {
  background: var(--pl-gradient-card);
  border: 1px solid var(--pl-border);
  border-radius: var(--pl-radius);
  box-shadow: var(--pl-shadow-md);
  padding: 22px 24px;
  margin-bottom: 22px;
  display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;
}
.impact-of-change-report-form label,
form label {
  font-family: var(--pl-font-display);
  font-weight: 600; font-size: 12px;
  text-transform: uppercase; letter-spacing: .06em;
  color: var(--pl-ink-soft); margin-bottom: 6px;
  display: block;
}
.form-control, .impact-of-change-report-form .select2-selection {
  border: 1px solid var(--pl-border-strong) !important;
  border-radius: 10px !important;
  background: #fff !important;
  font-family: var(--pl-font-body); color: var(--pl-ink);
  height: 42px !important; padding: 8px 14px !important;
  transition: border-color .18s ease, box-shadow .18s ease;
}
.form-control:focus, .impact-of-change-report-form .select2-selection:focus {
  border-color: var(--pl-primary-600) !important;
  box-shadow: 0 0 0 4px rgba(23,65,163,.12) !important;
  outline: none;
}
.select2-container .select2-selection--single .select2-selection__rendered {
  line-height: 26px !important; color: var(--pl-ink) !important;
}

/* Buttons */
.btn-view-report, .btn.btn-primary, button.btn-primary {
  background: var(--pl-gradient-hero) !important;
  color: #fff !important; border: 0 !important;
  border-radius: 12px !important;
  font-family: var(--pl-font-display); font-weight: 700; letter-spacing: .02em;
  padding: 12px 36px !important;
  box-shadow: 0 10px 22px -12px rgba(11,42,107,.6);
  transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
}
.btn-view-report:hover, .btn.btn-primary:hover { transform: translateY(-1px); filter: brightness(1.06); }

.btn-download-report {
  background: var(--pl-gradient-accent) !important;
  color: #fff !important; border: 0 !important;
  border-radius: 12px !important;
  font-family: var(--pl-font-display); font-weight: 700;
  padding: 12px 36px !important;
  box-shadow: 0 10px 22px -12px rgba(239,122,27,.6);
  transition: transform .18s ease, filter .18s ease;
}
.btn-download-report:hover { transform: translateY(-1px); filter: brightness(1.06); }

/* Add product / add expense buttons */
#addProductBtn, #addMultipleProductsBtn {
  background: var(--pl-gradient-hero) !important;
  color: #fff !important;
  border-radius: 10px !important;
  font-weight: 700;
  padding: 8px 14px !important;
  box-shadow: var(--pl-shadow-sm);
}
#addExpenseBtn, #addMultipleExpensesBtn {
  background: var(--pl-gradient-accent) !important;
  color: #fff !important;
  border-radius: 10px !important;
  font-weight: 700;
  padding: 8px 14px !important;
  box-shadow: var(--pl-shadow-sm);
}
#clearAllExpensesBtn {
  background: #fff !important;
  border: 1px solid var(--pl-rose) !important;
  color: var(--pl-rose) !important;
  border-radius: 10px !important;
  padding: 6px 14px !important;
  font-weight: 600;
}
#clearAllExpensesBtn:hover { background: var(--pl-rose-soft) !important; }

/* ---------- Section headings ---------- */
.dashboard-main h2, .dashboard-main h3, .dashboard-main h4,
.dashboard-main-body h2, .dashboard-main-body h3, .dashboard-main-body h4 {
  font-family: var(--pl-font-display);
  color: var(--pl-ink); letter-spacing: -.01em;
  margin: 26px 0 14px;
}
.dashboard-main h2 { font-size: 20px !important; font-weight: 800; }
.dashboard-main h3 { font-size: 17px !important; font-weight: 700; color: var(--pl-primary); }
.dashboard-main h4 { font-size: 15px !important; font-weight: 700; color: var(--pl-primary-600); }

/* ---------- Card sections ---------- */
.section-card,
.position-card,
.combined-impact-card,
.transformation-card,
.dashboard-main > div.card,
.dashboard-main > section {
  background: var(--pl-surface);
  border: 1px solid var(--pl-border);
  border-radius: var(--pl-radius);
  box-shadow: var(--pl-shadow-md);
  padding: 22px 24px;
  margin-bottom: 22px;
}

/* ---------- "Your Current Position" KPI tiles ---------- */
.current-position-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-bottom: 22px;
}
.position-tile {
  background: var(--pl-gradient-card);
  border: 1px solid var(--pl-border);
  border-radius: var(--pl-radius-sm);
  padding: 18px;
  position: relative; overflow: hidden;
  box-shadow: var(--pl-shadow-sm);
  transition: transform .2s ease, box-shadow .2s ease;
}
.position-tile:hover { transform: translateY(-2px); box-shadow: var(--pl-shadow-md); }
.position-tile::before {
  content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
  background: var(--pl-gradient-accent);
}
.position-tile .label {
  font-size: 11px; text-transform: uppercase; letter-spacing: .08em;
  color: var(--pl-muted); font-weight: 600;
}
.position-tile .value {
  font-family: var(--pl-font-display);
  font-size: 26px; font-weight: 800; color: var(--pl-ink);
  margin: 6px 0 4px; font-variant-numeric: tabular-nums;
}
.position-tile .sub { font-size: 12px; color: var(--pl-ink-soft); }

/* ---------- Tables (products, expenses, summary) ---------- */
.dashboard-main table, .dashboard-main-body table {
  width: 100%; border-collapse: separate; border-spacing: 0;
  background: var(--pl-surface);
  border-radius: var(--pl-radius-sm);
  overflow: hidden;
  box-shadow: var(--pl-shadow-sm);
  font-family: var(--pl-font-body); font-size: 13.5px;
  margin: 14px 0 22px;
}
.dashboard-main table thead th, .dashboard-main-body table thead th,
.dashboard-main thead.sticky th, .dashboard-main thead.bg-gray-100 th {
  background: var(--pl-gradient-hero) !important;
  color: #fff !important;
  font-family: var(--pl-font-display);
  font-weight: 700; font-size: 12px;
  letter-spacing: .06em; text-transform: uppercase;
  padding: 12px 14px !important; text-align: left;
  border: 0 !important;
}
.dashboard-main table tbody td, .dashboard-main-body table tbody td {
  padding: 10px 12px;
  border: 1px solid var(--pl-border) !important;
  color: var(--pl-ink); vertical-align: middle;
}
.dashboard-main table tbody tr:nth-child(even) td { background: var(--pl-bg-soft); }
.dashboard-main table tbody tr:hover td,
.dashboard-main tr.hover\:bg-gray-50:hover td { background: var(--pl-primary-50) !important; }

/* Inline inputs inside tables */
.dashboard-main table input[type="text"],
.dashboard-main table input[type="number"],
.dashboard-main table select {
  border: 1px solid var(--pl-border-strong) !important;
  border-radius: 8px !important;
  padding: 6px 10px !important;
  background: #fff !important;
  font-family: var(--pl-font-body); color: var(--pl-ink);
  font-size: 12.5px;
}
.dashboard-main table input.bg-yellow-50 { background: var(--pl-saffron-soft) !important; }
.dashboard-main table input:focus, .dashboard-main table select:focus {
  border-color: var(--pl-primary-600) !important;
  box-shadow: 0 0 0 3px rgba(23,65,163,.12) !important; outline: none;
}

/* Numeric cells right-aligned */
.font-mono, td.text-right { font-family: var(--pl-font-num); text-align: right; font-variant-numeric: tabular-nums; }

/* Highlight totals row */
.bg-green-100.font-bold td,
tr.bg-green-100 td {
  background: var(--pl-emerald-soft) !important;
  color: var(--pl-emerald) !important;
  font-weight: 700;
}

/* ---------- Status pills (Products / Expenses count badges) ---------- */
#productCount, .bg-green-100.text-green-800 {
  background: var(--pl-emerald-soft) !important;
  color: var(--pl-emerald) !important;
  border-radius: 999px;
  padding: 3px 10px;
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .06em;
}
#expenseCount, .bg-red-100.text-red-800 {
  background: var(--pl-rose-soft) !important;
  color: var(--pl-rose) !important;
  border-radius: 999px;
  padding: 3px 10px;
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .06em;
}

/* ---------- Working capital sub-panels ---------- */
.text-blue-700 { color: var(--pl-primary) !important; font-family: var(--pl-font-display); }
.text-gray-700 { color: var(--pl-ink-soft) !important; }
.text-gray-600 { color: var(--pl-muted) !important; }

/* ---------- Combined Impact Summary ---------- */
.overall-combined-impact,
.combined-impact-card {
  background: linear-gradient(135deg, #f4ecff 0%, #faf5ff 100%);
  border: 1px solid #d8c8f0;
  border-radius: var(--pl-radius);
  padding: 22px 24px;
  box-shadow: var(--pl-shadow-md);
  margin-bottom: 22px;
}
.overall-combined-impact h4,
.text-purple-800 {
  color: #4b2a8a !important;
  font-family: var(--pl-font-display);
  font-weight: 800;
}

/* ---------- Transformation Results Summary ---------- */
.transformation-card {
  background: linear-gradient(135deg, #ecfaf3 0%, #f6fffb 100%);
  border: 1px solid #b9e5cf;
}
.transformation-card h3 { color: var(--pl-emerald); }

/* Improvement deltas */
.improvement-positive, .text-green-700, .text-green-600 {
  color: var(--pl-emerald) !important;
  font-weight: 700;
  font-family: var(--pl-font-num);
}
.improvement-negative, .text-red-700, .text-red-600 {
  color: var(--pl-rose) !important;
  font-weight: 700;
  font-family: var(--pl-font-num);
}

/* ---------- Note / informational text ---------- */
.note,
.dashboard-main p em,
.text-xs.text-gray-600 {
  background: var(--pl-saffron-soft);
  border-left: 4px solid var(--pl-saffron);
  border-radius: 8px;
  padding: 10px 14px;
  color: var(--pl-ink-soft);
  font-size: 13px;
  display: block;
  margin: 10px 0 16px;
}

/* ---------- Footer ---------- */
.d-footer, footer.d-footer {
  margin-top: 30px;
  padding: 18px 22px;
  background: var(--pl-surface);
  border: 1px solid var(--pl-border);
  border-radius: var(--pl-radius);
  color: var(--pl-muted);
  font-size: 12px; text-align: center;
  box-shadow: var(--pl-shadow-sm);
}

/* ---------- Responsive ---------- */
@media (max-width: 1440px) {

.text-xl {
    font-size: 16px !important;
}
}
@media (max-width: 720px) {
  .dashboard-main, .dashboard-main-body { padding: 16px 14px 40px; }
  .navbar-search { min-width: 0; flex: 1; }
  .impact-of-change-report-form { padding: 16px; }
  .btn-view-report, .btn-download-report { width: 100%; }
}
</style>
@endsection

@section('content')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Impact Of Change</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Impact Of Change</li>
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
              <form method="post" enctype="multipart/form-data" class="impact-of-change-report-form" id="impact-of-change-report-form">
                @csrf               
                @include('reports.comman-filer', ['all_users' => $all_users])
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

      
 <div>
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden balance-sheet-table-box  d-none">
            
         

            <!-- Current Position -->
            <div class="bg-gray-100 p-6 border-b">
                <div class="flex justify-between items-center mb-4 d-none">
                    <h2 class="text-xl font-semibold text-gray-700">Your Current Position</h2>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600">Data Period:</label>
                        <input id="dataPeriod" type="number" value="1"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Total Revenue <span id="revenueLabel">(Annual)</span></label>
                        <div id="totalRevenue" class="text-xl font-bold text-blue-600">
                            0Cr
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">GP% <span id="grossProfitLabel">(Gross Margin)</span></label>
                        <div id="grossProfitPercent" class="text-xl font-bold text-green-600">
                            0.0%
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Net Cash Flow <span id="cashFlowLabel">(Annual)</span></label>
                        <div id="netCashFlow" class="text-xl font-bold text-red-600">
                            0Cr
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Operating Profit <span id="profitLabel">(Annual)</span></label>
                        <div id="operatingProfit" class="text-xl font-bold text-red-600">
                            0Cr
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Net Margin</label>
                        <div id="netMargin" class="text-xl font-bold text-gray-700">
                            0.0%
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span id="grossProfitAmount" class="font-medium">₹0</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    <span id="periodNote">Note: All financial impacts are calculated and displayed as annual figures based on your selected data period.</span>
                </div>
            </div>

            <!-- Product-wise Revenue Configuration -->
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="icon icon-md text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Product-wise Revenue Configuration
                        <span id="productCount" class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">3 Products</span>
                    </h3>
                    <div class="flex gap-2">
                        <button id="addMultipleProductsBtn" class="d-none bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add 5 Products
                        </button>
                        <button id="addProductBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Product
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="sticky top-0 bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left text-xs font-medium">Product Name</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Base Revenue <span id="productRevenueLabel">(₹ Annual)</span></th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Price Change (%)</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Volume Change (%)</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-2 flex justify-between items-center text-sm text-gray-600">
                    <span>Total Products: <span id="productCountText">0</span></span>
                    <button id="clearAllProductsBtn" class="text-red-600 hover:text-red-800 px-3 py-1 rounded border border-red-300 hover:border-red-500 transition-colors">
                        Clear All Products
                    </button>
                </div>
            </div>

            <!-- Expense-wise Cost Configuration -->
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="icon icon-md text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                        Expense-wise Cost Configuration
                        <span id="expenseCount" class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">9 Expenses</span>
                    </h3>
                    <div class="flex gap-2">
                        <button id="addMultipleExpensesBtn" class="d-none bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-orange-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add 5 Expenses
                        </button>
                        <button id="addExpenseBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Expense
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="sticky top-0 bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left text-xs font-medium">Expense Name</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Base Amount <span id="expenseAmountLabel">(₹ Annual)</span></th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Category</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Reduction (%)</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody id="expensesTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-2 flex justify-between items-center text-sm text-gray-600">
                    <span>Total Expenses: <span id="expenseCountText">0</span></span>
                    <button id="clearAllExpensesBtn" class="text-red-600 hover:text-red-800 px-3 py-1 rounded border border-red-300 hover:border-red-500 transition-colors">
                        Clear All Expenses
                    </button>
                </div>
            </div>

            <!-- Working Capital Parameters -->
            <div class="p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Working Capital Optimization
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-blue-700 mb-3">Receivables Management</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Current Receivables Days</label>
                                <input type="number" step="0.01" id="currentReceivablesDays" value="92" class="w-full p-2 border border-gray-300 rounded text-xs">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Receivables Days Reduction</label>
                                <input type="number" step="0.1" id="receivablesDaysReduction" value="10" class="w-full p-2 border border-gray-300 rounded text-xs bg-yellow-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-blue-700 mb-3">Inventory Management</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Current Inventory Days</label>
                                <input type="number" id="currentInventoryDays" step="0.01" value="95" class="w-full p-2 border border-gray-300 rounded text-xs">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Inventory Days Reduction</label>
                                <input type="number" step="0.1" id="inventoryDaysReduction" value="15" class="w-full p-2 border border-gray-300 rounded text-xs bg-yellow-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-blue-700 mb-3">Payables Management</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Current Payables Days</label>
                                <input type="number" id="currentPayableDays" step="0.01" value="30" class="w-full p-2 border border-gray-300 rounded text-xs">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Payables Days Increase</label>
                                <input type="number" step="0.1" id="payableDaysIncrease" value="15" class="w-full p-2 border border-gray-300 rounded text-xs bg-yellow-50">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Impact Summary -->
            <div class="p-6 bg-gradient-to-r from-purple-50 to-blue-50 border-b">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Overall Combined Impact Summary
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg border border-purple-200">
                        <h4 class="font-medium text-purple-700 mb-2">Revenue Impact</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Price Optimization:</span>
                                <span id="totalPriceImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Volume Growth:</span>
                                <span id="totalVolumeImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="border-t pt-1 flex justify-between font-medium">
                                <span>Total Revenue Impact:</span>
                                <span id="totalRevenueImpact" class="font-mono text-green-600">₹0</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-purple-200">
                        <h4 class="font-medium text-purple-700 mb-2">Cost Impact</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>COGS Optimization:</span>
                                <span id="totalCOGSImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Overhead Optimization:</span>
                                <span id="totalOverheadImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="border-t pt-1 flex justify-between font-medium">
                                <span>Total Cost Savings:</span>
                                <span id="totalCostImpact" class="font-mono text-green-600">₹0</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-purple-200">
                        <h4 class="font-medium text-purple-700 mb-2">Working Capital Impact</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Receivables:</span>
                                <span id="receivablesImpactSummary" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Inventory:</span>
                                <span id="inventoryImpactSummary" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Payables:</span>
                                <span id="payablesImpactSummary" class="font-mono">₹0</span>
                            </div>
                            <div class="border-t pt-1 flex justify-between font-medium">
                                <span>Total WC Impact:</span>
                                <span id="totalWCImpact" class="font-mono text-blue-600">₹0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-white rounded-lg border-2 border-purple-300">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-purple-800">Combined Overall Impact</h4>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Operating Profit Improvement</div>
                            <div id="combinedProfitImpact" class="text-2xl font-bold text-purple-700">₹0</div>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-between items-center text-sm">
                        <span class="text-gray-600">This represents the total impact when ALL changes are implemented simultaneously</span>
                        <div class="text-right">
                            <div class="text-gray-600">Cash Flow Improvement</div>
                            <div id="combinedCashFlowImpact" class="text-lg font-semibold text-blue-700">₹0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Impact of Change Analysis
                </h2>
                <div class="mb-4 text-sm text-gray-600">
                    <span id="analysisNote">All impacts below are calculated as annual figures based on your selected data period.</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-3 text-left font-semibold text-gray-700 w-1/3">Improvement Lever</th>
                                <th class="border border-gray-300 p-3 text-center font-semibold text-gray-700 w-1/4">Driver Details</th>
                                <th class="border border-gray-300 p-3 text-center font-semibold text-gray-700 w-1/5">Impact on Cash Flow <span id="analysisFlowLabel">(Annual)</span></th>
                                <th class="border border-gray-300 p-3 text-center font-semibold text-gray-700 w-1/5">Impact on Operating Profit <span id="analysisProfitLabel">(Annual)</span></th>
                            </tr>
                        </thead>
                        <tbody id="analysisTableBody">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="p-6 bg-gradient-to-r from-green-50 to-blue-50 border-t">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Transformation Results Summary
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg border border-green-200">
                        <h4 class="text-lg font-semibold text-green-700 mb-3">Financial Impact</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">New Operating Profit:</span>
                                <div id="newOperatingProfit" class="text-xl font-bold text-green-600">
                                    0Cr
                                </div>
                                <div id="operatingProfitImprovement" class="text-sm text-green-600">
                                    Improvement: 0Cr
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">New Cash Flow Position:</span>
                                <div id="newCashFlow" class="text-xl font-bold text-green-600">
                                    0Cr
                                </div>
                                <div id="cashFlowImprovement" class="text-sm text-green-600">
                                    Improvement: 0Cr
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg border border-blue-200">
                        <h4 class="text-lg font-semibold text-blue-700 mb-3">Performance Metrics</h4>
                        <div class="space-y-2 text-sm">
                            <div id="currentMargin">Current Profit Margin: 0.0%</div>
                            <div id="newMargin">New Profit Margin: 0.0%</div>
                            <div id="marginImprovement">Margin Improvement: 0.0%</div>
                            <div id="cashFlowPercent">Cash Flow Improvement: 0.0% of revenue</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script src="{{asset('assets/js/pages/report/impact-of-change-report.js?ver='.time())}}" type="text/javascript"></script>
@endsection