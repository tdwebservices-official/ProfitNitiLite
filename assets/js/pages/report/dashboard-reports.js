jQuery(document).ready(function() {
	 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

	setTimeout(function(){
        jQuery('.assign_by').select2({ 'placeholder' : "Choose Assign" });
        jQuery('.month-select').select2({ 'placeholder' : "Choose Month" }); 
        jQuery('.year-select').select2({ 'placeholder' : "Choose Year" }); 
        jQuery('.btn-view-report').click();
  
    },100);


    $('#cashflow-report-form').validate({
        // Validate only visible fields
        ignore: ":hidden",
        // Validation rules
        rules: {
            date_filter : {
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

            jQuery('.btn-download-report').removeAttr('disabled');
            jQuery('.report-data-list').empty();
            jQuery('.report_type').val('view');
            jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#cashflow-report-form').ajaxSubmit({
                url: site_url+'/api/getDashboardReports',
                success: function(res) {
                    jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){
                        var record_list = '';   
                        var ij = 1;
                        jQuery.each( res.report_data, function( record_title, record_item ){
                           
                            var percentage_html = '';

                            var last_month_string = parseFloat(record_item.data.last_month).toLocaleString('en-IN');
                            var current_month_string = parseFloat(record_item.data.current_month).toLocaleString('en-IN');

                            if( record_item.noInt == 1 ){
                                last_month_string = record_item.data.last_month;
                                current_month_string = record_item.data.current_month;
                            }

                            
                            if( record_item.data.percentage < 0 ){ 
                                percentage_html = '<p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">\
                                    <span class="bg-danger-focus px-6 py-2 rounded-2 fw-medium text-danger-main text-sm d-flex align-items-center gap-1">\
                                        <i class="ri-arrow-right-down-line"></i> '+parseFloat(record_item.data.percentage).toLocaleString('en-IN')+'%\
                                    </span> Last month '+last_month_string+'\
                                </p>';
                            }else{
                                percentage_html = '<p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">\
                                    <span class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">\
                                        <i class="ri-arrow-right-up-line"></i> '+parseFloat(record_item.data.percentage).toLocaleString('en-IN')+'%\
                                    </span> Last month '+last_month_string+'\
                                </p>';
                            }
                            
                            var urlname = record_title.toLowerCase();
                            urlname = urlname.replaceAll('&','');
                            urlname = urlname.replaceAll('/','');
                            urlname = urlname.replace(/\s+/g, ' ');

                            urlname = urlname.replaceAll(' ', '_');
                            
                         record_list +=   '<div class="col-xxl-3 col-sm-6">\
                            <div class="card p-3 report-box shadow-2 radius-8 h-100 gradient-deep-two-'+ij+' border border-white">\
                                <div class="card-body p-0">\
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">\
                                        <div class="d-flex align-items-center gap-10">\
                                            <span class="mb-0 w-48-px h-48-px bg-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">\
                                                <img src="assets/images/home-eleven/icons/home-eleven-icon1.svg" alt="">\
                                            </span>\
                                            <div>\
                                                <span class="fw-medium text-secondary-light text-md">'+record_title+'</span>\
                                                <h6 class="fw-semibold mt-2">'+current_month_string+'</h6>\
                                            </div>\
                                        </div>\
                                    </div>'+percentage_html+'\
                                </div>\
                            </div>\
                        </div>';
                        
                        if( ij == 4 ){
                            ij = 1;
                        }else{
                            ij++;
                        }
                        });

                        jQuery('.report-data-list').html(record_list);

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
                    jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').hide();
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


	
});