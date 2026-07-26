    // Declare chartInstance outside to persist across clicks
var globalChartInstance = null;

// Store original data for each chart
const chartOriginalData = {};

// Store current format for each chart
const chartCurrentFormat = {};
jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    setTimeout(function(){
        jQuery('.assign_by').select2({ 'placeholder' : "Choose Assign" });

    },100);

    function generateNiceColors(count) {
        return Array.from({ length: count }, (_, i) =>
    `hsl(${(i * 360) / count}, 70%, 50%)`
    );
    }

    $('#cashflow-report-form').validate({
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
            jQuery('.report_type').val('view');
            jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#cashflow-report-form').ajaxSubmit({
                url: site_url+'/api/getCashflowReport',
                success: function(res) {
                    jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){

                        jQuery('.balance-sheet-table-box').removeClass('d-none');
                        jQuery('.balance-sheet-table').html(res.table_html);
                        var report_itemObj = get_report_item_list();
                         setDataAttributeTB(['Validation']);

                        setTimeout(function() {
                            // Trigger once for default selection
                            $("#figureType").trigger("change");
                     
                        },150);
                        cashflowWaterFallChart( report_itemObj );
                        cashInflowsOutFlowChart( report_itemObj, res.chart_header );
                        
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

    jQuery(document).on('change','.chart-value-type',function(){
        const chartId = $(this).data('chart-id');
        const format = $(this).val();
// Update chart with formatted data
        updateChartFormat(chartId, format);
    });


    // Separate function for chart rendering
    function cashflowWaterFallChart(report_itemObj) {

        jQuery('#cash-flow-waterfall').empty();
        
    // Extract Dates
        const dates = [];
        $('#sheet0 .row1 td:not(:first)').each(function () {
            dates.push($(this).text().trim());
        });
        var waterFallCHt = {
            series:  [
                {
                    name: "Operations",
                    data: report_itemObj['Cash flows from Operations']
                },
                {
                    name: "Investing",
                    data: report_itemObj['Cash flows from Investing']
                },
                {
                    name: "Financing",
                    data: report_itemObj['Cash flows from Financing']
                }
            ],
            categories: dates
        };
        chartOriginalData['cash-flow-waterfall'] = JSON.parse(JSON.stringify(waterFallCHt));
        chartCurrentFormat['cash-flow-waterfall'] = 'lakh';
        renderChart('cash-flow-waterfall', 'stackedBarChart', waterFallCHt);
        updateChartFormat('cash-flow-waterfall', 'lakh');
    }

    function cashInflowsOutFlowChart(report_itemObj, chart_header) {
        
        jQuery('#inflow-outflow-chart').empty();

        const r_data = [
            report_itemObj['Trade Receivables'][report_itemObj['Trade Receivables'].length - 1],
            report_itemObj['Trade Payables'][report_itemObj['Trade Payables'].length - 1],
            report_itemObj['Inventory'][report_itemObj['Inventory'].length - 1],
            report_itemObj['Equity'][report_itemObj['Equity'].length - 1],
            report_itemObj['Borrowings'][report_itemObj['Borrowings'].length - 1],
            report_itemObj['Others'][report_itemObj['Others'].length - 1]
        ];
        const r_categories = ["Trade Receivables", "Trade Payables", "Inventory", "Equity", "Borrowings", "Others"];
        const r_series = [
            {
                name: chart_header[ chart_header.length - 1],
                data: r_data
            }
        ];
        var outFlowInflowObj = {
            series: r_series,
            categories: r_categories
        };
        chartOriginalData['inflow-outflow-chart'] = JSON.parse(JSON.stringify(outFlowInflowObj));
        chartCurrentFormat['inflow-outflow-chart'] = 'lakh';
        renderChart('inflow-outflow-chart', 'horizontalBarChart', outFlowInflowObj);
        updateChartFormat('inflow-outflow-chart', 'lakh');
    }

    jQuery(document).on( 'click', '.btn-ai-report', function () {
        jQuery('#cashflow-report-form .btn-ai-report').siblings('.spinner-border').show();
        $('#cashflow-report-form').ajaxSubmit({
            url: site_url+'/api/getAICashFlowReport',
            success: function(res) {
                jQuery('#cashflow-report-form .btn-ai-report').siblings('.spinner-border').hide();
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
                jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').hide();
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

    jQuery(document).on( 'click', '.btn-download-report', function () {
         jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').show();
        jQuery('.report_type').val('download');
        $('#cashflow-report-form').ajaxSubmit({
            url: site_url+'/api/getCashflowReport',
            success: function(res) {
                jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').hide();
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

    jQuery(document).on( 'click', '.btn-old-view-report', function () {
         jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').show();
        jQuery('.report_type').val('view');
                        jQuery('.balance-sheet-table').empty();

        $('#cashflow-report-form').ajaxSubmit({
            url: site_url+'/api/getOldCashflowReport',
            success: function(res) {
                jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#cashflow-report-form .btn-download-report').siblings('.spinner-border').hide();
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