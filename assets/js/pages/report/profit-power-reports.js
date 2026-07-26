
    // Declare chartInstance outside to persist across clicks
var globalChartInstance = null;

// Store original data for each chart
const chartOriginalData = {};

// Store current format for each chart
const chartCurrentFormat = {};
var chart2 = {},chart3 = {};
jQuery(document).ready(function($) {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



   
    // createChartThree('columnChart6', '#487FFF', '#FF9F29', '#e80f0f');
    // createChartTwo('columnChart7', '#487FFF', '#FF9F29');
    // createChartTwo('columnChart8', '#487FFF', '#FF9F29');


    $('#profit-power-report-form').validate({
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
            jQuery('#profit-power-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#profit-power-report-form').ajaxSubmit({
                url: site_url+'/api/getProfitPowerReport',
                success: function(res) {
                    jQuery('#profit-power-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){

                        jQuery('.balance-sheet-table').html(res.table_html);



                        var report_itemObj = get_report_item_list();

                      //   colorizeRowsByLabel(".balance-sheet-table table", [
                      //     "Revenue Growth %", "Gross Margin %", "Operating Profit %"
                      // ]);

                      //   oppcolorizeRowsByLabel(".balance-sheet-table table", [
                      //     "Overheads %", "COGS Growth %","Break Even Sales"
                      // ]);

                        setDataAttributeTB(['Revenue Growth %','COGS Growth %','Gross Margin %','Overheads %','Overheads Growth %','Operating Profit %','Net Profit %']);

                        setTimeout(function() {
                            // Trigger once for default selection
                            $("#figureType").trigger("change");
                        },150);


                        revenueCogsTrend( report_itemObj, res.chart_header );
                        bkSalesVsRevnue( report_itemObj, res.chart_header );
                        ProfitabilityParameters( report_itemObj, res.chart_header );
                        salevscogs( report_itemObj, res.chart_header );
                        salevsoverheads( report_itemObj, res.chart_header );


                        // chart3['columnChart6'].updateOptions({
                        //     xaxis: {
                        //         categories: res.chart_header
                        //     }
                        // });

                        // chart3['columnChart6'].updateSeries([
                        //     {
                        //         name: 'Gross Margin %',
                        //         data: report_itemObj['Gross Margin %']
                        //     },
                        //      {
                        //         name: 'Operating Profit %',
                        //         data: report_itemObj['Operating Profit %']
                        //     },
                        //      {
                        //         name: 'Net Profit %',
                        //         data: report_itemObj['Net Profit %']
                        //     }
                        // ]);

                        // chart2['columnChart7'].updateOptions({
                        //     xaxis: {
                        //         categories: res.chart_header
                        //     }
                        // });

                        // chart2['columnChart7'].updateSeries([
                        //     {
                        //         name: 'Revenue Growth %',
                        //         data: report_itemObj['Revenue Growth %']
                        //     },
                        //      {
                        //         name: 'COGS Growth %',
                        //         data: report_itemObj['COGS Growth %']
                        //     }
                        // ]);

                        // chart2['columnChart8'].updateOptions({
                        //     xaxis: {
                        //         categories: res.chart_header
                        //     }
                        // });

                        // chart2['columnChart8'].updateSeries([
                        //     {
                        //         name: 'Revenue Growth %',
                        //         data: report_itemObj['Revenue Growth %']
                        //     },
                        //      {
                        //         name: 'Overheads Growth %',
                        //         data: report_itemObj['Overheads Growth %']
                        //     }
                        // ]);
                        

                        jQuery('.balance-sheet-table-box').removeClass('d-none');

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
                jQuery('#profit-power-report-form button[type="submit"]').siblings('.spinner-border').hide();
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

    function revenueCogsTrend( report_itemObj, chart_header ){
        jQuery('#revenue-cogs-trend').empty();       
        const r_series = [
            {
                name: 'Revenue',
                data: report_itemObj['Revenue']
            },
            {
                name: 'COGS',
                data: report_itemObj['COGS']
            }
        ];
        var revenueCogsObj = {
            series: r_series,
            categories: chart_header
        };
        chartOriginalData['revenue-cogs-trend'] = JSON.parse(JSON.stringify(revenueCogsObj));
        chartCurrentFormat['revenue-cogs-trend'] = 'lakh';
        renderChart('revenue-cogs-trend', 'barChart', revenueCogsObj);
        updateChartFormat('revenue-cogs-trend', 'lakh');
    }


      function bkSalesVsRevnue( report_itemObj, chart_header ){
        jQuery('#break-even-sales-vs-revnue').empty();       
        const r_series = [
            {
                name: 'Break Even Sales',
                data: report_itemObj['Break Even Sales']
            },
            {
                name: 'Revenue',
                data: report_itemObj['Revenue']
            }
        ];
        var revenueCogsObj = {
            series: r_series,
            categories: chart_header
        };
        chartOriginalData['break-even-sales-vs-revnue'] = JSON.parse(JSON.stringify(revenueCogsObj));
        chartCurrentFormat['break-even-sales-vs-revnue'] = 'lakh';
        renderChart('break-even-sales-vs-revnue', 'barChart', revenueCogsObj);
        updateChartFormat('break-even-sales-vs-revnue', 'lakh');
    }

    function ProfitabilityParameters( report_itemObj, chart_header ){
        jQuery('#columnChart6').empty();       


        const r_series = [
            {
                name: 'Gross Margin %',
                data: report_itemObj['Gross Margin %']
            },
            {
                name: 'Operating Profit %',
                data: report_itemObj['Operating Profit %']
            },
            {
                name: 'Net Profit %',
                data: report_itemObj['Net Profit %']
            }
        ];
        var revenueCogsObj = {
            series: r_series,
            categories: chart_header
        };
        chartOriginalData['columnChart6'] = JSON.parse(JSON.stringify(revenueCogsObj));
        chartCurrentFormat['columnChart6'] = 'lakh';
        renderChart('columnChart6', 'barChart', revenueCogsObj);
    }

     function salevscogs( report_itemObj, chart_header ){
        jQuery('#columnChart7').empty();       
        const r_series = [
            {
                name: 'Revenue Growth %',
                data: report_itemObj['Revenue Growth %']
            },
            {
                name: 'COGS Growth %',
                data: report_itemObj['COGS Growth %']
            }
        ];
        var revenueCogsObj = {
            series: r_series,
            categories: chart_header
        };
        chartOriginalData['columnChart7'] = JSON.parse(JSON.stringify(revenueCogsObj));
        chartCurrentFormat['columnChart7'] = 'lakh';
        renderChart('columnChart7', 'barChart', revenueCogsObj);

    }

     function salevsoverheads( report_itemObj, chart_header ){
        jQuery('#columnChart8').empty();       
        const r_series = [
            {
                name: 'Revenue Growth %',
                data: report_itemObj['Revenue Growth %']
            },
            {
                name: 'Overheads Growth %',
                data: report_itemObj['Overheads Growth %']
            }
        ];
        var revenueCogsObj = {
            series: r_series,
            categories: chart_header
        };
        chartOriginalData['columnChart8'] = JSON.parse(JSON.stringify(revenueCogsObj));
        chartCurrentFormat['columnChart8'] = 'lakh';
        renderChart('columnChart8', 'barChart', revenueCogsObj);
    }

    jQuery(document).on( 'click', '.btn-download-report', function () {
         jQuery('#profit-power-report-form .btn-download-report').siblings('.spinner-border').show();
         jQuery('.report_type').val('download');

        $('#profit-power-report-form').ajaxSubmit({
            url: site_url+'/api/getProfitPowerReport',
            success: function(res) {
                jQuery('#profit-power-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#profit-power-report-form .btn-download-report').siblings('.spinner-border').hide();
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
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 40
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

        chart2[chartId] = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart2[chartId].render();
    }

    function createChartThree(chartId, color1, color2, color3) {
        var options = {
            series: [{
                name: 'Income',
                data: [48, 35, 55, 32, 48, 30, 55, 50, 57]
            }, {
                name: 'Expense',
                data: [12, 20, 15, 26, 22, 60, 40, 48, 25]
            }, {
                name: 'Rent',
                data: [12, 66, 15, 99, 22, 44, 40, 48, 25]
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
                colors: [color1, color2,color3], // Use two colors for the lines
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
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 40
                },
            },
            colors: [color1, color2,color3], // Set color for series
            fill: {
                type: 'gradient',
                colors: [color1, color2, color3],
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: [
                        `${color1}00`,
                        `${color2}00`,
                        `${color3}00`
                    ],
                    opacityFrom: [0, 0, 0],
                    opacityTo: [0, 0, 0],
                    stops: [0, 100]
                }
            },
            markers: {
                colors: [color1, color2,color3], // Use two colors for the markers
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

        chart3[chartId] = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart3[chartId].render();
    }
	
    
});