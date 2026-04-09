@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/pages/reports/comman-reports.css')}}">
@endsection

@section('content')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Balance Sheet Report</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Balance Sheet Report</li>
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
          <div class="card-header">
            <h5 class="card-title mb-0">Balance Sheet</h5>
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