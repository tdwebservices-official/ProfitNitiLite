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

 <div class="card h-100 p-0 radius-12 mb-5">
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
              <form method="post" enctype="multipart/form-data" class="cashflow-report-form" id="cashflow-report-form">
                @csrf
                <div class="row  align-items-center">
                  <div class="col-md-4 col-sm-12">
                    <div class="mb-20">
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
                    <div class="mb-20">
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
        </div>

      </div>
    </div>
  </div>
    
    <!-- Widgets start -->
    <div class="row gy-4 report-data-list">
       
    </div>
    <!-- Widgets end -->

  </div>

@endsection

@section('scripts')

<script src="{{asset('assets/js/pages/report/dashboard-reports.js?ver='.time())}}" type="text/javascript"></script>

@endsection