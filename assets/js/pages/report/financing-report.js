var chart1 = {};
jQuery(document).ready(function() {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

     createChartTwo('columnChart1', '#487FFF', '#FF9F29');


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

                        setDataAttributeTB();

                        chart1['columnChart1'].updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart1['columnChart1'].updateSeries([
                            {
                                name: 'Operating Cash Profit',
                                data: report_itemObj['Operating Cash Profit']
                            },
                             {
                                name: 'Operating Cash Flow',
                                data: report_itemObj['Operating Cash Flow']
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


    function createChartTwo(chartId, color1, color2) {
        var options = {
            series: [{
                name: 'Income',
                data: [48, 35, 55, 32, 48, 30, 55, 50, 57]
            }, {
                name: 'Expense',
                data: [12, 20, 15, 26, 22, 60, 40, 48, 25]
            }],
            legend: {
                show: false 
            },
            chart: {
                type: 'area',
                width: '100%',
                height: 200,
                toolbar: {
                    show: false
                },
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: [color1, color2], // Use two colors for the lines
                lineCap: 'round'
            },
            grid: {
                show: true,
                borderColor: '#D1D5DB',
                strokeDashArray: 1,
                position: 'back',
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                row: {
                    colors: undefined,
                    opacity: 0.5
                },
                column: {
                    colors: undefined,
                    opacity: 0.5
                },
                padding: {
                    top: -20,
                    right: 0,
                    bottom: -10,
                    left: 0
                },
            },
            colors: [color1, color2], // Set color for series
            fill: {
                type: 'gradient',
                colors: [color1, color2], // Use two colors for the gradient
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: [undefined, `${color2}00`], // Apply transparency to both colors
                    inverseColors: false,
                    opacityFrom: [0, 0], // Starting opacity for both colors
                    opacityTo: [0, 0], // Ending opacity for both colors
                    stops: [0, 100],
                },
            },
            markers: {
                colors: [color1, color2], // Use two colors for the markers
                strokeWidth: 3,
                size: 0,
                hover: {
                    size: 10
                }
            },
            xaxis: {
                labels: {
                    show: false
                },
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                tooltip: {
                    enabled: false
                },
                labels: {
                    formatter: function (value) {
                        return value;
                    },
                    style: {
                        fontSize: "14px"
                    }
                }
            },
           
            yaxis: {
          labels: {
                formatter: function (val) {
                    return ((val / 1000).toFixed(0) > 0) ? (val / 1000).toFixed(0) + 'k' : val;
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString('en-IN');
                }
            }
        },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                }
            }
        };

        chart1[chartId] = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart1[chartId].render();
    }
	
    
});