@extends('layouts.master')
@section('content')
   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Add User</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Add User</li>
  </ul>
</div>

        <div class="card h-100 p-0 radius-12">
            <div class="card-body p-24">
                <div class="row justify-content-center">
                    <div class="col-xxl-6 col-xl-8 col-lg-10">
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
                                <form method="post" enctype="multipart/form-data">
                                	 @csrf
                                    <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Name <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="name" name="name" required placeholder="Enter Full Name" value="{{ old('name') }}" >
                                    </div>
                                    <div class="mb-20">
                                        <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                        <input type="email" class="form-control radius-8" id="email" name="email" required placeholder="Enter email address" value="{{ old('email') }}">
                                    </div>
                                    <div class="mb-20">
                                        <label for="username" class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="username" name="username" required placeholder="Enter username" value="{{ old('username') }}">
                                    </div>  
                                    <div class="mb-20">
                                      <label class="d-block mb-1"><strong>Industry :</strong></label>
                                              <select class="industry_id form-control" name="industry_id">                        

                                                @foreach( $industry_list as $industry_category => $industry_items )
                                                <optgroup label="{{$industry_category}}">
                                                  @foreach( $industry_items as $industry_item )                                            
                                                  <option value="{{$industry_item->id}}">{{$industry_item->name}}</option>
                                                  @endforeach
                                              </optgroup>                                            
                                              @endforeach

                                          </select>  
                                    </div>
                                    <div class="mb-20">
                                        <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span class="text-danger-600">*</span></label>
                                        <input type="password" class="form-control radius-8" id="password" name="password" required placeholder="Enter password" value="{{ old('password') }}">
                                    </div>    
                                     <div class="mb-20">
                                        <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm Password <span class="text-danger-600">*</span></label>
                                        <input type="password" class="form-control radius-8" id="confirm_password" name="confirm_password" required placeholder="Enter Confirm password" value="{{ old('confirm_password') }}">
                                    </div>                                   
                                    <div class="mb-20">
                                        <label for="image" class="form-label">Profile Image</label>
                                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                            Save
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

@section('scripts')
<script>

jQuery(document).ready(function(){
    setTimeout(function () {
        jQuery('.industry_id').select2({ 'placeholder' : "Choose Industry" });
    },300);
});
</script>
@endsection
