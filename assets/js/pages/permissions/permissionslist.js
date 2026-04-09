jQuery(document).ready(function($){
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  var datatable =  $('#dataTable').DataTable( {

        ajax: {
            url: site_url+'/api/permissionLists',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: null } // placeholder for custom rendering

        ],
        order: [[0, 'desc']],
        columnDefs: [
            {
        targets: 0, // last column
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
            
            return '<input class="form-check-input id-check-input" value="'+row.id+'" type="checkbox"> '+row.id;
        }
        }, 
        {
            targets: -1, // last column
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return `
                    <a href="`+site_url+`/permissions/${row.id}/edit" data-id="${row.id}" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                 <a href="javascript:void(0)"  data-id="${row.id}" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-permission">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </a>
                `;
            }
        }
    ],
        processing: true,
        serverSide: true

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

    jQuery(document).on( 'click', '.delete-permission', function(){
        var permission_id = $(this).data("id");
        if ( confirm("are you sure want to delete !") == true) {
            $.ajax({
                type: "POST",
                url: site_url+'/api/deletePermission',
                data: {
                    id:permission_id,
                },
                success: function (data) {
                    datatable.ajax.reload();
                },
                error: function (data) {
                }
            });
        }
    } );
     jQuery(document).on( 'click', '.bulk-delete', function(){
        var permission_id = $(this).data("id");

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
                url: site_url+'/api/deletePermission',
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