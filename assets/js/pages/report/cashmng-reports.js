var chart3 = null;
var GaugeCharts = {};
jQuery(document).ready(function() {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

 
     createChartTwo('columnChart3', '#E30A0A', '#FF9F29', '#144BD6', '#45B369');
    


     var options4 = {
        series: [{
            name: 'Ticket',
            data: [6200, 5200, 4200, 3200]
        }],
        chart: {
            type: 'bar',
            height: 270,
            toolbar: {
                show: false
            },
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                distributed: true, // Enables individual bar styling
                barHeight: '22px'
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: {
            show: true,
            borderColor: '#ddd',
            strokeDashArray: 0,
            position: 'back',
            xaxis: {
              lines: {
                show: false
              }
            },   
            yaxis: {
              lines: {
                show: false
            }
          },  
        },
        xaxis: {
            categories: ['High', 'Medium', 'Low', 'Urgent'],
            labels: {
              formatter: function (val) {
                  return ((val / 1000).toFixed(0) > 0) ? (val / 1000).toFixed(0) + 'k' : val;
              }
            }
        },
        legend: {
            show: false
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.5,
                gradientToColors: ['#144bd6'],
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        colors: [
            '#144bd6',
        ]
    };

    var chart4 = new ApexCharts(document.querySelector("#columnChart4"), options4);
    chart4.render();


     var options5 = {
      series: [
          {
            name: 'This Day',
            data: [18, 25, 20, 35, 25, 55, 45, 50, 40],
          },
      ],
      chart: {
          type: 'area',
          width: '100%',
          height: 360,
          sparkline: {
            enabled: false // Remove whitespace
          },
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
          width: 4,
          colors: ['#487fff'],
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
              top: -30,
              right: 0,
              bottom: -10,
              left: 0
          },  
      },
      colors: ['#487fff'], // Set color for series
      fill: {
          type: 'gradient',
          colors: ['#487fff'], // Set the starting color (top color) here
          gradient: {
              shade: 'light', // Gradient shading type
              type: 'vertical',  // Gradient direction (vertical)
              shadeIntensity: 0.5, // Intensity of the gradient shading
              gradientToColors: [`${'#487fff'}00`], // Bottom gradient color (with transparency)
              inverseColors: false, // Do not invert colors
              opacityFrom: .6, // Starting opacity
              opacityTo: 0.3,  // Ending opacity
              stops: [0, 100],
          },
      },
      // Customize the circle marker color on hover
      markers: {
        colors: ['#487fff'],
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
          categories: [`Jan`, `Feb`, `Mar`, `Apr`, `May`, `Jun`, `Jul`, `Aug`, `Sep`, `Oct`, `Nov`, `Dec`],
          tooltip: {
              enabled: false,
          },
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
          },
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
      
    };

    var chart5 = new ApexCharts(document.querySelector('#columnChart5'), options5);
    chart5.render();


    $('#cashmng-report-form').validate({
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
            jQuery('#cashmng-report-form button[type="submit"]').siblings('.spinner-border').show();
            $('#cashmng-report-form').ajaxSubmit({
            url: site_url+'/api/getCashMngReport',
                success: function(res) {
                    jQuery('#cashmng-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){

                        jQuery('.balance-sheet-table-box').removeClass('d-none');
                        jQuery('.balance-sheet-table').html(res.table_html);

                        var report_itemObj = get_report_item_list();

                        chart3.updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });
                        // var acrDays = arraySum(report_itemObj['Accounts Receivable Days'][report_itemObj['Accounts Receivable Days'].length-1]);
                        // var invDays = arraySum(report_itemObj['Inventory Days']);
                        // var acpDays = arraySum(report_itemObj['Accounts Payable Days']);

                        var acrDays = report_itemObj['Accounts Receivable Days'][report_itemObj['Accounts Receivable Days'].length-1];
                        var invDays = report_itemObj['Inventory Days'][report_itemObj['Inventory Days'].length-1];
                        var acpDays = report_itemObj['Accounts Payable Days'][report_itemObj['Accounts Payable Days'].length-1];

                        // var quick_ratio = arraySum(report_itemObj['Quick Ratio']);
                        // var current_ratio = arraySum(report_itemObj['Current Ratio']);

                        var quick_ratio = report_itemObj['Quick Ratio'][report_itemObj['Quick Ratio'].length-1];
                        var current_ratio = report_itemObj['Current Ratio'][report_itemObj['Current Ratio'].length-1];


                        GaugeCharts['#currentRatio'].updateSeries([current_ratio]);
                        GaugeCharts['#quickRatio'].updateSeries([quick_ratio]);

                        jQuery(".ar-days .number").text(parseFloat(acrDays).toFixed(2));
                        jQuery(".inv-days .number").text(parseFloat(invDays).toFixed(2));
                        jQuery(".ap-days .number").text(parseFloat(acpDays).toFixed(2));
                        jQuery(".cc-days .number").text(parseFloat((acrDays+invDays) - acpDays).toFixed(2));

                        chart3.updateSeries([
                            {
                                name: 'A/R Days',
                                data: report_itemObj['Accounts Receivable Days']
                            },
                            {
                                name: 'Inventory Days',
                                data: report_itemObj['Inventory Days']
                            },
                            {
                                name: 'A/P Days',
                                data: report_itemObj['Accounts Payable Days']
                            },
                            {
                                name: 'W/C Days',
                                data: report_itemObj['Working Capital Days']
                            }
                        ]);
                        chart4.updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart4.updateSeries([
                            {
                                name: 'Marginal Cash Flow',
                                data: report_itemObj['Marginal Cash Flow']
                            }
                        ]);

                        chart5.updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart5.updateSeries([
                            {
                                name: 'Working Capital per ₹100',
                                data: report_itemObj['Working Capital per ₹100']
                            }
                        ]);

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
                jQuery('#cashmng-report-form button[type="submit"]').siblings('.spinner-border').hide();
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
         jQuery('#cashmng-report-form .btn-download-report').siblings('.spinner-border').show();
jQuery('.report_type').val('download');

        $('#cashmng-report-form').ajaxSubmit({
            url: site_url+'/api/getCashMngReport',
            success: function(res) {
                jQuery('#cashmng-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#cashmng-report-form .btn-download-report').siblings('.spinner-border').hide();
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
	
 function createChartTwo(chartId, color1, color2, color3, color4) {

    var options = {
    series: [{
        name: "This month",
        data: [0, 48, 20, 24, 6, 33, 30, 48, 35, 18, 20, 5]
    },{
        name: "This Last",
        data: [0, 99, 20, 24, 6, 66, 30, 48, 35, 18, 20, 5]
    }],
    chart: {
        height: 300,
        type: 'line',
        toolbar: {
            show: false
        },
        zoom: {
            enabled: false
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        colors: [color1, color2, color3, color4],
        width: 4
    },
    markers: {
        size: 0,
        strokeWidth: 3,
        hover: {
            size: 8
        }
    },
    tooltip: {
        enabled: true,
        x: {
            show: true,
        },
        y: {
            show: false,
        },
        z: {
            show: false,
        }
    },
    grid: {
        row: {
            colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
        },
        borderColor: '#D1D5DB',
        strokeDashArray: 3,
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
    xaxis: {
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
        },
        axisBorder: {
            show: false
        },
       
    }
};

chart3 = new ApexCharts(document.querySelector("#columnChart3"), options);
chart3.render();
}


function createGauge(el, value, color, targetLabel) {
    const options = {
      chart: {
        type: 'radialBar',
        height: 300
      },
      series: [value],
      plotOptions: {
        radialBar: {
          startAngle: -90,
          endAngle: 90,
          hollow: {
            size: '60%'
          },
          track: {
            background: '#eee',
            strokeWidth: '100%'
          },
          dataLabels: {
            name: {
              show: true,
              offsetY: 30,
              fontSize: '12px'
            },
            value: {
              offsetY: -10,
              fontSize: '28px',
              formatter: function (val) {
                return val.toFixed(2);
              }
            }
          }
        }
      },
      fill: {
        colors: [color]
      },
      labels: [targetLabel]
    };
    GaugeCharts[el] = new ApexCharts(document.querySelector(el), options);
    GaugeCharts[el].render();
  }

  createGauge("#currentRatio", 91, "#45B369", "Current Ratio");
  createGauge("#quickRatio", 74, "#144BD6", "Quick Ratio");

function arraySum( arr = [] ){

    if( arr === undefined || arr === '' || arr === null || arr === 0 ){
        return 0;
    }
    let sum = 0;
    for (let i = 0; i < arr.length; i++) {
        sum += arr[i];
    }

    return sum;
}


    
});