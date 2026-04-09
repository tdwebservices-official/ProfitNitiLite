@extends('layouts.master')
@section('content')
   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Branding Details</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Branding Details</li>
  </ul>
</div>

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
        <form method="post" enctype="multipart/form-data" class="add-branding-form" id="add-branding-form">
             @csrf

             <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Identification</h6>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                     <div class="col-12">
                        <label for="logo" class="form-label fw-semibold text-primary-light text-sm mb-8">Logo </label>

                        <div class="form-group row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_3">
                                    <div class="dropzone-msg dz-message needsclick">
                                        <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="branding_options[logo_media_id]" id="logo_media_id" value="{{ isset($branding_option_arr['logo_media_id']) ? $branding_option_arr['logo_media_id'] : '' }}">
                            <input type="hidden" name="branding_options[logo_media_url]" id="logo_media_url" value="{{ isset($branding_option_arr['logo_media_url']) ? $branding_option_arr['logo_media_url'] : '' }}">
                        </div>
                    </div>
                    <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="logo_width" class="form-label fw-semibold text-primary-light text-sm mb-8">Logo Width (Pixel)</label>
                                    <input type="number" class="form-control radius-8" id="logo_width" name="branding_options[logo_width]"  placeholder="Enter logo width" value="{{ isset($branding_option_arr['logo_width']) ? $branding_option_arr['logo_width'] : '' }}" >
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="logo_height" class="form-label fw-semibold text-primary-light text-sm mb-8">Logo Height (Pixel)</label>
                                    <input type="number" class="form-control radius-8" id="logo_height" name="branding_options[logo_height]"  placeholder="Enter logo height" value="{{ isset($branding_option_arr['logo_height']) ? $branding_option_arr['logo_height'] : '' }}" >
                                </div>
                            </div>
                        </div>  
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="company_name" class="form-label fw-semibold text-primary-light text-sm mb-8">Company Name </label>
                                    <input type="text" class="form-control radius-8" id="company_name" name="branding_options[company_name]"  placeholder="Enter company_name" value="{{ isset($branding_option_arr['company_name']) ? $branding_option_arr['company_name'] : '' }}" >
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email </label>
                                    <input type="text" class="form-control radius-8" id="email" name="branding_options[email]"  placeholder="Enter Email" value="{{ isset($branding_option_arr['email']) ? $branding_option_arr['email'] : '' }}" >
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                               
                                <div class="col-md-6 col-12">
                                    <label for="phone_no" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone number</label>
                                    <input type="text" class="form-control radius-8" id="phone_no" name="branding_options[phone_no]"  placeholder="Enter Phone number" value="{{ isset($branding_option_arr['phone_no']) ? $branding_option_arr['phone_no'] : '' }}" >
                                </div>

                                <div class="col-md-6 col-12">
                                    <label for="report_main_title" class="form-label fw-semibold text-primary-light text-sm mb-8">Report Main Title</label>
                                    <input type="text" class="form-control radius-8" id="report_main_title" name="branding_options[report_main_title]"  placeholder="Enter report main title" value="{{ isset($branding_option_arr['report_main_title']) ? $branding_option_arr['report_main_title'] : '' }}" >
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Address Information</h6>
                </div>
                <div class="card-body">
                    <div class="row gy-3">

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="address_line_1" class="form-label fw-semibold text-primary-light text-sm mb-8">Address line 1 </label>
                                    <input type="text" class="form-control radius-8" id="address_line_1" name="branding_options[address_line_1]"  placeholder="Enter Address line 1" value="{{ isset($branding_option_arr['address_line_1']) ? $branding_option_arr['address_line_1'] : '' }}" >
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="address_line_2" class="form-label fw-semibold text-primary-light text-sm mb-8">Address line 2</label>
                                    <input type="text" class="form-control radius-8" id="address_line_2" name="branding_options[address_line_2]"  placeholder="Enter Branch Name" value="{{ isset($branding_option_arr['address_line_2']) ? $branding_option_arr['address_line_2'] : '' }}" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="country" class="form-label fw-semibold text-primary-light text-sm mb-8">Country</label>
                                    <input type="text" class="form-control radius-8" id="country" name="branding_options[country]"  placeholder="Enter Country" value="{{ isset($branding_option_arr['country']) ? $branding_option_arr['country'] : '' }}" >
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="state" class="form-label fw-semibold text-primary-light text-sm mb-8">State / Province</label>
                                    <input type="text" class="form-control radius-8" id="state" name="branding_options[state]"  placeholder="Enter State / Province" value="{{ isset($branding_option_arr['state']) ? $branding_option_arr['state'] : '' }}" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="city" class="form-label fw-semibold text-primary-light text-sm mb-8">City</label>
                                    <input type="text" class="form-control radius-8" id="city" name="branding_options[city]"  placeholder="Enter City" value="{{ isset($branding_option_arr['city']) ? $branding_option_arr['city'] : '' }}" >
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="postal_code" class="form-label fw-semibold text-primary-light text-sm mb-8">Postal code</label>
                                    <input type="text" class="form-control radius-8" id="postal_code" name="branding_options[postal_code]"  placeholder="Enter Postal code" value="{{ isset($branding_option_arr['postal_code']) ? $branding_option_arr['postal_code'] : '' }}" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                                              
            <div class="d-flex align-items-center mt-5 gap-3">                
                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                    Save
                </button>
                <div class="spinner-border text-primary" role="status" style="display: none;"></div>
                <button type="reset" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                    Reset
                </button>
            </div>
        </form>
        
    </div>
@endsection

 @section('scripts')
<script src="{{asset('assets/js/pages/branding/branding.js?ver='.time())}}" type="text/javascript"></script>
@endsection