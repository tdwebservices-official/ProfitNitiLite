/**
 * Value Formatting Functions
 * Converts numbers to different formats
 */

function formatValue(value, format) {
    if (!value && value !== 0) return value;
    
    const num = parseFloat(value);
    
    switch(format) {
        case 'thousand':
            return (num / 1000).toFixed(1) + 'K';
        case 'lakh':
            return (num / 100000).toFixed(1) + 'L';
        case 'crore':
            return (num / 10000000).toFixed(1) + 'Cr';
        case 'original':
        default:
            return value;
    }
}

function formatSeriesData(series, format) {
    if (!series) return series;
    
    return series.map(s => ({
        ...s,
        data: Array.isArray(s.data) ? s.data.map(v => {
            if (typeof v === 'object' && v.y) {
                return { ...v, y: v.y };
            }
            return v;
        }) : s.data
    }));
}

function getFormattedChartData(originalData, format) {
    if (format === 'original') return originalData;
    

    const formatted = JSON.parse(JSON.stringify(originalData));
    const divisor = getFormatDivisor(format);
   
    if (formatted.series && Array.isArray(formatted.series)) {
        formatted.series = formatted.series.map(s => {
            if (!s.data) return s;
            
            // Handle different data types
            return {
                ...s,
                data: s.data.map(v => {


                    if (v === null || v === undefined) return v;
                    
                    // Waterfall chart data with objects
                    if (typeof v === 'object' && v.y !== undefined) {
                        return {
                            ...v,
                            y: Math.round((v.y / divisor) * 100) / 100
                        };
                    }
                    // Scatter/Bubble chart data - arrays [x, y] or objects with x, y, z
                    else if (Array.isArray(v)) {
                        return [
                            Math.round((v[0] / divisor) * 100) / 100,
                            Math.round((v[1] / divisor) * 100) / 100,
                            v[2] ? Math.round((v[2] / divisor) * 100) / 100 : undefined
                        ].filter(x => x !== undefined);
                    }
                    // Scatter/Bubble with x, y, z object
                    else if (typeof v === 'object' && (v.x !== undefined || v.y !== undefined)) {
                        const result = { ...v };
                        if (result.x) result.x = Math.round((result.x / divisor) * 100) / 100;
                        if (result.y) result.y = Math.round((result.y / divisor) * 100) / 100;
                        if (result.z) result.z = Math.round((result.z / divisor) * 100) / 100;
                        return result;
                    }
                    // Simple numeric value
                    else if (typeof v === 'number') {
                        
                        return Math.round((v / divisor) * 100) / 100;
                    }
                    
                    return v;
                })
            };
        });
    }
    
    return formatted;
}

function getFormatDivisor(format) {
    switch(format) {
        case 'thousand': return 1000;
        case 'lakh': return 100000;
        case 'crore': return 10000000;
        default: return 1;
    }
}

function getFormatLabel(format) {
    switch(format) {
        case 'thousand': return ' (in Thousands)';
        case 'lakh': return ' (in Lakhs)';
        case 'crore': return ' (in Crores)';
        default: return '';
    }
}

/**
 * Universal Chart Renderer Function
 * Supports all ApexCharts types
 * 
 * Usage: renderChart(elementId, chartType, data, customOptions)
 */

