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
            jQuery('.new-dashboard-wraper').addClass('d-none');
            jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').show();
            jQuery('.profit-cashflow-insight .skeleton-loader,.wc-insight .skeleton-loader,.ratio-insight .skeleton-loader').removeClass('d-none');

            $('#cashflow-report-form').ajaxSubmit({
                url: site_url+'/api/getDashboardReports',
                success: function(res) {
                    jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    

                    if( res.status == 'success' ){

                        jQuery('.new-dashboard-wraper').removeClass('d-none');

                    jQuery('.filterDateSet').text( jQuery('select[name="month"] option:selected').text()  +'-'+ jQuery('select[name="year"] option:selected').text() );

                    jQuery('.pt-sales-amt').text(parseFloat(res.report_data['Sales'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-cogs-amt').text(parseFloat(res.report_data['COGS'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-gross-amt').text(parseFloat(res.report_data['Gross Margin'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.pt-profit-amt').text(parseFloat(res.report_data['Retained Profit'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-working-cap-amt').text(parseFloat(res.report_data['Working Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-other-cap-amt').text(parseFloat(res.report_data['Other Capital'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.pt-sub-a-amt').text(parseFloat(res.report_data['Retained Profit'].data.current_month + res.report_data['Other Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-sub-b-amt').text(parseFloat(res.report_data['Working Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-surplus-amt').text(parseFloat(res.report_data['Working Capital'].data.current_month - (res.report_data['Retained Profit'].data.current_month + res.report_data['Other Capital'].data.current_month)).toLocaleString('en-IN'));
                    jQuery('.pt-cap-wth-amt').text(parseFloat(res.report_data['Capital Withdrawn'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-operating-cash-flow-amt').text(parseFloat(res.report_data['Operating Cash Flow'].data.current_month).toLocaleString('en-IN'));  


                    jQuery('.pt-overheads-amt').text(parseFloat(res.report_data['Overheads'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-operating-cash-profit-amt').text(parseFloat(res.report_data['Operating Profit'].data.current_month).toLocaleString('en-IN'));
                    var cashCustAMt = (res.report_data['Sales'].data.last_month + res.report_data['Accounts Receivable'].data.current_month) - res.report_data['Accounts Receivable'].data.current_month;
                    var cashCustSup = (res.report_data['COGS'].data.last_month + res.report_data['Accounts Payable'].data.current_month) - res.report_data['Accounts Payable'].data.current_month;
                    jQuery('.pt-cash-cust-amt').text(parseFloat(cashCustAMt).toLocaleString('en-IN'));
                    jQuery('.pt-cash-sup-amt').text(parseFloat(cashCustSup).toLocaleString('en-IN'));
                    jQuery('.pt-gross-cash-amt').text(parseFloat(cashCustAMt - cashCustSup).toLocaleString('en-IN'));


                    jQuery('.acc-rec-box .wc-balance').text(parseFloat(res.report_data['Accounts Receivable'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.acc-rec-box .wc-row .val').text(parseFloat(res.report_data['Accounts Receivable'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.acc-rec-box .days-label .days-value').text(parseFloat(res.report_data['A/R Days'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.acc-ap-box .wc-balance').text(parseFloat(res.report_data['Accounts Payable'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.acc-ap-box .wc-row .val').text(parseFloat(res.report_data['Accounts Payable'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.acc-ap-box .days-label .days-value').text(parseFloat(res.report_data['A/P Days'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.inventory-box .wc-balance').text(parseFloat(res.report_data['Closing Stock'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.inventory-box .wc-row .val').text(parseFloat(res.report_data['Closing Stock'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.inventory-box .days-label .days-value').text(parseFloat(res.report_data['Inventory Days'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.net-working-cap-box .wc-balance').text(parseFloat(res.report_data['Working Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.net-working-cap-box .wc-row .val').text(parseFloat(res.report_data['Working Capital'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.net-working-cap-box .days-label .days-value').text(parseFloat(res.report_data['W/C Days'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.net-working-cap-box .days-label .days-value').text(parseFloat(res.report_data['W/C Days'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.bs-score').text(parseFloat(res.report_data['BS Category'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-revenue-amt-box .kpi-value.num').text(parseFloat(res.report_data['Sales'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Sales'].data.percentage, '.pt-revenue-amt-box' );
                    jQuery('.pt-operating-amt-box .kpi-value.num').text(parseFloat(res.report_data['Operating Cash Flow'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Operating Cash Flow'].data.percentage, '.pt-operating-amt-box' );
                    jQuery('.pt-grossmrg-amt-box .kpi-value.num').text(parseFloat(res.report_data['Gross Mrg Perc'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Gross Mrg Perc'].data.percentage, '.pt-grossmrg-amt-box' );
                    jQuery('.pt-netmrg-amt-box .kpi-value').text(parseFloat(res.report_data['Net Mrg Perc'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Net Mrg Perc'].data.percentage, '.pt-netmrg-amt-box' );
                    jQuery('.curr-ratio-box .ratio-value').text(parseFloat(res.report_data['CurrentRatio'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.curr-ratio-box .ratio-target span').text(parseFloat(res.report_data['CurrentRatio'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.quick-ratio-box .ratio-value').text(parseFloat(res.report_data['QuickRatio'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.quick-ratio-box .ratio-target span').text(parseFloat(res.report_data['QuickRatio'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.debt-equity-box .ratio-value').text(parseFloat(res.report_data['DebtToEquity'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.debt-equity-box .ratio-target span').text(parseFloat(res.report_data['DebtToEquity'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.interest-coverage-box .ratio-value').text(parseFloat(res.report_data['Interest Cover'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.interest-coverage-box .ratio-target span').text(parseFloat(res.report_data['Interest Cover'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.return-equity-box .ratio-value').text(parseFloat(res.report_data['Return on equity'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.return-equity-box .ratio-target span').text(parseFloat(res.report_data['Return on equity'].data.last_month).toLocaleString('en-IN'));
                                       
                    loadDeepSeekData( 'ProfitCashflow', jQuery('.profit-cash-grid').html(),  '.profit-cashflow-insight .insight-text', '.profit-cashflow-insight' );
                    loadDeepSeekData( 'WorkingCapital', jQuery('.acc-rec-box').html() + jQuery('.acc-ap-box').html() + jQuery('.inventory-box').html() + jQuery('.net-working-cap-box').html(),  '.wc-insight .insight-text',  '.wc-insight' );
                    loadDeepSeekData( 'FinancialRatio', jQuery('.ratio-grid').html(),  '.ratio-insight .insight-text', '.ratio-insight' );

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


    function badgeHtmlSet( percentage, clsName ){
        jQuery(clsName+' .kpi-trend').text(percentage+'%');
        jQuery(clsName+' .kpi-trend').removeClass('up').removeClass('down');
        if( percentage < 0 ){ 
            jQuery(clsName+' .kpi-trend').addClass('down');
        }else{
            jQuery(clsName+' .kpi-trend').addClass('up');
        }
    }

    function loadDeepSeekData( type = '', html_box, htmlWrapper, mainCLs ){
        jQuery.ajax({
            type: "POST",
            url: site_url+'/api/generateDashboardDataWithDeepSeek',
            data: {
                prompt_html : html_box,
                type : type,
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
                    jQuery(htmlWrapper).html(data.content);
                    jQuery(mainCLs+' .skeleton-loader').addClass('d-none');
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
	
});