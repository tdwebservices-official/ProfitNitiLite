@extends('layouts.master')
@section('content')
   <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Add Role</h6>
  <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">Add Role</li>
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
                                <form method="post" enctype="multipart/form-data" action="{{ route('roles.store') }}">
                                	 @csrf
                                     <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Name <span class="text-danger-600">*</span></label>
                                        <input value="{{ old('name') }}" type="text" class="form-control radius-8" name="name" placeholder="Enter your role name">
                                    </div>
                                    <div class="mb-20">
                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Assign Permissions <span class="text-danger-600">*</span></label>
                                        <table class="table table-striped">
                                            <thead>
                                                <th scope="col" width="1%"><input class="all_permission" type="checkbox" name="all_permission"></th>
                                                <th scope="col" width="20%">Name</th>
                                                <th scope="col" width="1%">Guard</th> 
                                            </thead>
                                            <tbody>
                                                @foreach($permissions as $permission)
                                                <tr class="permission_name permission_name{{ $permission->id }}">
                                                    <td>


                                                        <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                                            <input type="checkbox"
                                                            data-id="permission_name{{ $permission->id }}" 
                                                            name="permission[{{ $permission->name }}]"
                                                            value="{{ $permission->name }}"
                                                            class="form-check-input"  role="switch" >
                                                        </div>  

                                                    </td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>{{ $permission->guard_name }}</td>
                                                </tr>
                                                @endforeach
                                                <tr class="perror" style="display: none;text-align: center;background-color: #ffffff;">
                                                    <td colspan="3" style="padding: 30px;">
                                                        <span>No records found</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>                                           </div>
                                                            
                                    
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