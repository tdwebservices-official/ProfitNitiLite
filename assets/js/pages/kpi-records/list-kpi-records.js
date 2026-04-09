jQuery(document).ready(function($){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
   var datatable = $('#dataTable').DataTable( {
           
        ajax: {
            url: site_url+'/api/kpiRecordLists',
            type: 'POST',
        },
        lengthMenu: [10, 25, 50, 100,500,1000,2000,5000],

        order: [[0, 'desc']],
        columns: [
            { data: 'id' },
            { data: 'tbdate' },
            { data: 'kpi_name' },
            { data: 'amount' },            
            { data: 'assign_by', visible: false },
            { data: null } 

        ],
        columnDefs: [  
            {
                targets: 0, width: '100px',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<input class="form-check-input id-check-input" value="'+row.id+'" type="checkbox"> '+row.id;
                }
            },{ 

            targets: 3, width: '200px',
            render: function (data, type, row, meta) {
                return '<input class="form-control hg-input-change d-none" name="amount" value="'+row.amount+'" type="number" ><span>'+row.amount+'</span>';
            },
          },
            {
                targets: -1,width: '150px', 
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    var kpi_record_button_html = '';

                    if( row.kpi_record_edit == 'yes' ){
                        
                        kpi_record_button_html += `<a href="javascript:;" data-id="${row.id}" class="w-32-px h-32-px bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center edit-kpi-record" style="margin-right:5px;">
                  <span class="edit-kpi-record-icon"><iconify-icon icon="lucide:edit"></iconify-icon></span>
                  <span class="save-kpi-record-icon d-none"><iconify-icon icon="lucide:save"></iconify-icon></span>                    
                </a>`;
                }
                

                    if( row.kpi_record_delete == 'yes' ){
                        kpi_record_button_html += `<a href="javascript:void(0)"  data-id="${row.id}" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-kpi_record">
<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                    </a>`;
                }
                return kpi_record_button_html;
            }
        }

    ],
        processing: true,
        serverSide: true,
        drawCallback: function (settings) {
        let apiJson = settings.json;
        if (apiJson) {
            $('#totalKPI').text(parseFloat(apiJson.totalKPI).toLocaleString(undefined, { minimumFractionDigits: 2 }));
        }
    }

    } );
    
   setTimeout(function(){
    jQuery('.assign_by').select2({ 'placeholder' : "Choose Assign" });
    $( "#type" ).select2( { "placeholder" : 'Select KPI Items' } );

    $('#date_filter').daterangepicker({
       buttonClasses: ' btn',
       applyClass: 'btn-primary',
       cancelClass: 'btn-secondary',
       showDropdowns: true,
   }, function(start, end, label) {
       var daterange = start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD');
       jQuery('#date_filter .form-control').val( daterange);
   });
    
},100);


    
    $('#date_filter').on('apply.daterangepicker', function(ev, picker) {

      datatable
        .column(1) // Replace 2 with the actual column index of 'assign_by'
        .search(picker.startDate.format('YYYY-MM-DD') + ' / ' + picker.endDate.format('YYYY-MM-DD'))
        .draw();
    });

    $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {

      datatable
        .column(1) // Replace 2 with the actual column index of 'assign_by'
        .search('')
        .draw();
    });


  jQuery(document).on( 'click', '.edit-kpi-record', function () {

    var p_this = jQuery(this);
    var kpi_record_id = p_this.attr('data-id');

    if(jQuery(this).hasClass('active')){

        if ( confirm("are you sure want to update !") == true) {
            $.ajax({
                type: "POST",
                url: site_url+'/api/updateKPIRecord',
                data: {
                    kpi_record_id:kpi_record_id,
                    amount:p_this.closest('tr').find('input[name="amount"]').val(),
                },
                dataType:'json',
                success: function (data) {

                    if( data.status == 'error' ){
                        swal.fire({
                            "title": "",
                             'icon': "error",
                            "text": data.message,
                            "type": "error",
                            "buttonStyling": false,
                            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                        });
                    }else{
                        p_this.removeClass('active');

                        p_this.closest('tr').find('input[name="amount"]').siblings('span').text( p_this.closest('tr').find('input[name="amount"]').val() );
                        p_this.closest('tr').find('.hg-input-change').addClass('d-none');
                        p_this.closest('tr').find('.hg-input-change').siblings('span').removeClass('d-none');
                        p_this.closest('tr').find('.save-kpi-record-icon').addClass('d-none');
                        p_this.closest('tr').find('.edit-kpi-record-icon').removeClass('d-none');
                        p_this.addClass('bg-primary').removeClass('bg-danger'); 
                    }

                },
                error: function (data) {

                    swal.fire({
                        "title": "",
                        'icon': "error",
                        "text": data.responseJSON.message !== undefined ? data.responseJSON.message : data.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            });

        }

        
    }else{
        jQuery(this).addClass('active');
        jQuery(this).closest('tr').find('.hg-input-change').removeClass('d-none');
        jQuery(this).closest('tr').find('.hg-input-change').siblings('span').addClass('d-none');
        jQuery(this).closest('tr').find('.save-kpi-record-icon').removeClass('d-none');
        jQuery(this).closest('tr').find('.edit-kpi-record-icon').addClass('d-none');
        jQuery(this).removeClass('bg-primary').addClass('bg-danger');
    }

    return false;

} );

    
    jQuery(document).on( 'change', '.assign_by', function(){
        datatable
        .column(4) // Replace 2 with the actual column index of 'assign_by'
        .search(this.value)
        .draw();
    } );

    jQuery(document).on( 'change', '#type', function(){
        datatable
        .column(2) // Replace 2 with the actual column index of 'assign_by'
        .search(this.value)
        .draw();
    } );

    jQuery(document).on( 'change', '.all-check', function () {
        if( jQuery(this).prop('checked') == true ){
            jQuery('#dataTable_wrapper input.form-check-input').prop('checked',true);
        }else{
            jQuery('#dataTable_wrapper input.form-check-input').prop('checked',false);
        }
    } );

    jQuery(document).on( 'change', '.id-check-input', function () {
        var table_tr_cnt = jQuery('#dataTable_wrapper .dataTable tbody tr').length;
        if( jQuery('#dataTable_wrapper .dataTable tbody tr .id-check-input:checked').length == table_tr_cnt ){

        jQuery('.all-check').prop('checked', true);
    }else{
        jQuery('.all-check').prop('checked', false);
    }

    } );
     

    jQuery(document).on( 'click', '.delete-kpi_record', function(){
        var kpirecord_id = $(this).data("id");
        if ( confirm("are you sure want to delete !") == true) {
            $.ajax({
                type: "POST",
                url: site_url+'/api/deleteKPIRecord',
                data: {
                    id:kpirecord_id,
                },
                dataType:'json',
                success: function (data) {
                    if( data.status == 'success' ){
                        datatable.ajax.reload();
                    }
                    if( data.status == 'error' ){
                       swal.fire({
                            "title": "",
                             'icon': "error",
                            "text": data.message,
                            "type": "error",
                            "buttonStyling": false,
                            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                        });
                    }
                },
                error: function (data) {
                    swal.fire({
                        "title": "",
                         'icon': "error",
                        "text": data.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            });
        }
    } );

    jQuery(document).on( 'click', '.bulk-delete', function(){
        var kpirecord_id = $(this).data("id");

        var selectedIDs = [];
        $('.id-check-input:checked').each(function() {
            selectedIDs.push($(this).val());
        });

        if (selectedIDs.length === 0) {
            alert('Please select at least one row to delete.');
            return;
        }

        if ( confirm("are you sure want to delete !") == true) {
            var p_this = jQuery(this);
            p_this.addClass('active-loader');
            $.ajax({
                type: "POST",
                url: site_url+'/api/deleteKPIRecord',
                data: {
                    id:selectedIDs,
                },
                dataType:'json',
                success: function (data) {
                    p_this.removeClass('active-loader');
                    if( data.status == 'success' ){
                        datatable.ajax.reload();
                        jQuery('.all-check').prop('checked', false);

                    }
                    if( data.status == 'error' ){
                       swal.fire({
                            "title": "",
                             'icon': "error",
                            "text": data.message,
                            "type": "error",
                            "buttonStyling": false,
                            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                        });
                    }
                },
                error: function (data) {
                    p_this.removeClass('active-loader');
                    jQuery('.all-check').prop('checked', false);
                    swal.fire({
                        "title": "",
                         'icon': "error",
                        "text": data.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            });
        }
    } );

});