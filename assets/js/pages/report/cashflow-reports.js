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

    // Extract data
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

    // Extract Dates
    const dates = [];
    $('#sheet0 .row1 td:not(:first)').each(function () {
        dates.push($(this).text().trim());
    });

    const options = {
        chart: {
            type: 'bar',
            stacked: true,
            height: 500
        },
        series: [
            {
                name: 'Operating',
                data: chartData.operating
            },
            {
                name: 'Investing',
                data: chartData.investing
            },
            {
                name: 'Financing',
                data: chartData.financing
            }
        ],
        colors: ['#00E396', '#FEB019', '#FF4560'],
        plotOptions: {
            bar: {
                horizontal: false
            }
        },
        dataLabels: {
            enabled: true
        },
        xaxis: {
            categories: dates,
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
                formatter: val => val
            }
        }
    };

    // Destroy previous chart
    if (window.chart1) {
        window.chart1.destroy();
    }

    // Render
    window.chart1 = new ApexCharts(
        document.querySelector("#columnChart1"),
        options
    );

    window.chart1.render();
}

    function ddwaterfullchart() {

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

                         setDataAttributeTB();

                        setTimeout(function() {
                            // Trigger once for default selection
                            $("#figureType").trigger("change");
                        },150);
                        
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