function renderChart(elementId, chartType, data, customOptions = {}) {
    const $element = $('#' + elementId);
    
    if (!$element.length) {
        console.error(`Element with ID "${elementId}" not found`);
        return;
    }

    var chartColors = [
      '#667eea', '#764ba2', '#f093fb', '#4facfe',
      '#43e97b', '#fa709a', '#fee140', '#30b0fe',

      '#5a67d8', '#6b46c1', '#ed64a6', '#4299e1',
      '#38a169', '#f56565', '#ecc94b', '#3182ce',

      '#4c51bf', '#553c9a', '#d53f8c', '#2b6cb0',
      '#2f855a', '#e53e3e', '#d69e2e', '#2c5282',

      '#434190', '#44337a', '#b83280', '#2c5282',
      '#276749', '#c53030', '#b7791f', '#2a4365',

      '#3c366b', '#322659', '#a83279', '#1a365d',
      '#22543d', '#9b2c2c', '#a16207', '#1a365d'
    ];

    // Default options for each chart type
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        toolbar: {
            show: true,
            tools: {
                download: true,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: {
            padding: {
               top: 10,
                    right: 10,
                    bottom: 10,
                left: 50
            },
        },
        stroke: {
            show: true,
            curve: 'smooth',
            lineCap: 'butt',
            colors: undefined,
            width: 2,
            dashArray: 0
        },
        colors: chartgenerateColors(8)
    };

    // Chart-specific configurations
    const configs = {
        // 1. Line Chart
        lineChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Line Chart' },
            tooltip: { shared: true, intersect: false },
            plotOptions: { bar: { dataLabels: { position: 'top' } } },
            dataLabels: {
                enabled: true,
                offsetY: -5,
                style: {
                    colors: ['#000000'], // black
                    fontSize: '12px',
                    fontWeight: 'bold'
                },
                formatter: function(val) {
                    return val;
                }
            }
        },

        // 2. Clustered Bar (Grouped Bar)
        clusteredBarChart: {
            chart: { type: 'bar', stacked: false, ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Clustered Bar Chart' },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } }
        },

        // 3. Donut Chart
        donutChart: {
            chart: { type: 'donut', ...chartDefaults },
            series: data.series || [],
            labels: data.labels || [],
            colors: chartgenerateColors((data.series && data.series.length) || (data.labels && data.labels.length) || 5),
            title: { text: 'Donut Chart' },
            plotOptions: { pie: { donut: { size: '65%' } } }
        },

        // 4. Horizontal Bar
        horizontalBarChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Horizontal Bar Chart' },
            plotOptions: { bar: { horizontal: true, borderRadius: 4 } }
        },

        // 5. Bar Chart
        barChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Bar Chart' },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4, dataLabels: { position: 'top' } } },
            dataLabels: {
                enabled: true,
                offsetY: -20,                
                style: {
                    colors: ['#000000'], // black
                    fontSize: '12px',
                    fontWeight: 'bold'
                },
                formatter: function(val) {
                    return val;
                }
            }
        },

        // 6. Stacked Bar
        stackedBarChart: {
            chart: { type: 'bar', stacked: true, ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Stacked Bar Chart' },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } }
        },

        // 7. Dual Line
        dualLineChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Dual Line Chart' },
            stroke: { curve: 'smooth', width: 2 }
        },

        // 8. Area Chart
        areaChart: {
            chart: { type: 'area', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Area Chart' },
            fill: { opacity: 0.6 },
            stroke: { curve: 'smooth', width: 2 }
        },

        // 9. Line Forecast
        lineForecastChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Line Forecast Chart' },
            stroke: { curve: 'smooth', width: [2, 2], dashArray: [0, 5] },
            fill: { opacity: [0.85, 0] }
        },

        // 10. Gauge Chart
        gaugeChart: {
            chart: { type: 'radialBar', ...chartDefaults },
            series: data.series || [],
            labels: data.labels || [],
            title: { text: 'Gauge Chart' },
            colors: chartgenerateColors((data.series && data.series.length) || (data.labels && data.labels.length) || 1),
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: { background: '#f2f2f2' },
                    dataLabels: { name: { fontSize: '14px' }, value: { fontSize: '16px' } }
                }
            }
        },

        // 11. Column Chart
        columnChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Column Chart' },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } }
        },

        // 12. Pareto Bar+Line
        paretoChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: [
                { title: { text: 'Issues' } },
                { opposite: true, title: { text: 'Cumulative %' }, max: 100 }
            ],
            title: { text: 'Pareto Chart' },
            plotOptions: { bar: { columnWidth: '50%' } },
            stroke: { width: [2, 2], curve: 'smooth' }
        },

        // 13. Scatter Plot
        scatterChart: {
            chart: { type: 'scatter', ...chartDefaults },
            series: data.series || [],
            xaxis: data.xaxis || { type: 'numeric' },
            yaxis: { title: { text: 'Y-Axis' } },
            title: { text: 'Scatter Plot' }
        },

        // 14. Waterfall Chart
        waterfallChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Waterfall Chart' },
            plotOptions: {
                bar: { horizontal: false, columnWidth: '65%', borderRadius: 4, dataLabels: { enabled: true } }
            },
            dataLabels: { enabled: true, formatter: function(val) { return val; } }
        },

        // 15. Pareto Bar+Line (Alt)
        paretoAltChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: [
                { title: { text: 'Frequency' } },
                { opposite: true, title: { text: '% Total' }, max: 100 }
            ],
            title: { text: 'Pareto Chart - Alt' },
            plotOptions: { bar: { columnWidth: '50%' } },
            stroke: { width: [2, 2] }
        },

        // 16. Heatmap
        heatmapChart: {
            chart: { type: 'heatmap', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Heatmap' },
            plotOptions: {
                heatmap: {
                    shadeIntensity: 0.5,
                    radius: 0,
                    useFillColorAsStroke: true
                }
            }
        },

        // 17. Bubble Chart
        bubbleChart: {
            chart: { type: 'bubble', ...chartDefaults },
            series: data.series || [],
            xaxis: { type: 'numeric' },
            yaxis: { type: 'numeric' },
            title: { text: 'Bubble Chart' }
        },

        // 18. KPI Cards (using bar)
        kpiCardsChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Score' } },
            title: { text: 'KPI Cards' },
            plotOptions: { bar: { horizontal: false, columnWidth: '65%', borderRadius: 8, dataLabels: { enabled: true } } },
            dataLabels: { enabled: true, formatter: function(val) { return Math.round(val) + '%'; } }
        },

        // 19. Waterfall (Alt)
        waterfallAltChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Waterfall - Alt' },
            plotOptions: {
                bar: { horizontal: false, columnWidth: '65%', borderRadius: 4, dataLabels: { enabled: true } }
            },
            dataLabels: { enabled: true }
        },

        // 20. Line Scenario
        lineScenarioChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Line Scenario' },
            stroke: { curve: 'smooth', width: 2 },
            fill: { opacity: 0.3 }
        },

        // 21. Scatter Plot (Alt)
        scatterAltChart: {
            chart: { type: 'scatter', ...chartDefaults },
            series: data.series || [],
            xaxis: data.xaxis || { type: 'numeric' },
            yaxis: { title: { text: 'Y-Axis' } },
            title: { text: 'Scatter Plot - Alt' }
        },

        // 22. Score Bar
        scoreBarChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Score' }, max: 100 },
            title: { text: 'Score Bar' },
            plotOptions: { bar: { horizontal: false, columnWidth: '60%', borderRadius: 4, dataLabels: { enabled: true } } },
            dataLabels: { enabled: true, formatter: function(val) { return Math.round(val); } }
        },

        // 23. Waterfall Score
        waterfallScoreChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Waterfall Score' },
            plotOptions: {
                bar: { horizontal: false, columnWidth: '65%', borderRadius: 4, dataLabels: { enabled: true } }
            },
            dataLabels: { enabled: true }
        },

        // 24. Gauge (Alt)
        gaugeAltChart: {
            chart: { type: 'radialBar', ...chartDefaults },
            series: data.series || [],
            labels: data.labels || [],
            title: { text: 'Gauge - Alt' },
            colors: chartgenerateColors((data.series && data.series.length) || (data.labels && data.labels.length) || 1),
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    track: { background: '#f2f2f2' },
                    dataLabels: {
                        name: { fontSize: '14px' },
                        value: { fontSize: '16px', formatter: function(val) { return val + '%'; } }
                    }
                }
            }
        }
    };

    // Get the config for this chart type
    let config = configs[chartType];

    if (!config) {
        console.error(`Chart type "${chartType}" not recognized`);
        return;
    }

    // Merge custom options
    config = $.extend(true, {}, config, customOptions);

    // Clear the element
    $element.empty();

    // Render the chart
    try {
        const chart = new ApexCharts($element[0], config);
        chart.render();
        
        // Store reference for later updates
        $element.data('apexChart', chart);
       // console.log(`Chart "${chartType}" rendered successfully in #${elementId}`);
        
        return chart;
    } catch (error) {
        console.error(`Error rendering chart "${chartType}":`, error);
        $element.html(`<p style="color: red; padding: 20px;">Error rendering chart: ${error.message}</p>`);
    }
}

