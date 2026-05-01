jQuery(document).ready(function() {
	 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    $('#pl-report-form').validate({
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
            jQuery('.pl-sheet-table').empty();
            jQuery('.pl-sheet-table-box').addClass('d-none');
            jQuery('#pl-report-form button[type="submit"]').siblings('.spinner-border').show();
            
            $('#pl-report-form').ajaxSubmit({
                url: site_url+'/api/getPLReportView',
                success: function(res) {
                    jQuery('#pl-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){
                        jQuery('.pl-sheet-table-box').removeClass('d-none');
                        jQuery('.pl-sheet-table').html(res.table_html);
 colorizeRowsByLabel(".pl-sheet-table table", [
                          "Gross Profit", "EBITDA", "PBIT", "Profit after Interest and before Tax", "Profit before Exceptional/Extraordinary Items and Tax", "Profit before Tax", "Profit after Tax", "Retained Profit"
                      ]);
                         setDataAttributeTB();
                        setTimeout(function() {
                            // Trigger once for default selection
                            $("#figureType").trigger("change");
                        },150);

                        
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
                    jQuery('#pl-report-form button[type="submit"]').siblings('.spinner-border').hide();
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
         jQuery('#pl-report-form .btn-download-report').siblings('.spinner-border').show();

        $('#pl-report-form').ajaxSubmit({
                url: site_url+'/api/getPLReport',
                success: function(res) {
                    jQuery('#pl-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                    jQuery('#pl-report-form .btn-download-report').siblings('.spinner-border').hide();
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
        jQuery('#pl-report-form .btn-ai-report').siblings('.spinner-border').show();
        $('#pl-report-form').ajaxSubmit({
            url: site_url+'/api/getAIPLReport',
            success: function(res) {
                jQuery('#pl-report-form .btn-ai-report').siblings('.spinner-border').hide();
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
                jQuery('#pl-report-form .btn-download-report').siblings('.spinner-border').hide();
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
            if( jQuery(this).closest('tr').hasClass('main-tr-inner') ){      
                jQuery('.hide-his-tr[data-label="'+jQuery(this).closest('tr').attr('data-key')+'"] .extra-row-btn').click();
            }
            jQuery('.sub-row[data-label="'+data_key+'"]').css('display','table-row');  
            jQuery(this).addClass('active');
        }
    } );
	
});