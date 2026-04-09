@extends('layouts.master')
@section('content')
   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Edit Note</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Edit Note</li>
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
                                <form method="post" enctype="multipart/form-data" class="edit-note-form" id="edit-note-form">
                                     @csrf
                                    <div class="mb-20">
                                        <label for="t_month" class="form-label fw-semibold text-primary-light text-sm mb-8">Month <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="t_month" name="t_month"  placeholder="Choose Month" readonly value="{{ ($note->t_month) ? date('d-m-Y', strtotime($note->t_month)) : '' }}" >
                                    </div>  
                                    <div class="mb-20">
                                        <label for="target" class="form-label fw-semibold text-primary-light text-sm mb-8">Target <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="target" name="target"  placeholder="Enter Target" value="{{ $note->target }}" >
                                    </div>
                                    <div class="mb-20">
                                        <label for="action_steps" class="form-label fw-semibold text-primary-light text-sm mb-8">Action Steps <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="action_steps" name="action_steps"  placeholder="Enter Action Steps" value="{{ $note->action_steps }}" >
                                    </div>
                                    <div class="mb-20">
                                        <label for="person_responsible" class="form-label fw-semibold text-primary-light text-sm mb-8">Person Responsible <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="person_responsible" name="person_responsible"  placeholder="Enter Person Responsible" value="{{ $note->person_responsible }}" >
                                    </div>   
                                    <div class="mb-20">
                                        <label for="target_date" class="form-label fw-semibold text-primary-light text-sm mb-8">Target Date <span class="text-danger-600">*</span></label>
                                        <input type="text" class="form-control radius-8" id="target_date" name="target_date"  placeholder="Choose Target Date" readonly value="{{ ($note->target_date) ? date('d-m-Y', strtotime($note->target_date)) : '' }}" >
                                    </div>   
                                    <div class="mb-20">
                                        <label for="status" class="form-label fw-semibold text-primary-light text-sm mb-8">Status <span class="text-danger-600">*</span></label>
                                        <select  class="form-control radius-8" name="status">                                           
                                            @foreach( $note_statuses as $status_key => $status_name )
                                            <option value="{{$status_key}}" @if($status_key == $note->status) selected @endif>{{$status_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>   
                                    <div class="mb-20">
                                        <label for="remarks" class="form-label fw-semibold text-primary-light text-sm mb-8">Remarks </label>
                                        <textarea class="form-control radius-8" id="remarks" name="remarks"  placeholder="Enter Remarks">{{ $note->remarks }}</textarea>
                                        <input type="hidden"  name="note_id"  value="{{ isset($note->id) ? $note->id : 0 }}" >
                                    </div>                                     
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="reset" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                            Update
                                        </button>
                                        <div class="spinner-border text-primary" role="status" style="display: none;"></div>
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
<script src="{{asset('assets/js/pages/note/edit-note.js?ver='.time())}}" type="text/javascript"></script>
@endsection