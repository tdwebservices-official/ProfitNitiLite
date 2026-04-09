@extends('layouts.master')

<style>
select.assign_by {
    min-width: 200px;
}
select.assign_by+span.select2 span.selection {
    width: 100%;
}
</style>

@section('content')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">KPI Record List</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">KPI Record List</li>
  </ul>
</div>

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
              <div class="d-flex align-items-center flex-wrap gap-3">
                  <div class="mb-20">
                <label class="d-block mb-1"><strong>Assign By :</strong></label>
                <select class="assign_by form-control" name="assign_by">
                  <option value="all">All</option>
                  @foreach( $all_users as $u_key => $user_item )
                  <option value="{{$user_item->id}}" @if ( auth()->user()->id == $user_item->id ) selected @endif>{{$user_item->name}}</option>
                  @endforeach
                </select>  
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
                  <label class="d-block mb-1"><strong>Date range :</strong></label>
                  <div class='input-group pull-right ' id='date_filter'>
                    <input type='text' class="form-control bg-white" name="date_filter" readonly placeholder="Select Between Date" />
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                    </div>
                  </div>  
                </div> 
              </div>
              <div>
              
             </div>
              <div class="d-flex gap-2">
              <div class="mb-20 d-flex gap-2">
                <a href="kpi-records/quick-create" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                  <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                  Add Single KPI Record
                </a>
                <a href="kpi-records/create" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                  <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                  Add Multi KPI Record
                </a>  
              </div>
              <div class="mb-20">
                <a href="javascript:;" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 bulk-delete"> 
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                  Bulk Delete
                  <div class="hg-spinner-border spinner-border text-light" role="status"></div>
                </a>
              </div>
             </div>
            </div>
            <div class="card basic-data-table">

      <div class="card-body">
        <div class="row mt-3">
          <div class="col-md-3 text-left mb-3"><strong>Total KPI Records:</strong> ₹<span id="totalKPI">0.00</span></div>          
        </div>
       <table class="table bordered-table mb-0" id="dataTable" data-page-length="10" style="table-layout: fixed; width: 100%;">
          <thead>
            <tr>
              <th scope="col">
                <div class="form-check style-check d-flex align-items-center">
                  <input class="form-check-input all-check" type="checkbox">
                  <label class="form-check-label">
                    S.L
                  </label>
                </div>
              </th>              
              <th scope="col">Month/Year</th>
              <th scope="col">KPI Name</th>
              <th scope="col">Amount</th>                                       
              <th style="display: none;">Assign By</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            
         
          </tbody>
        </table>
      </div>
    </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="{{asset('assets/js/pages/kpi-records/list-kpi-records.js?ver='.time())}}" type="text/javascript"></script>
    @endsection