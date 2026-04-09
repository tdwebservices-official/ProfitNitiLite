var chart2 = {};
jQuery(document).ready(function($) {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


     function waterfullchart() {

  const chartData = {};

  // ✅ Extract Data
  $('#sheet0 tbody tr').each(function () {
    const label = $(this).find('td:first').text().trim();

    if ([
      'Revenue',
      'COGS',
      'Overheads including depreciation',
      'Net Profit'
    ].includes(label)) {

      chartData[label] = [];

      $(this).find('td:not(:first)').each(function () {
        let val = $(this).text().replace(/,/g, '').trim();
        val = parseFloat(val) || 0;
        chartData[label].push(val);
      });
    }
  });

  // ✅ Extract Dates
  const dates = [];
  $('#sheet0 .row2 td:not(:first)').each(function () {
    dates.push($(this).text().trim());
  });

  let categories = [];
  let base = [];
  let values = [];

  // ✅ Build Waterfall
  dates.forEach((date, i) => {

    let revenue = chartData['Revenue'][i] || 0;
    let cogs = -Math.abs(chartData['COGS'][i] || 0);
    let overheads = -Math.abs(chartData['Overheads including depreciation'][i] || 0);
    let netProfit = chartData['Net Profit'][i] || 0;

    let cumulative = 0;

    // Revenue
    categories.push(`${date} Revenue`);
    base.push(0);
    values.push(revenue);
    cumulative = revenue;

    // COGS
    categories.push(`${date} COGS`);
    base.push(cumulative);
    values.push(cogs);
    cumulative += cogs;

    // Gross Margin
    categories.push(`${date} Gross`);
    base.push(0);
    values.push(cumulative);

    // Overheads
    categories.push(`${date} Overheads`);
    base.push(cumulative);
    values.push(overheads);
    cumulative += overheads;

    // Net Profit
    categories.push(`${date} Net`);
    base.push(0);
    values.push(netProfit);
  });

  // ✅ Destroy old chart (IMPORTANT fix)
  if (window.chart3) {
    window.chart3.destroy();
  }

  // ✅ Render Chart
  const options = {
    chart: {
      type: 'bar',
      stacked: true,
      height: 500
    },
    series: [
      { name: 'Base', data: base },
      { name: 'Value', data: values }
    ],
    colors: [
      'transparent',
      ({ value }) => value >= 0 ? '#00E396' : '#FF4560'
    ],
    legend: {
      show: false
    },
    dataLabels: {
      enabled: true,
      formatter: (val, opts) =>
        opts.seriesIndex === 1 ? val : ''
    },
    xaxis: {
      categories: categories,
      labels: { rotate: -45 }
    },
    yaxis: {
      labels: {
        formatter: val => val
      }
    },
    tooltip: {
      y: {
        formatter: (val, opts) =>
          opts.seriesIndex === 1 ? val : ''
      }
    }
  };

  window.chart3 = new ApexCharts(document.querySelector("#columnChart3"), options);
  window.chart3.render();
}



     // ================================ Column Charts Chart Start ================================ 
    var options = {
        series: [{
            name: 'Net Profit',
            data: [20000, 16000, 14000, 25000, 45000, 18000, 28000, 11000, 26000, 48000, 18000, 22000]
        },{
            name: 'Revenue',
            data: [15000, 18000, 19000, 20000, 35000, 20000, 18000, 13000, 18000, 38000, 14000, 16000]
        }],
        colors: ['#487FFF', '#FF9F29'],
        labels: ['Active', 'New', 'Total'],
        legend: {
            show: false 
        },
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            },
        },
        grid: {
            show: true,
            borderColor: '#D1D5DB',
            strokeDashArray: 4, // Use a number for dashed style
            position: 'back',
        },
        plotOptions: {
            bar: {
                borderRadius: 2,
                columnWidth: '40%',
                borderRadiusApplication: 'end'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        yaxis: {
            categories: ['0', '5000', '10,000', '20,000', '30,000', '50,000', '60,000', '60,000', '70,000', '80,000', '90,000', '100,000'],
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
        fill: {
            opacity: 1,
            width: 18,
        },
    };

    var chart1  = new ApexCharts(document.querySelector("#columnChart1"), options);
    chart1.render();
    var chart4  = new ApexCharts(document.querySelector("#columnChart4"), options);
    chart4.render();

    createChartTwo('columnChart2', '#487FFF', '#FF9F29');
    createChartTwo('columnChart5', '#487FFF', '#FF9F29');


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

                        chart1.updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart1.updateSeries([
                            {
                                name: 'Revenue',
                                data: res.revenue_arr
                            },
                            {
                                name: 'COGS',
                                data: res.cogs_arr
                            }
                        ]);

                        chart2['columnChart2'].updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart2['columnChart2'].updateSeries([
                            {
                                name: 'Gross Margin %',
                                data: report_itemObj['Gross Margin %']
                            },
                            {
                                name: 'Net Profit %',
                                data: report_itemObj['Net Profit %']
                            }
                        ]);

                        chart2['columnChart5'].updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart2['columnChart5'].updateSeries([
                            {
                                name: 'Break Even Sales',
                                data: report_itemObj['Break Even Sales']
                            },
                             {
                                name: 'Revenue',
                                data: report_itemObj['Revenue']
                            }
                        ]);

                        waterfullchart();

                        chart4.updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });


                        chart4.updateSeries([
                            {
                                name: 'Revenue Growth %',
                                data: report_itemObj['Revenue Growth %']
                            },
                            {
                                name: 'COGS Growth %',
                                data: report_itemObj['COGS Growth %']
                            }
                        ]);



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

        chart2[chartId] = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart2[chartId].render();
    }
	
    
});