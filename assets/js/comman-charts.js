/**
 * Value Formatting Functions
 * Converts numbers to different formats
 */

function formatValue(value, format) {
    if (!value && value !== 0) return value;

    const num = parseFloat(value);

    switch (format) {
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

            return {
                ...s,
                data: s.data.map(v => {
                    if (v === null || v === undefined) return v;

                    if (typeof v === 'object' && v.y !== undefined) {
                        return {
                            ...v,
                            y: Math.round((v.y / divisor) * 100) / 100
                        };
                    } else if (Array.isArray(v)) {
                        return [
                            Math.round((v[0] / divisor) * 100) / 100,
                            Math.round((v[1] / divisor) * 100) / 100,
                            v[2] ? Math.round((v[2] / divisor) * 100) / 100 : undefined
                        ].filter(x => x !== undefined);
                    } else if (typeof v === 'object' && (v.x !== undefined || v.y !== undefined)) {
                        const result = { ...v };
                        if (result.x) result.x = Math.round((result.x / divisor) * 100) / 100;
                        if (result.y) result.y = Math.round((result.y / divisor) * 100) / 100;
                        if (result.z) result.z = Math.round((result.z / divisor) * 100) / 100;
                        return result;
                    } else if (typeof v === 'number') {
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
    switch (format) {
        case 'thousand': return 1000;
        case 'lakh': return 100000;
        case 'crore': return 10000000;
        default: return 1;
    }
}

function getFormatLabel(format) {
    switch (format) {
        case 'thousand': return ' (in Thousands)';
        case 'lakh': return ' (in Lakhs)';
        case 'crore': return ' (in Crores)';
        default: return '';
    }
}

function getYAxisLabel(format) {
    switch (format) {
        case 'thousand': return 'Values (K)';
        case 'lakh': return 'Values (L)';
        case 'crore': return 'Values (Cr)';
        default: return 'Values';
    }
}

/**
 * Shared vertical data-label config for bar/column charts.
 * Native labels are hidden; custom SVG labels are drawn centered on each bar.
 */
function getVerticalBarDataLabels(customFormatter) {
    return {
        enabled: false,
        formatter: customFormatter || function(val) {
            if (val === null || val === undefined || val === '') return '';
            return val;
        }
    };
}

function getVerticalBarPlotOptions(overrides = {}) {
    return $.extend(true, {
        horizontal: false,
        columnWidth: '55%',
        borderRadius: 4,
        dataLabels: {
            position: 'center',
            orientation: 'horizontal',
            hideOverflowingLabels: false
        }
    }, overrides);
}

const VERTICAL_BAR_LABEL_CHART_TYPES = new Set([
    'clusteredBarChart',
    'barChart',
    'stackedBarChart',
    'columnChart',
    'horizontalBarChart',
    'waterfallChart',
    'waterfallAltChart',
    'waterfallScoreChart',
    'kpiCardsChart',
    'scoreBarChart',
    'paretoChart',
    'paretoAltChart'
]);

function wrapChartEvent(existingFn, wrapperFn) {
    return function(chartContext, config) {
        wrapperFn(chartContext, config);
        if (typeof existingFn === 'function') {
            existingFn(chartContext, config);
        }
    };
}

function getBarLabelText(chartContext, seriesIndex, dataPointIndex, value) {
    const formatter = chartContext.w.config.dataLabels && chartContext.w.config.dataLabels.formatter;

    if (typeof formatter === 'function') {
        return formatter(value, {
            seriesIndex: seriesIndex,
            dataPointIndex: dataPointIndex,
            w: chartContext.w
        });
    }

    if (value === null || value === undefined || value === '') return '';
    return value;
}

function getBarFontSize(text, box) {
    const barLength = Math.max(box.width, box.height);
    if (!text || barLength <= 0) return 11;
    return Math.min(11, Math.max(7, Math.floor(barLength / (String(text).length * 0.55))));
}

function centerVerticalBarDataLabels(chartContext) {
    if (!chartContext || !chartContext.el || !chartContext.w) return;

    const applyLabels = function() {
        const el = chartContext.el;
        const w = chartContext.w;
        const chartInner = el.querySelector('.apexcharts-inner');

        if (!chartInner) return;

        el.querySelectorAll('.apexcharts-datalabels').forEach(function(group) {
            group.style.display = 'none';
        });

        el.querySelectorAll('.custom-bar-labels-layer').forEach(function(layer) {
            layer.remove();
        });

        const labelLayer = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        labelLayer.setAttribute('class', 'custom-bar-labels-layer');
        chartInner.appendChild(labelLayer);

        const bars = el.querySelectorAll('path.apexcharts-bar-area');

        bars.forEach(function(bar) {
            if (typeof bar.getBBox !== 'function') return;

            const seriesGroup = bar.closest('.apexcharts-series');
            if (!seriesGroup) return;

            let seriesIndex = seriesGroup.getAttribute('data:realIndex');
            if (seriesIndex === null || seriesIndex === '') {
                const rel = parseInt(seriesGroup.getAttribute('rel') || '1', 10);
                seriesIndex = String(rel - 1);
            }
            seriesIndex = parseInt(seriesIndex, 10);

            const dataPointIndex = parseInt(bar.getAttribute('j') || '0', 10);
            const value = w.globals.series[seriesIndex] && w.globals.series[seriesIndex][dataPointIndex];

            if (value === null || value === undefined || value === '') return;

            const labelText = getBarLabelText(chartContext, seriesIndex, dataPointIndex, value);
            if (labelText === null || labelText === undefined || labelText === '') return;

            const box = bar.getBBox();
            if (box.width <= 0 && box.height <= 0) return;

            const cx = box.x + (box.width / 2);
            const cy = box.y + (box.height / 2);
            const fontSize = getBarFontSize(labelText, box);

            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('class', 'custom-bar-label');
            text.setAttribute('x', cx);
            text.setAttribute('y', cy);
            text.setAttribute('fill', '#000000');
            text.setAttribute('font-size', fontSize + 'px');
            text.setAttribute('font-weight', 'bold');
            text.setAttribute('font-family', 'Helvetica, Arial, sans-serif');
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('dominant-baseline', 'central');
            text.setAttribute('transform', 'rotate(-90 ' + cx + ' ' + cy + ')');
            text.textContent = labelText;

            labelLayer.appendChild(text);
        });
    };

    window.requestAnimationFrame(function() {
        window.requestAnimationFrame(applyLabels);
    });
}

function attachCenteredVerticalBarLabelEvents(config) {
    config.chart = config.chart || {};
    const existingEvents = config.chart.events || {};

    config.chart.events = Object.assign({}, existingEvents, {
        mounted: wrapChartEvent(existingEvents.mounted, centerVerticalBarDataLabels),
        updated: wrapChartEvent(existingEvents.updated, centerVerticalBarDataLabels),
        resized: wrapChartEvent(existingEvents.resized, centerVerticalBarDataLabels),
        animationEnd: wrapChartEvent(existingEvents.animationEnd, centerVerticalBarDataLabels)
    });

    return config;
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
            }
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

    const configs = {
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
                    colors: ['#000000'],
                    fontSize: '12px',
                    fontWeight: 'bold'
                },
                formatter: function(val) {
                    return val;
                }
            }
        },

        clusteredBarChart: {
            chart: { type: 'bar', stacked: false, ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Clustered Bar Chart' },
            plotOptions: { bar: getVerticalBarPlotOptions() },
            dataLabels: getVerticalBarDataLabels()
        },

        donutChart: {
            chart: { type: 'donut', ...chartDefaults },
            series: data.series || [],
            labels: data.labels || [],
            colors: chartgenerateColors((data.series && data.series.length) || (data.labels && data.labels.length) || 5),
            title: { text: 'Donut Chart' },
            plotOptions: { pie: { donut: { size: '65%' } } }
        },

        horizontalBarChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Horizontal Bar Chart' },
            plotOptions: {
                bar: getVerticalBarPlotOptions({
                    horizontal: true,
                    dataLabels: {
                        position: 'center',
                        orientation: 'horizontal',
                        hideOverflowingLabels: false
                    }
                })
            },
            dataLabels: getVerticalBarDataLabels()
        },

        barChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Bar Chart' },
            plotOptions: { bar: getVerticalBarPlotOptions() },
            dataLabels: getVerticalBarDataLabels()
        },

        stackedBarChart: {
            chart: { type: 'bar', stacked: true, ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Stacked Bar Chart' },
            plotOptions: { bar: getVerticalBarPlotOptions() },
            dataLabels: getVerticalBarDataLabels()
        },

        dualLineChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Dual Line Chart' },
            stroke: { curve: 'smooth', width: 2 }
        },

        areaChart: {
            chart: { type: 'area', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Area Chart' },
            fill: { opacity: 0.6 },
            stroke: { curve: 'smooth', width: 2 }
        },

        lineForecastChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Line Forecast Chart' },
            stroke: { curve: 'smooth', width: [2, 2], dashArray: [0, 5] },
            fill: { opacity: [0.85, 0] }
        },

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

        columnChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Column Chart' },
            plotOptions: { bar: getVerticalBarPlotOptions() },
            dataLabels: getVerticalBarDataLabels()
        },

        paretoChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: [
                { title: { text: 'Issues' } },
                { opposite: true, title: { text: 'Cumulative %' }, max: 100 }
            ],
            title: { text: 'Pareto Chart' },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                    dataLabels: {
                        position: 'center',
                        orientation: 'horizontal',
                        hideOverflowingLabels: false
                    }
                }
            },
            dataLabels: getVerticalBarDataLabels()
        },

        scatterChart: {
            chart: { type: 'scatter', ...chartDefaults },
            series: data.series || [],
            xaxis: data.xaxis || { type: 'numeric' },
            yaxis: { title: { text: 'Y-Axis' } },
            title: { text: 'Scatter Plot' }
        },

        waterfallChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Waterfall Chart' },
            plotOptions: {
                bar: getVerticalBarPlotOptions({ columnWidth: '65%', dataLabels: { enabled: true } })
            },
            dataLabels: getVerticalBarDataLabels()
        },

        paretoAltChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: [
                { title: { text: 'Frequency' } },
                { opposite: true, title: { text: '% Total' }, max: 100 }
            ],
            title: { text: 'Pareto Chart - Alt' },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                    dataLabels: {
                        position: 'center',
                        orientation: 'horizontal',
                        hideOverflowingLabels: false
                    }
                }
            },
            dataLabels: getVerticalBarDataLabels(),
            stroke: { width: [2, 2] }
        },

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

        bubbleChart: {
            chart: { type: 'bubble', ...chartDefaults },
            series: data.series || [],
            xaxis: { type: 'numeric' },
            yaxis: { type: 'numeric' },
            title: { text: 'Bubble Chart' }
        },

        kpiCardsChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Score' } },
            title: { text: 'KPI Cards' },
            plotOptions: {
                bar: getVerticalBarPlotOptions({
                    columnWidth: '65%',
                    borderRadius: 8,
                    dataLabels: { enabled: true }
                })
            },
            dataLabels: getVerticalBarDataLabels(function(val) {
                return Math.round(val) + '%';
            })
        },

        waterfallAltChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Waterfall - Alt' },
            plotOptions: {
                bar: getVerticalBarPlotOptions({ columnWidth: '65%', dataLabels: { enabled: true } })
            },
            dataLabels: getVerticalBarDataLabels()
        },

        lineScenarioChart: {
            chart: { type: 'line', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Values' } },
            title: { text: 'Line Scenario' },
            stroke: { curve: 'smooth', width: 2 },
            fill: { opacity: 0.3 }
        },

        scatterAltChart: {
            chart: { type: 'scatter', ...chartDefaults },
            series: data.series || [],
            xaxis: data.xaxis || { type: 'numeric' },
            yaxis: { title: { text: 'Y-Axis' } },
            title: { text: 'Scatter Plot - Alt' }
        },

        scoreBarChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            xaxis: { categories: data.categories || [] },
            yaxis: { title: { text: 'Score' }, max: 100 },
            title: { text: 'Score Bar' },
            plotOptions: {
                bar: getVerticalBarPlotOptions({
                    columnWidth: '60%',
                    dataLabels: { enabled: true }
                })
            },
            dataLabels: getVerticalBarDataLabels(function(val) {
                return Math.round(val);
            })
        },

        waterfallScoreChart: {
            chart: { type: 'bar', ...chartDefaults },
            series: data.series || [],
            title: { text: 'Waterfall Score' },
            plotOptions: {
                bar: getVerticalBarPlotOptions({ columnWidth: '65%', dataLabels: { enabled: true } })
            },
            dataLabels: getVerticalBarDataLabels()
        },

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

    let config = configs[chartType];

    if (!config) {
        console.error(`Chart type "${chartType}" not recognized`);
        return;
    }

    config = $.extend(true, {}, config, customOptions);

    if (VERTICAL_BAR_LABEL_CHART_TYPES.has(chartType)) {
        attachCenteredVerticalBarLabelEvents(config);
    }

    $element.empty();

    try {
        const chart = new ApexCharts($element[0], config);
        const renderPromise = chart.render();

        $element.data('apexChart', chart);

        if (VERTICAL_BAR_LABEL_CHART_TYPES.has(chartType)) {
            if (renderPromise && typeof renderPromise.then === 'function') {
                renderPromise.then(function() {
                    centerVerticalBarDataLabels(chart);
                });
            } else {
                setTimeout(function() {
                    centerVerticalBarDataLabels(chart);
                }, 300);
            }
        }

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

    if (count <= baseColors.length) {
        return baseColors.slice(0, count);
    }

    const colors = [...baseColors];
    for (let i = baseColors.length; i < count; i++) {
        const hue = (i * 360 / count) % 360;
        const saturation = 70 + (i % 3) * 5;
        const lightness = 55 + (i % 2) * 5;
        colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
    }

    return colors;
}

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
    } catch (error) {
        console.error(`Error updating chart #${elementId}:`, error);
    }
}

function destroyChart(elementId) {
    const $element = $('#' + elementId);
    const chart = $element.data('apexChart');

    if (chart) {
        chart.destroy();
        $element.removeData('apexChart');
        console.log(`Chart #${elementId} destroyed`);
    }
}

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

        if (formattedData.series) {
            chart.updateSeries(formattedData.series);
        }

        let baseTitle = chart.opts.title.text || 'Chart';
        baseTitle = baseTitle.replace(/ \(in (Thousands|Lakhs|Crores)\)/, '');

        const newTitle = baseTitle + getFormatLabel(format);
        const yAxisLabel = getYAxisLabel(format);

        const updateOptions = {
            title: { text: newTitle }
        };

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
    } catch (error) {
        console.error(`Error updating chart format for #${chartId}:`, error);
    }
}
