var chart3 = null;
var GaugeCharts = {};
jQuery(document).ready(function() {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



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
                        
                         colorizeRowsByLabel(".balance-sheet-table table", [
                          "AP Days"
                      ]);

                        oppcolorizeRowsByLabel(".balance-sheet-table table", [
                          "A/R Days"
                      ]);

                        setDataAttributeTB();

                        setTimeout(function() {
                            // Trigger once for default selection
                            $("#figureType").trigger("change");
                        },150);

                        

                        // var acrDays = arraySum(report_itemObj['Accounts Receivable Days'][report_itemObj['Accounts Receivable Days'].length-1]);
                        // var invDays = arraySum(report_itemObj['Inventory Days']);
                        // var acpDays = arraySum(report_itemObj['Accounts Payable Days']);

                        var acrDays = report_itemObj['A/R Days'][report_itemObj['A/R Days'].length-1];
                        var invDays = report_itemObj['Inventory Days'][report_itemObj['Inventory Days'].length-1];
                        var acpDays = report_itemObj['AP Days'][report_itemObj['AP Days'].length-1];

                        // var quick_ratio = arraySum(report_itemObj['Quick Ratio']);
                        // var current_ratio = arraySum(report_itemObj['Current Ratio']);

                        var quick_ratio = report_itemObj['Quick Ratio'][report_itemObj['Quick Ratio'].length-1];
                        var current_ratio = report_itemObj['Current Ratio'][report_itemObj['Current Ratio'].length-1];


                        GaugeCharts['#currentRatio'].updateSeries([(current_ratio / 2) * 100]);

                        //#008000
                        var current_ratio_color = (current_ratio<2) ? "#ff0000" : '#008000'
                        var quick_ratio_color = (quick_ratio<1) ? "#ff0000" : '#008000';

                        GaugeCharts['#currentRatio'].updateOptions({
                            fill: {
                                colors: [current_ratio_color]
                            },
                            customValue: current_ratio
                        });

                        GaugeCharts['#quickRatio'].updateSeries([(quick_ratio / 2) * 100]);

                        GaugeCharts['#quickRatio'].updateOptions({
                            fill: {
                                colors: [quick_ratio_color]
                            },
                            customValue: quick_ratio
                        });


                        jQuery(".ar-days .number").text(parseFloat(acrDays).toFixed(2));
                        jQuery(".inv-days .number").text(parseFloat(invDays).toFixed(2));
                        jQuery(".ap-days .number").text(parseFloat(acpDays).toFixed(2));
                        jQuery(".cc-days .number").text(parseFloat((acrDays+invDays) - acpDays).toFixed(2));

                       

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
                formatter: function (val, opts) {
                  return opts.config.customValue.toFixed(2);
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