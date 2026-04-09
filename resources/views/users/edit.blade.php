@extends('layouts.master')
@section('content')
   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit User</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Edit User</li>
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
                                <form method="post" enctype="multipart/form-data" action="{{ route('users.update', $user->id) }}">
                                	 @csrf
                                    <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Name <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="name" name="name" required placeholder="Enter Full Name" value="{{ $user->name }}" >
                                    </div>
                                    <div class="mb-20">
                                        <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                        <input type="email" class="form-control radius-8" id="email" name="email" required placeholder="Enter email address" value="{{ $user->email }}">
                                    </div>
                                    <div class="mb-20">
                                        <label for="username" class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="username" name="username" required placeholder="Enter username" value="{{ $user->username }}">
                                    </div>  
                                    <div class="mb-20">
                                        <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span class="text-danger-600">*</span></label>
                                    <input type="password" autocomplete="new-password" class="form-control" name="new_password" placeholder="Password">
                                    </div>  
                                     <div class="mb-20">
                                      <label class="d-block mb-1"><strong>Industry :</strong></label>
                                              <select class="industry_id form-control" name="industry_id">                        

                                                @foreach( $industry_list as $industry_category => $industry_items )
                                                <optgroup label="{{$industry_category}}">
                                                  @foreach( $industry_items as $industry_item )                                            
                                                  <option value="{{$industry_item->id}}" {{ ($industry_id == $industry_item->id ) ? "selected" : ""; }} >{{$industry_item->name}}</option>
                                                  @endforeach
                                              </optgroup>                                            
                                              @endforeach

                                          </select>  
                                    </div>
                                    <div class="mb-20">
                                        <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">Role <span class="text-danger-600">*</span></label>
                                        <select class="form-control" name="role" required>
                                            <option value="">Select role</option>
                                            @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ in_array($role->name, $userRole) 
                                                ? 'selected'
                                                : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>    
                                    <div class="mb-20">
                                        <label for="image" class="form-label fw-semibold text-primary-light text-sm mb-8">Profile Image</label>
                                        <input type="file" name="image" class="form-control" id="imageInput" onchange="previewImage(event)">
                                        
                                        @php
                                            $imagePath = 'assets/images/users/' . $user->id . '/' . $user->image;
                                        @endphp

                                        @if ($user->image && file_exists(public_path($imagePath)))
                                            <div class="mt-2" id="imagePreviewContainer">
                                                <label class="fw-semibold text-sm">Current Image:</label><br>
                                          <img id="imagePreview"
                                        src="{{ url('public/assets/images/users/' . $user->id . '/' . rawurlencode($user->image)) }}"
                                        alt="User Image"
                                        style="max-width: 100px;">
                                                <p class="text-muted" id="fileName">{{ $user->image }}</p>
                                            </div>
                                        @else
                                            <div class="mt-2" id="imagePreviewContainer">
                                                <label class="fw-semibold text-sm">Current Image:</label><br>
                                                <img id="imagePreview"
                                                    src="{{ asset('assets/images/default-user.png') }}"
                                                    alt="Default Image"
                                                    style="max-width: 100px;">
                                                <p class="text-muted" id="fileName">No image uploaded</p>
                                            </div>
                                        @endif
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


@section('scripts')
<script>

jQuery(document).ready(function(){
    setTimeout(function () {
        jQuery('.industry_id').select2({ 'placeholder' : "Choose Industry" });
    },300);
});

function previewImage(event) {
    const input = event.target;
    const fileName = document.getElementById('fileName');
    const preview = document.getElementById('imagePreview');

    if (input.files && input.files.length > 0) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
            }
            if (fileName) {
                fileName.textContent = input.files[0].name;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