function chartgenerateColors(count) {   
    const baseColors = [
        '#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', 
        '#fa709a', '#fee140', '#30b0fe', '#ff6b6b', '#4ecdc4',
        '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe', '#fd79a8',
        '#fdcb6e', '#6c7aa8', '#0984e3', '#eb4d4b', '#95afc0'
    ];
    
    // If count is less than or equal to base colors, return them
    if (count <= baseColors.length) {
        return baseColors.slice(0, count);
    }
    
    // Generate additional colors using HSL for better distribution
    const colors = [...baseColors];
    for (let i = baseColors.length; i < count; i++) {
        const hue = (i * 360 / count) % 360;
        const saturation = 70 + (i % 3) * 5;
        const lightness = 55 + (i % 2) * 5;
        colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
    }
    
    return colors;
}

/**
 * Update existing chart with new data
 * Usage: updateChart(elementId, newData)
 */
function updateChart(elementId, newData) {
    const $element = $('#' + elementId);
    const chart = $element.data('apexChart');

    if (!chart) {
        console.error(`Chart not found in element #${elementId}`);
        return;
    }

    try {
        if (newData.series) {
            chart.updateSeries(newData.series);
        }
        
        if (newData.categories) {
            chart.updateOptions({
                xaxis: { categories: newData.categories }
            });
        }
        //console.log(`Chart #${elementId} updated successfully`);
    } catch (error) {
        console.error(`Error updating chart #${elementId}:`, error);
    }
}

