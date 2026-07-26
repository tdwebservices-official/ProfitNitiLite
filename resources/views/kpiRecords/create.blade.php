@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/pages/kpi-records/kpi-form.css?ver='.time())}}">
@endsection
@section('content')

<style>
.ui-datepicker-calendar,.ui-datepicker-current {
    display: none !important;
}
body .balance-sheet-table thead tr th, body .balance-sheet-table thead tr td,body .balance-sheet-table tbody tr th, body .balance-sheet-table tbody tr td {
    padding: 10px !important;
    font-size: 13px;
}
body .balance-sheet-table tbody tr td input.form-control,.action-box .form-select, .action-box .form-control {
   font-size: 13px !important;
    height: auto;
    padding: 6px 12px;
    line-height: normal;
    border-radius: 5px;
}
.action-box button {
    font-size: 12px !important;
    padding: 4px 12px !important;
    border-radius: 5px;
}
.csv-load label {
    font-size: 12px;
    margin-bottom: 2px;
}
body .balance-sheet-table tbody tr td input.form-control::placeholder, .action-box .form-select::placeholder, .action-box .form-control::placeholder{
font-size: 13px !important;
}

.top-action-box {
    position: sticky;
    top: 72px;
    z-index: 999;
    background: #fff;
}
</style>

   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Add KPI Record</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Add KPI Record</li>
  </ul>
</div>

        <div class="card p-0 radius-12">
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
                                <div class="row action-box top-action-box">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="d-flex align-items-center gap-2"><input readonly type="text" id="fromDate" placeholder="Form" class="form-control radius-8"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="d-flex align-items-center gap-2"><input readonly type="text" placeholder="To" id="toDate" class="form-control radius-8"></label>
                                            </div>
                                            <div class="col-md-4">                                                
                                                <button type="button" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8" id="generate-kpi">Load Table</button>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-3 save-box d-none text-end">
                                        <div class="align-items-center gap-3">
                                            <button type="button" class="save-kpi-record btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                                Save
                                                <div class="spinner-border text-white position-absolute " role="status" style="display: none;"></div>
                                            </button>
                                            <button type="button" class="reset-btn border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                                Reset
                                            </button>                                                
                                        </div>
                                    </div>
                                </div>  
                                <div class="row align-items-center d-none csv-load  action-box">
                                    <form method="post" enctype="multipart/form-data" class="load-kpi-records-form ">
                                        @csrf
                                            <div class="col-md-8 col-12 d-flex gap-3 align-items-end mb-10">
                                                <div>
                                                    <label class="form-label fw-semibold">Upload CSV</label>
                                                    <input type="file" name="csvFile" id="csvFile" accept=".csv" class="form-control">
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8" id="loadCSVFIle">Load CSV <div class="spinner-border text-white position-absolute " role="status" style="display: none;"></div></button>
                                                </div>
                                                <div>
                                                    <button type="button" id="downloadSample" class="btn btn-success">
                                                    Download Sample CSV
                                                </button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>                             
                                <form method="post" enctype="multipart/form-data" class="add-kpi-records-form d-none" id="add-kpi-records-form" data-blpl-items='{{ json_encode($kpi_bl_pl_items) }}'>
                                     @csrf
                                    <div id="kpi-tableArea" class="kpi-tableArea mt-3"></div>
                                </form>
                            </div>
        </div>
    </div>
@endsection

 @section('scripts')
<script src="{{asset('assets/js/pages/kpi-records/add-kpi-record.js?ver='.time())}}" type="text/javascript"></script>

@endsection