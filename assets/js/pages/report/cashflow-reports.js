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

        function waterfullchart() {

    const chartData = {
        operating: [],
        investing: [],
        financing: []
    };

    // ✅ Extract data safely with flexible matching
    $('#sheet0 tbody tr').each(function () {

        let label = $(this).find('td:first').text().trim().toLowerCase();

        let values = [];
        $(this).find('td:not(:first)').each(function () {
            let val = $(this).text().replace(/,/g, '').trim();
            values.push(parseFloat(val) || 0);
        });

        if (label.includes('operating activity')) {
            chartData.operating = values;
        }

        if (label.includes('investing activity')) {
            chartData.investing = values;
        }

        if (label.includes('financing activity')) {
            chartData.financing = values;
        }
    });



    // ✅ Extract Dates
    const dates = [];
    $('#sheet0 .row1 td:not(:first)').each(function () {
        dates.push($(this).text().trim());
    });

    let categories = [];
    let base = [];
    let values = [];

    let runningTotal = 0;

    dates.forEach((date, i) => {

        let operating = chartData.operating[i] || 0;
        let investing = chartData.investing[i] || 0;
        let financing = chartData.financing[i] || 0;

        // 🔹 Operating
        categories.push(`${date} Operating`);
        base.push(runningTotal);
        values.push(operating);
        runningTotal += operating;

        // 🔹 Investing
        categories.push(`${date} Investing`);
        base.push(runningTotal);
        values.push(investing);
        runningTotal += investing;

        // 🔹 Financing
        categories.push(`${date} Financing`);
        base.push(runningTotal);
        values.push(financing);
        runningTotal += financing;
    });


    const options = {
        chart: {
            type: 'bar',
            stacked: true,
            height: 500
        },
        series: [
            {
                name: 'Base',
                data: base
            },
            {
                name: 'Value',
                data: values
            }
        ],
        colors: [
            'transparent',
            function ({ value }) {
                return value >= 0 ? '#00E396' : '#FF4560';
            }
        ],
        plotOptions: {
            bar: {
                horizontal: false
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.seriesIndex === 1 ? val : '';
            }
        },
        xaxis: {
            categories: categories,
            labels: {
                rotate: -45
            }
        },
        yaxis: {
            labels: {
                formatter: val => val
            }
        },
        tooltip: {
            y: {
                formatter: function (val, opts) {
                    return opts.seriesIndex === 1 ? val : '';
                }
            }
        },
        legend: {
            show: false
        }
    };

    // ✅ Destroy previous chart if exists
    if (window.chart1) {
        window.chart1.destroy();
    }

    // ✅ Render chart
    window.chart1 = new ApexCharts(
        document.querySelector("#columnChart1"),
        options
    );

    window.chart1.render();
}


// Your input data
const data = {
  "Trade Receivables": [-49, 20],
  "Trade Payables": [52, -19],
  "Inventory": [-11, -39],
  "Equity": [2774, 340],
  "Borrowings": [97, 144],
  "Others": [-90, 23]
};

const months = ["Apr 2026", "May 2026"];

// Transform data
const categories = Object.keys(data);

const series = months.map((month, i) => ({
  name: month,
  data: categories.map(cat => data[cat][i] || 0)
}));

// Chart config
var options2 = {
  chart: {
    type: 'bar',
    height: 500
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '60%'
    }
  },
  series: series,
  xaxis: {
    categories: categories
  },
  dataLabels: {
    enabled: true
  },
  tooltip: {
    y: {
      formatter: function(val) {
        return (val >= 0 ? "Inflow: " : "Outflow: ") + val;
      }
    }
  },
  grid: {
    xaxis: {
      lines: {
        show: true
      }
    }
  }
};


    var chart2 = new ApexCharts(document.querySelector("#columnChart2"), options2);
    chart2.render();

var options3 = {
            series: [{
                name: 'New Patient',
                data: [48, 35, 55, 32, 48, 30, 55, 50, 57]
            }, {
                name: 'Old Patient',
                data: [12, 20, 15, 26, 22, 60, 40, 48, 25]
            }],
            legend: {
                show: false 
            },
            chart: {
                type: 'area',
                width: '100%',
                height: 270,
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
                colors: ['#487FFF', '#FF9F29'], // Use two colors for the lines
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
            colors: ['#487FFF', '#FF9F29'], // Set color for series
            fill: {
                type: 'gradient',
                colors: ['#487FFF', '#FF9F29'], 
                
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: [undefined, '#FF9F2900'], // Apply transparency to both colors
                    inverseColors: false,
                    opacityFrom: [0.4, 0.6], // Starting opacity for both colors
                    opacityTo: [0.3, 0.3], // Ending opacity for both colors
                    stops: [0, 100],
                },
            },
            markers: {
                colors: ['#487FFF', '#FF9F29'], // Use two colors for the markers
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
        };

        var chart3 = new ApexCharts(document.querySelector('#columnChart3'), options3);
        chart3.render();
  

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

                        waterfullchart();


                        const r_data = {
    "Trade Receivables": report_itemObj['Trade Receivables'],
    "Trade Payables": report_itemObj['Trade Payables'],
    "Inventory": report_itemObj['Inventory'],
    "Equity": report_itemObj['Equity'],
    "Borrowings": report_itemObj['Borrowings'],
    "Others": report_itemObj['Others']
};

const r_categories = Object.keys(r_data);

// series = months
const r_series = res.chart_header.map((month, i) => ({
    name: month,
    data: r_categories.map(cat => r_data[cat][i] || 0)
}));

// colors
const colors = generateNiceColors(res.chart_header.length);

// ✅ FINAL FIX
chart2.updateOptions({
    series: r_series,
    colors: colors,
    xaxis: {
        categories: r_categories   // ✅ MUST be items
    }
});


                        chart3.updateOptions({
                            xaxis: {
                                categories: res.chart_header
                            }
                        });

                        chart3.updateSeries([
                            {
                                name: 'Cash at Beginning of Period',
                                data: report_itemObj['Cash at Beginning of Period']
                            },
                            {
                                name: 'Cash at End of Period',
                                data: report_itemObj['Cash at End of Period']
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