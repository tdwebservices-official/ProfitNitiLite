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
  <h6 class="fw-semibold mb-0">Note List</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Note List</li>
  </ul>
</div>

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
              <div>
                <label class="d-block mb-1"><strong>Assign By :</strong></label>
                <select class="assign_by form-control" name="assign_by">
                  <option value="all">All</option>
                  @foreach( $all_users as $u_key => $user_item )
                  <option value="{{$user_item->id}}" @if ( auth()->user()->id == $user_item->id ) selected @endif>{{$user_item->name}}</option>
                  @endforeach
                </select>  
              </div>
             <div class="d-flex align-items-center gap-3">
                  <a href="javascript:;" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 bulk-delete"> 
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                    Bulk Delete
                    <div class="hg-spinner-border spinner-border text-light" role="status"></div>
                  </a>

              <a href="note/create" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add New Note
              </a>
            </div>
          </div>
            <div class="card basic-data-table">

      <div class="card-body">
        <table class="table bordered-table mb-0" id="dataTable" data-page-length='10' table-layout: fixed;
      width: 100%;>
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
              <th scope="col">Month</th>
              <th scope="col">Target</th>
              <th scope="col">Action Steps</th>
              <th scope="col">Person Responsible</th>
              <th scope="col">Target Date</th>
              <th scope="col">Status</th>
              <th scope="col">Remarks</th>
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
    <script src="{{asset('assets/js/pages/note/notelist.js?ver='.time())}}" type="text/javascript"></script>
    @endsection