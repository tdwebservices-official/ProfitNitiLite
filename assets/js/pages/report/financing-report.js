
    // Declare chartInstance outside to persist across clicks
var globalChartInstance = null;

// Store original data for each chart
const chartOriginalData = {};

// Store current format for each chart
const chartCurrentFormat = {};
var chart1 = {};
jQuery(document).ready(function() {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    $('#financing-report-form').validate({
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

            jQuery('.btn-download-report').removeAttr('disabled');
            jQuery('.balance-sheet-table').empty();
            jQuery('.balance-sheet-table-box').addClass('d-none');
            jQuery('.report_type').val('view');
            jQuery('#financing-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#financing-report-form').ajaxSubmit({
            url: site_url+'/api/getFinancingReport',
                success: function(res) {
                    jQuery('#financing-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){

                        jQuery('.balance-sheet-table-box').removeClass('d-none');
                        jQuery('.balance-sheet-table').html(res.table_html);

                        var report_itemObj = get_report_item_list();

                        setDataAttributeTB(['Marginal Cash Flow','Debt to Equity','Debt to Capital','Interest Cover','Debt Payback','Operating CF Margin','Cash Flow Coverage','Cash Flow to Debt','Capex Coverage']);
                        cashprofitVSCashflow( report_itemObj, res.chart_header );

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
                jQuery('#financing-report-form button[type="submit"]').siblings('.spinner-border').hide();
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

    function cashprofitVSCashflow( report_itemObj, chart_header ){
        jQuery('#cashprofit-vs-cashflow').empty();       
        const r_series = [
            {
                name: 'Operating Cash Profit',
                data: report_itemObj['Operating Cash Profit']
            },
            {
                name: 'Operating Cash Flow',
                data: report_itemObj['Operating Cash Flow']
            }
        ];
        var revenueCogsObj = {
            series: r_series,
            categories: chart_header
        };
        chartOriginalData['cashprofit-vs-cashflow'] = JSON.parse(JSON.stringify(revenueCogsObj));
        chartCurrentFormat['cashprofit-vs-cashflow'] = 'lakh';
        renderChart('cashprofit-vs-cashflow', 'barChart', revenueCogsObj);
        updateChartFormat('cashprofit-vs-cashflow', 'lakh');
    }

    jQuery(document).on( 'click', '.btn-download-report', function () {
         jQuery('#financing-report-form .btn-download-report').siblings('.spinner-border').show();
         jQuery('.report_type').val('download');

        $('#financing-report-form').ajaxSubmit({
            url: site_url+'/api/getFinancingReport',
            success: function(res) {
                jQuery('#financing-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#financing-report-form .btn-download-report').siblings('.spinner-border').hide();
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
   
    
});