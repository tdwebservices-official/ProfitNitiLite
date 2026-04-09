@extends('layouts.master')
@section('content')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Permission List</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Permission List</li>
  </ul>
</div>

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-end">
             
               <a href="javascript:;" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 bulk-delete"> 
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                  Bulk Delete
                  <div class="hg-spinner-border spinner-border text-light" role="status"></div>
                </a>
                <a href="permissions/create" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"> 
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Add New Permission
                </a>
            </div>
            <div class="card basic-data-table">

      <div class="card-body">
        <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
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
              <th scope="col">Name</th>                
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
    <script src="{{asset('assets/js/pages/permissions/permissionslist.js?ver='.time())}}" type="text/javascript"></script>
    @endsection