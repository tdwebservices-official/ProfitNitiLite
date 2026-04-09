jQuery(document).ready(function($){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
   var datatable = $('#dataTable').DataTable( {
       
       

        ajax: {
            url: site_url+'/api/noteLists',
            type: 'POST',
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'id' },
            { data: 't_month' },
            { data: 'target' },
            { data: 'action_steps' },
            { data: 'person_responsible' },
            { data: 'target_date' },
            { data: 'status' },
            { data: 'remarks' },
            { data: 'user_id', visible: false },
            { data: null } // placeholder for custom rendering
        ],
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
            targets: 6, // Custom render for 'type' column
            render: function (data, type, row, meta) {
                if (data == '1') {
                    return `<span class="badge bg-secondary">Open</span>`;
                } else if (data == '2') {
                    return `<span class="badge bg-primary">Partially Closed</span>`;
                } else if (data == '3') {
                    return `<span class="badge bg-danger">Closed</span>`;
                } else {
                    return `<span class="badge bg-secondary">Unknown</span>`;
                }
            }
        },
        {
            targets: -1, // last column
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                var note_button_html = '';
                if( row.note_edit == 'yes' ){
                    note_button_html += `<a href="`+site_url+`/note/${row.id}/edit" data-id="${row.id}" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>`;
                }
                if( row.note_delete == 'yes' ){
                    note_button_html += `<a href="javascript:void(0)"  data-id="${row.id}" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-note">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </a>`;
                }
                return note_button_html;
            }
        }
    ],
        processing: true,
        serverSide: true

    } );
    
    setTimeout(function(){
        jQuery('.user_id').select2({ 'placeholder' : "Choose Assign" });
    },100);
        
    jQuery(document).on( 'change', '.user_id', function(){
        datatable
        .column(4) // Replace 2 with the actual column index of 'user_id'
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
     

    jQuery(document).on( 'click', '.delete-note', function(){
        var note_id = $(this).data("id");
        if ( confirm("are you sure want to delete !") == true) {
            $.ajax({
                type: "POST",
                url: site_url+'/api/deleteNote',
                data: {
                    id:note_id,
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
        var note_id = $(this).data("id");

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
                url: site_url+'/api/deleteNote',
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