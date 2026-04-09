@extends('layouts.master')
@section('content')
   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit Password</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Edit Password</li>
  </ul>
</div>

        <div class="card h-100 p-0 radius-12">
            <div class="card-body p-24">
                <div class="row justify-content-center">
                    <div class="col-xxl-6 col-xl-8 col-lg-10">
                        <div class="card border">
                            <div class="card-body">
                            	@if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        <li><strong>Whoops!</strong> There were some problems with your input.</li>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif                               
                                <form method="post" enctype="multipart/form-data" action="{{ route('user.update-change-password') }}">
                                	 @csrf
                                    <div class="mb-20">
                                        <label for="old_password" class="form-label fw-semibold text-primary-light text-sm mb-8">Old Password <span class="text-danger-600">*</span></label>
                                        <input type="password" class="form-control radius-8" id="old_password" name="old_password" required placeholder="Enter Old Password" >
                                    </div>
                                    <div class="mb-20">
                                        <label for="new_password" class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span class="text-danger-600">*</span></label>
                                        <input type="password" class="form-control radius-8" id="new_password" name="new_password" required placeholder="Enter New Password">
                                    </div>
                                    <div class="mb-20">
                                        <label for="confirm_password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm Password <span class="text-danger-600">*</span></label>
                                        <input type="password" class="form-control radius-8" id="confirm_password" name="confirm_password" required placeholder="Enter Confirm Password">
                                    </div>                   
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