/**
 * Destroy a chart
 * Usage: destroyChart(elementId)
 */
function destroyChart(elementId) {
    const $element = $('#' + elementId);
    const chart = $element.data('apexChart');

    if (chart) {
        chart.destroy();
        $element.removeData('apexChart');
        console.log(`Chart #${elementId} destroyed`);
    }
}



/**
 * Update chart with different format
 * Usage: updateChartFormat(chartId, format)
 */
function updateChartFormat(chartId, format) {
    const $element = $('#' + chartId);
    const chart = $element.data('apexChart');
    
    if (!chart || !chartOriginalData[chartId]) {
        console.error(`Chart data not found for #${chartId}`);
        return;
    }
    
    try {
        const originalData = chartOriginalData[chartId];

        const formattedData = getFormattedChartData(originalData, format);
       
        // Update series
        if (formattedData.series) {          
            chart.updateSeries(formattedData.series);
        }
        
        // Update title with format label
        let baseTitle = chart.opts.title.text || 'Chart';
        
        // Remove previous format label
        baseTitle = baseTitle.replace(/ \(in (Thousands|Lakhs|Crores)\)/, '');
        
        const newTitle = baseTitle + getFormatLabel(format);
        
        // Get Y-axis label based on format
        const yAxisLabel = getYAxisLabel(format);
        
        // Update chart options with new title and y-axis label
        const updateOptions = {
            title: { text: newTitle }
        };
        
        // Update Y-axis if chart has yaxis
        if (chart.opts.yaxis) {
            if (Array.isArray(chart.opts.yaxis)) {
                updateOptions.yaxis = chart.opts.yaxis.map((axis, idx) => ({
                    ...axis,
                    title: { text: (idx === 0 ? yAxisLabel : axis.title?.text) || '' }
                }));
            } else {
                updateOptions.yaxis = {
                    ...chart.opts.yaxis,
                    title: { text: yAxisLabel }
                };
            }
        }
        
        chart.updateOptions(updateOptions);
        
        chartCurrentFormat[chartId] = format;
        //console.log(`Chart #${chartId} updated with format: ${format}`);
    } catch (error) {
        console.error(`Error updating chart format for #${chartId}:`, error);
    }
}

function getYAxisLabel(format) {
    switch(format) {
        case 'thousand': return 'Values (K)';
        case 'lakh': return 'Values (L)';
        case 'crore': return 'Values (Cr)';
        default: return 'Values';
    }
}

