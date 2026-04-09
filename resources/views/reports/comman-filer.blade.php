<style>
.ui-datepicker-calendar,.ui-datepicker-current {
    display: none !important;
}
</style>
<div class="row  align-items-center">
  <div class="col-md-3 col-sm-12">
    <div class="mb-20">
      <label class="d-block mb-1"><strong>Assign By :</strong></label>
      <select class="assign_by form-control" name="assign_by">
        <option value="all">All</option>
        @foreach( $all_users as $u_key => $user_item )
        <option value="{{$user_item->id}}" @if ( auth()->user()->id == $user_item->id ) selected @endif>{{$user_item->name}}</option>
        @endforeach
      </select>  
    </div>
  </div>

  <div class="col-md-5 col-sm-12">
    <div class="mb-20">
      <div class='d-flex gap-3 align-items-center' id='date_filter'>                        
        <div>
          <label for="date_from"><strong>From</strong></label>
          <input type="text" id="date_from" class="form-control radius-8" readonly name="date_from" placeholder="From">
        </div>
        <div>
          <label for="date_to"><strong>To</strong></label>
          <input type="text" id="date_to" class="form-control radius-8" readonly name="date_to" placeholder="To">
        </div>
        <input type='hidden' class="form-control bg-white" name="date_filter" readonly placeholder="Select Between Date" />
        <input type="hidden" name="report_type" class="report_type" value="">
      </div>  
    </div>   
  </div>
  <div class="col-md-4 col-sm-12">
    <div class="d-flex align-items-center gap-3">
      <div>
        <button type="submit" class="btn btn-primary btn-view-report border border-primary-600 text-md px-56 py-12 radius-8"> 
          View
        </button>
        <div class="spinner-border text-primary" role="status" style="display: none;"></div>
      </div>
      <div>
        <button type="button" {{ (!isset($_COOKIE['kpi_date_filter']) || empty($_COOKIE['kpi_date_filter'])) ? 'disabled' : ''; }}  class="btn btn-primary btn-download-report border border-primary-600 text-md px-56 py-12 radius-8"> 
          Download
        </button>
        <div class="spinner-border text-primary" role="status" style="display: none;"></div>
      </div>
    </div>
  </div>
</div>