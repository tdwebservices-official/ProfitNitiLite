@extends('layouts.master')
@section('content')

<style>
.ui-datepicker-calendar,.ui-datepicker-current {
    display: none !important;
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
                                                           
                                <form method="post" enctype="multipart/form-data" class="add-kpi-records-form" id="add-kpi-records-form">
                                     @csrf
                                     <div class="mb-20">
                                        <label for="amount" class="form-label fw-semibold text-primary-light text-sm mb-8">Month/Year</label>
                                        <input readonly type="text" id="fromDate" name="fromDate" placeholder="Month/Year" class="form-control radius-8">
                                    </div>
                                     <div class="mb-20">
                                        <label for="primary_contact_name" class="form-label fw-semibold text-primary-light text-sm mb-8 d-block">KPI Items </label>
                                        <select class="form-control radius-8" name="type" id="type" required>
                                          <option value="0">Select KPI Items</option>
                                          @foreach( $kpi_bl_pl_items as $balance_m_key => $balance_sheet_item )
                                          <option value="{{$balance_m_key}}">{{$balance_sheet_item}}</option>                                             
                                          @endforeach
                                      </select>
                                  </div>
                                     <div class="mb-20">
                                        <label for="amount" class="form-label fw-semibold text-primary-light text-sm mb-8">Amount</label>
                                        <input type="number" class="form-control radius-8" id="amount" name="amount"  placeholder="Enter Amount" value="{{ old('amount') }}" >
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="submit" class="save-kpi-record btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                            Save
                                            <div class="spinner-border text-white position-absolute " role="status" style="display: none;"></div>
                                        </button>
                                        <button type="reset" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                            Reset
                                        </button>                                                
                                    </div>
                                </form>
                            </div>
        </div>
    </div>
@endsection

 @section('scripts')
<script src="{{asset('assets/js/pages/kpi-records/quick-create.js?ver='.time())}}" type="text/javascript"></script>

@endsection