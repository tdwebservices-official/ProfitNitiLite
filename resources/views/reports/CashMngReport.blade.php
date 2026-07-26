@extends('layouts.master')
@section('css')
<!-- <link rel="stylesheet" href="{{asset('assets/css/pages/reports/comman-reports.css')}}"> -->
<link rel="stylesheet" href="{{asset('assets/css/pages/reports/new/cash-management.css?ver='.time())}}">

<style>

/* Layout */
.hg-wrapper {
  display: flex;
  align-items: center;
  gap: 18px;
  justify-content: space-between;
  box-sizing: border-box;
}

/* Cards */
.cardbox {
  flex: 1; /* makes cards expand evenly */
  max-width: 180px; /* keeps them from getting too wide */
  height: 130px;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

/* Number */
.cardbox .number {
  font-size: 42px;
  font-weight: 500;
  margin-bottom: 6px;
}

/* Label */
.cardbox .label {
  font-size: 12px;
  letter-spacing: 1px;
  opacity: 0.7;
}

/* Operators */
.hg-wrapper .operator {
  font-size: 28px;
  color: #555;
  font-weight: 500;
  flex: 0;
}

/* Colors (matched closer to image) */
.cardbox.blue {
  background: #eaf2ff;
  color: #2f5bd3;
}

.cardbox.light {
  background: #f3f6ff;
  color: #5b6fa8;
}

.cardbox.yellow {
  background: #fff4d9;
  color: #b67a00;
}

.cardbox.green {
  background: #e9f7ee;
  color: #2e7d4f;
}


</style>
@endsection

@section('content')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Cash Management</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Cash Management</li>
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
              <form method="post" enctype="multipart/form-data" class="cashmng-report-form" id="cashmng-report-form">
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
      <div class="card-header  d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Cash Management</h5>
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
    <div class="card mt-20">
      <div class="card-header">
        <h5 class="card-title mb-0">Cash Conversion Cycle Visual</h5>
      </div>
      <div class="card-body">

        <div class="hg-wrapper">

          <div class="cardbox blue ar-days">
            <div class="number">0</div>
            <div class="label">DSO • A/R DAYS</div>
          </div>

          <div class="operator">+</div>

          <div class="cardbox light inv-days">
            <div class="number">0</div>
            <div class="label">DIO • INV DAYS</div>
          </div>

          <div class="operator">−</div>

          <div class="cardbox yellow ap-days">
            <div class="number">0</div>
            <div class="label">DPO • A/P DAYS</div>
          </div>

          <div class="operator">=</div>

          <div class="cardbox green cc-days">
            <div class="number">0</div>
            <div class="label">CCC • TOTAL DAYS</div>
          </div>

        </div>

      </div>
    </div>

    <div class="card mt-20">
      <div class="card-header">
        <h5 class="card-title mb-0">Quick Ratio & Current Ratio gauges</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 col-12">
            <div id="quickRatio"></div>
          </div>
          <div class="col-md-6 col-12">
            <div id="currentRatio"></div>
          </div>
          
        </div>
      </div>
    </div>
    
   
    <div class="card mt-20">
      <div class="card-header">
        <h5 class="card-title mb-0">Working Capital Per ₹100 Of Revenue</h5>
      </div>
      <div class="card-body">
        <div id="columnChart5" class=""></div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/pages/report/cashmng-reports.js?ver='.time())}}" type="text/javascript"></script>
@endsection