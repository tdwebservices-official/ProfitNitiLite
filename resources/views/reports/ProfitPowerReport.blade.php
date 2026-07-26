@extends('layouts.master')
@section('css')
<!-- <link rel="stylesheet" href="{{asset('assets/css/pages/reports/comman-reports.css')}}"> -->
<link rel="stylesheet" href="{{asset('assets/css/pages/reports/new/profit-power-report.css?ver='.time())}}">

<style>
.chart-card-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ecf0f1;
}
.chart-value-type{
  width: auto;
  display: inline-block;
}
</style>
@endsection

@section('content')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Profit Power</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Profit Power</li>
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
              <form method="post" enctype="multipart/form-data" class="profit-power-report-form" id="profit-power-report-form">
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
            <h5 class="card-title mb-0">Profit Power</h5>
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
            <h5 class="card-title mb-0">Revenue vs COGS trend</h5>
            <select class="form-control chart-value-type" data-chart-id="revenue-cogs-trend">
                <option value="original">Original</option>
                <option value="thousand">Thousand (K)</option>
                <option value="lakh" selected="">Lakh (L)</option>
                <option value="crore">Crore (Cr)</option>
              </select>
          </div>
          <div class="card-body">
            <div id="revenue-cogs-trend" class=""></div>
          </div>
        </div>
       
       
        <div class="card mt-20">
          <div class="card-header">
            <h5 class="card-title mb-0">Break Even Sales vs Actual Revenue</h5>
            <select class="form-control chart-value-type" data-chart-id="break-even-sales-vs-revnue">
                <option value="original">Original</option>
                <option value="thousand">Thousand (K)</option>
                <option value="lakh" selected="">Lakh (L)</option>
                <option value="crore">Crore (Cr)</option>
              </select>
          </div>
          <div class="card-body">
            <div id="break-even-sales-vs-revnue" class=""></div>
          </div>
        </div>

        <div class="card mt-20">
          <div class="card-header">
            <h5 class="card-title mb-0">Profitability Parameters</h5>
          </div>
          <div class="card-body">
            <div id="columnChart6" class=""></div>
          </div>
        </div>
        <div class="card mt-20">
          <div class="card-header">
            <h5 class="card-title mb-0">Revenue Growth vs. COGS Growth</h5>
          </div>
          <div class="card-body">
            <div id="columnChart7" class=""></div>
          </div>
        </div>
        <div class="card mt-20">
          <div class="card-header">
            <h5 class="card-title mb-0">Revenue Growth vs Overheads Growth</h5>
          </div>
          <div class="card-body">
            <div id="columnChart8" class=""></div>
          </div>
        </div>
      </div>

</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/pages/report/profit-power-reports.js?ver='.time())}}" type="text/javascript"></script>
@endsection