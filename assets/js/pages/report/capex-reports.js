var chart1 = {};
jQuery(document).ready(function() {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    createSingleBarChart('columnChart1', '#487FFF');
    createSingleBarChart('columnChart2', '#FF9F29');
    createSingleBarChart('columnChart3', '#e80f0f');

    $('#capex-report-form').validate({
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
            jQuery('#capex-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#capex-report-form').ajaxSubmit({
            url: site_url+'/api/getCapexReport',
                success: function(res) {
                    jQuery('#capex-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){

                        jQuery('.balance-sheet-table-box').removeClass('d-none');
                        jQuery('.balance-sheet-table').html(res.table_html);

                        var report_itemObj = get_report_item_list();

                        setDataAttributeTB();


                        chart1['columnChart1'].updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart1['columnChart1'].updateSeries([
                            {
                                name: 'Return on Capital %',
                                data: report_itemObj['Return on Capital %']
                            }
                        ]);


                        chart1['columnChart2'].updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart1['columnChart2'].updateSeries([
                            {
                                name: 'Return on Equity %',
                                data: report_itemObj['Return on Equity %']
                            }
                        ]);

                        chart1['columnChart3'].updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart1['columnChart3'].updateSeries([
                            {
                                name: 'Asset Turnover',
                                data: report_itemObj['Asset Turnover']
                            }
                        ]);

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
                jQuery('#capex-report-form button[type="submit"]').siblings('.spinner-border').hide();
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
         jQuery('#capex-report-form .btn-download-report').siblings('.spinner-border').show();
         jQuery('.report_type').val('download');
        $('#capex-report-form').ajaxSubmit({
            url: site_url+'/api/getCapexReport',
            success: function(res) {
                jQuery('#capex-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#capex-report-form .btn-download-report').siblings('.spinner-border').hide();
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
	   


    function createSingleBarChart(chartId, color) {
        var options = {
            series: [{
                name: 'Income',
                data: [48, 35, 55, 32, 48, 30, 55, 50, 57]
            }],

            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: false
                }
            },

            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '35%'
                }
            },

            dataLabels: {
                enabled: false
            },

            colors: [color], // single color

            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                labels: {
                    style: {
                        fontSize: "12px"
                    }
                }
            },

            yaxis: {
                labels: {
                    formatter: function (val) {
                        return val.toLocaleString('en-IN');
                    }
                }
            },

            grid: {
                borderColor: '#D1D5DB',
                strokeDashArray: 2
            },

            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString('en-IN');
                    }
                }
            }
        };

        chart1[chartId] = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart1[chartId].render();
    }
    
});

