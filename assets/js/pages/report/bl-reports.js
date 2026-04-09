jQuery(document).ready(function() {
	 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    $('#bl-report-form').validate({
        // Validate only visible fields
        ignore: ":hidden",
        // Validation rules
        rules: {
            date_from : {
                required : true
            },
            date_to : {
                required : true
            }
        },

        // Display error
        invalidHandler: function(event, validator) {
            
            swal.fire({
                "title": "",
                 'icon': "error",
                "text": "There are some errors in your submission. Please correct them.",
                "type": "error",
                "buttonStyling": false,
                "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
            }).then(function () {
                // Scroll to the first visible error element after modal is dismissed
                if (validator.errorList.length) {
                    const firstErrorElement = $(validator.errorList[0].element);
                    if (firstErrorElement.is(":visible")) {
                        $('html, body').animate({
                            scrollTop: firstErrorElement.offset().top - 100 // adjust offset as needed
                        }, 500);
                        firstErrorElement.focus();
                    }
                }
            });
        },

        // Submit valid form
        submitHandler: function (form) {

            jQuery('.btn-download-report,.btn-ai-report').removeAttr('disabled');
            jQuery('.balance-sheet-table').empty();
            jQuery('.balance-sheet-table-box').addClass('d-none');
            jQuery('#bl-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#bl-report-form').ajaxSubmit({
                url: site_url+'/api/getBLReportView',
                success: function(res) {
                    jQuery('#bl-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){

                        jQuery('.balance-sheet-table-box').removeClass('d-none');

                        jQuery('.balance-sheet-table').html(res.table_html);

                    }
                    if( res.status == 'error' ){
                       swal.fire({
                            "title": "",
                             'icon': "error",
                            "text": res.message,
                            "type": "error",
                            "buttonStyling": false,
                            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                        });
                    }
                },
                error: function(res) {
                    jQuery('#bl-report-form button[type="submit"]').siblings('.spinner-border').hide();
                    swal.fire({
                        "title": "",
                         'icon': "error",
                        "text": res.responseJSON.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            });
        }
    });



    jQuery(document).on( 'click', '.btn-download-report', function () {
         jQuery('#bl-report-form .btn-download-report').siblings('.spinner-border').show();

        $('#bl-report-form').ajaxSubmit({
            url: site_url+'/api/getBLReport',
            success: function(res) {
                jQuery('#bl-report-form .btn-download-report').siblings('.spinner-border').hide();

                if( res.status == 'success' ){
                    download_file_by_url( res.pdf, res.filename );                      
                }   

                if( res.status == 'error' ){
                    swal.fire({
                        "title": "",
                        'icon': "error",
                        "text": res.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            },
            error: function(res) {
                jQuery('#bl-report-form .btn-download-report').siblings('.spinner-border').hide();
                swal.fire({
                    "title": "",
                    'icon': "error",
                    "text": res.responseJSON.message,
                    "type": "error",
                    "buttonStyling": false,
                    "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                });
            }
        });
    } );


    jQuery(document).on( 'click', '.btn-ai-report', function () {
        jQuery('#bl-report-form .btn-ai-report').siblings('.spinner-border').show();
        $('#bl-report-form').ajaxSubmit({
            url: site_url+'/api/getAIBLReport',
            success: function(res) {
                jQuery('#bl-report-form .btn-ai-report').siblings('.spinner-border').hide();
                if( res.status == 'success' ){
                    download_file_by_url( res.pdf, res.filename );                      
                }   

                if( res.status == 'error' ){
                    swal.fire({
                        "title": "",
                        'icon': "error",
                        "text": res.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            },
            error: function(res) {
                jQuery('#bl-report-form .btn-download-report').siblings('.spinner-border').hide();
                swal.fire({
                    "title": "",
                    'icon': "error",
                    "text": res.responseJSON.message,
                    "type": "error",
                    "buttonStyling": false,
                    "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                });
            }
        });
    } );

     jQuery(document).on( 'click', '.extra-row-btn', function () {
       var data_key = jQuery(this).attr('data-key');
        if( jQuery(this).hasClass('active') ){
            if( jQuery(this).hasClass('main-tr-btn') ){
                jQuery('.sub-row[data-main-key="'+data_key+'"]').css('display','none');  
                jQuery('.sub-row[data-main-key="'+data_key+'"] .extra-row-btn').removeClass('active');
            }else{
              if( jQuery('.sub-row[data-label="'+data_key+'"] .extra-row-btn').length > 0 ){
                   jQuery('.sub-row[data-label="'+data_key+'"] .extra-row-btn').each(function(){
                     var inner_key =  jQuery(this).attr('data-key');
                     jQuery(this).removeClass('active');
                     jQuery('.sub-row[data-label="'+data_key+'"]').css('display','none');  
                     jQuery('.sub-row[data-label="'+inner_key+'"]').css('display','none');  
                   });
               }else{
                   jQuery('.sub-row[data-label="'+data_key+'"]').css('display','none');  
               }
            }
           jQuery(this).removeClass('active');
            
        }else{
            jQuery('.sub-row[data-label="'+data_key+'"]').css('display','table-row');  
            jQuery(this).addClass('active');
        }
    } );
	
});