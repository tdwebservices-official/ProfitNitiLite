    // Declare chartInstance outside to persist across clicks
var globalChartInstance = null;

// Store original data for each chart
const chartOriginalData = {};

// Store current format for each chart
const chartCurrentFormat = {};
jQuery(document).ready(function() {
	 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

	setTimeout(function(){
        jQuery('.assign_by').select2({ 'placeholder' : "Choose Assign" });
        jQuery('.month-select').select2({ 'placeholder' : "Choose Month" }); 
        jQuery('.year-select').select2({ 'placeholder' : "Choose Year" }); 
        jQuery('.btn-view-report').click();
    },100);


    $('#cashflow-report-form').validate({
        // Validate only visible fields
        ignore: ":hidden",
        // Validation rules
        rules: {
            date_filter : {
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
            jQuery('.report-data-list').empty();
            jQuery('.report_type').val('view');
            jQuery('.new-dashboard-wraper').addClass('d-none');

            jQuery('#dashboard-container').html('<div class="db-loading"><div class="spinner"></div>Loading dashboard...</div>');
            
            jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').show();
            jQuery('.profit-cashflow-insight .skeleton-loader,.wc-insight .skeleton-loader,.ratio-insight .skeleton-loader').removeClass('d-none');

            $('#cashflow-report-form').ajaxSubmit({
                url: site_url+'/api/getDashboardReports',
                success: function(res) {
                    jQuery('#cashflow-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    

                    if( res.status == 'success' ){



                    jQuery('.new-dashboard-wraper').removeClass('d-none');

                    jQuery('.filterDateSet').text( jQuery('select[name="month"] option:selected').text()  +'-'+ jQuery('select[name="year"] option:selected').text() );

                    res.promptData.period_label = jQuery('select[name="month"] option:selected').text()  +'-'+ jQuery('select[name="year"] option:selected').text();
                    res.promptData.previous_period_label = jQuery('select[name="month"] option:selected').text()  +'-'+ jQuery('select[name="year"] option:selected').text();

                    var cashCustAMt = (res.report_data['Sales'].data.current_month + res.report_data['Accounts Receivable'].data.last_month) - res.report_data['Accounts Receivable'].data.current_month;
                    var cashCustSup = (res.report_data['COGS'].data.current_month + res.report_data['Accounts Payable'].data.last_month) - res.report_data['Accounts Payable'].data.current_month;

                    res.promptData.cash_metrics.cash_from_customers = cashCustAMt;
                    res.promptData.cash_metrics.cash_to_suppliers = cashCustSup;
                    res.promptData.cash_metrics.working_capital_change = parseFloat(res.report_data['Working Capital'].data.current_month);
                    res.promptData.cash_metrics.other_capital = parseFloat(res.report_data['Other Capital'].data.current_month);
                    res.promptData.cash_metrics.surplus_or_deficit = parseFloat(res.report_data['Working Capital'].data.current_month - (res.report_data['Retained Profit'].data.current_month + res.report_data['Other Capital'].data.current_month));

                    var revenueVariance      = cashCustAMt - parseFloat(res.report_data['Sales'].data.current_month);
                    var cogsVariance         = parseFloat(res.report_data['COGS'].data.current_month) - cashCustSup;
                    var grossVariance        = (parseFloat(cashCustAMt - cashCustSup)) - parseFloat(res.report_data['Gross Margin'].data.current_month);
                    var operatingVariance    = parseFloat(res.report_data['Operating Cash Flow'].data.current_month) - parseFloat(res.report_data['Operating Profit'].data.current_month);

                    jQuery('.profit-loss-plus,.profit-loss-minus,.working-capital-plus,.working-capital-minus,.other-capital-plus,.other-capital-minus,.capital-withdrawn-minus,.final-business-cash-a,.final-business-cash-b').text('—');

                    var positiveTotal = 0;
                    var negativeTotal = 0;
                    var retainedProfit      = parseFloat(res.report_data['Operating Profit'].data.current_month) ;
                    var workingCapital      = parseFloat(res.report_data['Working Capital'].data.current_month) -  parseFloat(res.report_data['Working Capital'].data.last_month);
                    var otherCapital        = parseFloat(res.report_data['Other Capital'].data.current_month) - parseFloat(res.report_data['Other Capital'].data.last_month);
                    var capitalWithdrawn    = parseFloat(res.report_data['Dividend Paid'].data.current_month) ;

                    /* Profit / Loss */
                    if (retainedProfit >= 0) {
                        positiveTotal += retainedProfit;
                        $('.profit-loss-plus').html( parseFloat(retainedProfit).toLocaleString('en-IN'));
                    } else {
                        negativeTotal += retainedProfit;
                        $('.profit-loss-minus').html( parseFloat(retainedProfit).toLocaleString('en-IN'));
                    }

                    /* Working Capital */
                    if (workingCapital < 0) {
                        positiveTotal += workingCapital;
                        $('.working-capital-plus').html( parseFloat(workingCapital).toLocaleString('en-IN'));
                    } else {
                        negativeTotal += workingCapital;
                        $('.working-capital-minus').html( parseFloat(workingCapital).toLocaleString('en-IN'));
                    }

                    /* Other Capital */ 
                    if (otherCapital < 0) {
                        positiveTotal += otherCapital;
                        $('.other-capital-plus').html( parseFloat(otherCapital).toLocaleString('en-IN'));
                    } else {
                        negativeTotal += otherCapital;
                        $('.other-capital-minus').html( parseFloat(otherCapital).toLocaleString('en-IN'));
                    }

                    /* Capital Withdrawn */
                    negativeTotal += capitalWithdrawn;
                    $('.capital-withdrawn-minus').html( parseFloat(capitalWithdrawn).toLocaleString('en-IN'));
          

                    /* Totals Row */
                    $('.final-business-cash-a').html( parseFloat(positiveTotal).toLocaleString('en-IN') );
                    $('.final-business-cash-b').html( parseFloat(negativeTotal).toLocaleString('en-IN') );

                    $('.pnNetCash').html( (parseFloat(positiveTotal) - parseFloat(negativeTotal)).toLocaleString('en-IN') );


                    jQuery('.pt-sales-amt').text(parseFloat(res.report_data['Sales'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-cogs-amt').text(parseFloat(res.report_data['COGS'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-gross-amt').text(parseFloat(res.report_data['Gross Margin'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.pt-profit-amt').text(parseFloat(res.report_data['Retained Profit'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-working-cap-amt').text(parseFloat(res.report_data['Working Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-other-cap-amt').text(parseFloat(res.report_data['Other Capital'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.pt-sub-a-amt').text(parseFloat(res.report_data['Retained Profit'].data.current_month + res.report_data['Other Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-sub-b-amt').text(parseFloat(res.report_data['Working Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-surplus-amt').text(parseFloat(res.report_data['Working Capital'].data.current_month - (res.report_data['Retained Profit'].data.current_month + res.report_data['Other Capital'].data.current_month)).toLocaleString('en-IN'));
                    jQuery('.pt-cap-wth-amt').text(parseFloat(res.report_data['Capital Withdrawn'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-operating-cash-flow-amt').text(parseFloat(res.report_data['Operating Cash Flow'].data.current_month).toLocaleString('en-IN')); 

                    jQuery('.pt-revenue-diff').text(parseFloat(revenueVariance).toLocaleString('en-IN'));
                    jQuery('.pt-cogs-diff').text(parseFloat(cogsVariance).toLocaleString('en-IN'));
                    jQuery('.pt-gross-diff').text(parseFloat(grossVariance).toLocaleString('en-IN'));
                    jQuery('.pt-opt-cash-diff').text(parseFloat(operatingVariance).toLocaleString('en-IN'));

                    var bg_color = 'green';
                    if( parseFloat(res.report_data['BS Category'].data.current_month) >= 81 ){
                        bg_color = 'green';
                    }else if( parseFloat(res.report_data['BS Category'].data.current_month) >= 61 && parseFloat(res.report_data['BS Category'].data.current_month) <= 80 ){                        
                        bg_color = 'orange';
                    }else{                       
                        bg_color = 'red';
                    }

                    jQuery('.bs-dayscore .days-bar-fill').attr('style', "width: "+parseFloat(res.report_data['BS Category'].data.current_month) +"%; background: "+bg_color+";");


                    jQuery('.pt-overheads-amt').text(parseFloat(res.report_data['Overheads'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-operating-cash-profit-amt').text(parseFloat(res.report_data['Operating Profit'].data.current_month).toLocaleString('en-IN'));
                    
                    jQuery('.pt-cash-cust-amt').text(parseFloat(cashCustAMt).toLocaleString('en-IN'));
                    jQuery('.pt-cash-sup-amt').text(parseFloat(cashCustSup).toLocaleString('en-IN'));
                    jQuery('.pt-gross-cash-amt').text(parseFloat(cashCustAMt - cashCustSup).toLocaleString('en-IN'));


               //     businesshealthChart(parseFloat(res.report_data['BS Category'].data.current_month));

                    jQuery('.acc-rec-box .wc-balance').text(parseFloat(res.report_data['Accounts Receivable'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.acc-rec-box .wc-row .val').text(parseFloat(res.report_data['Accounts Receivable'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.acc-rec-box .days-label .days-value').text(parseFloat(res.report_data['A/R Days'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.acc-ap-box .wc-balance').text(parseFloat(res.report_data['Accounts Payable'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.acc-ap-box .wc-row .val').text(parseFloat(res.report_data['Accounts Payable'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.acc-ap-box .days-label .days-value').text(parseFloat(res.report_data['A/P Days'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.inventory-box .wc-balance').text(parseFloat(res.report_data['Closing Stock'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.inventory-box .wc-row .val').text(parseFloat(res.report_data['Closing Stock'].data.last_month).toLocaleString('en-IN'));
                    jQuery('.inventory-box .days-label .days-value').text(parseFloat(res.report_data['Inventory Days'].data.current_month).toLocaleString('en-IN'));

                    jQuery('.net-working-cap-box .wc-balance').text(parseFloat(res.report_data['Working Capital'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.net-working-cap-box .wc-row .val').text(parseFloat(res.report_data['Working Capital'].data.last_month).toLocaleString('en-IN'));                    
                    jQuery('.net-working-cap-box .days-label .days-value').text(parseFloat(res.report_data['W/C Days'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.bs-score').text(parseFloat(res.report_data['BS Category'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.pt-revenue-amt-box .kpi-value.num').text(parseFloat(res.report_data['Sales'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Sales'].data.percentage, '.pt-revenue-amt-box' );
                    jQuery('.pt-operating-amt-box .kpi-value.num').text(parseFloat(res.report_data['Operating Cash Flow'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Operating Cash Flow'].data.percentage, '.pt-operating-amt-box' );
                    jQuery('.pt-grossmrg-amt-box .kpi-value.num').text(parseFloat(res.report_data['Gross Mrg Perc'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Gross Mrg Perc'].data.percentage, '.pt-grossmrg-amt-box' );
                    jQuery('.pt-netmrg-amt-box .kpi-value').text(parseFloat(res.report_data['Net Mrg Perc'].data.current_month).toLocaleString('en-IN'));
                    badgeHtmlSet( res.report_data['Net Mrg Perc'].data.percentage, '.pt-netmrg-amt-box' );
                    jQuery('.curr-ratio-box .ratio-value').text(parseFloat(res.report_data['CurrentRatio'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.curr-ratio-box .ratio-target span').text(parseFloat(res.report_data['CurrentRatio'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.quick-ratio-box .ratio-value').text(parseFloat(res.report_data['QuickRatio'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.quick-ratio-box .ratio-target span').text(parseFloat(res.report_data['QuickRatio'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.debt-equity-box .ratio-value').text(parseFloat(res.report_data['DebtToEquity'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.debt-equity-box .ratio-target span').text(parseFloat(res.report_data['DebtToEquity'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.interest-coverage-box .ratio-value').text(parseFloat(res.report_data['Interest Cover'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.interest-coverage-box .ratio-target span').text(parseFloat(res.report_data['Interest Cover'].data.last_month).toLocaleString('en-IN'));

                    jQuery('.return-equity-box .ratio-value').text(parseFloat(res.report_data['Return on equity'].data.current_month).toLocaleString('en-IN'));
                    jQuery('.return-equity-box .ratio-target span').text(parseFloat(res.report_data['Return on equity'].data.last_month).toLocaleString('en-IN'));


                    var kpiRecArr = ['.pt-sales-amt','.pt-cogs-amt','.pt-gross-amt','.pt-profit-amt','.pt-working-cap-amt','.pt-other-cap-amt','.pt-sub-a-amt','.pt-sub-b-amt','.pt-surplus-amt','.pt-cap-wth-amt', '.profit-loss-plus','.profit-loss-minus','.working-capital-plus','.working-capital-minus','.other-capital-plus','.other-capital-minus','.capital-withdrawn-minus','.final-business-cash-a','.final-business-cash-b','.pnNetCash', '.pt-revenue-diff','.pt-cogs-diff','.pt-gross-diff','.pt-opt-cash-diff','.pt-operating-cash-flow-amt','.pt-overheads-amt','.pt-operating-cash-profit-amt','.pt-cash-cust-amt','.pt-cash-sup-amt','.pt-gross-cash-amt','.acc-rec-box .wc-balance','.acc-rec-box .wc-row .val','.acc-ap-box .wc-balance','.acc-ap-box .wc-row .val','.inventory-box .wc-balance','.inventory-box .wc-row .val','.net-working-cap-box .wc-balance','.net-working-cap-box .wc-row .val','.pt-revenue-amt-box .kpi-value.num','.pt-operating-amt-box .kpi-value.num'];
                    jQuery(kpiRecArr).each(function(kk,vv){
                        var amountt = parseFloat(jQuery(vv).first().text().trim().replaceAll(',',''));
                        jQuery(vv).attr('data-amount',amountt);
                    });

                    jQuery("#dashboard-figureType").val(1000).change();
                                
                    var newpromptObj = res.promptData;
                    loadDeepSeekData( 'ProfitCashflow', newpromptObj, '.profit-cashflow-insight .insight-text', '.profit-cashflow-insight' );
                    // loadDeepSeekData( 'WorkingCapital', jQuery('.acc-rec-box').html() + jQuery('.acc-ap-box').html() + jQuery('.inventory-box').html() + jQuery('.net-working-cap-box').html(),  '.wc-insight .insight-text',  '.wc-insight' );
                    // loadDeepSeekData( 'FinancialRatio', jQuery('.ratio-grid').html(),  '.ratio-insight .insight-text', '.ratio-insight' );

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



    function badgeHtmlSet( percentage, clsName ){
        jQuery(clsName+' .kpi-trend').text(percentage+'%');
        jQuery(clsName+' .kpi-trend').removeClass('up').removeClass('down');
        if( percentage < 0 ){ 
            jQuery(clsName+' .kpi-trend').addClass('down');
        }else{
            jQuery(clsName+' .kpi-trend').addClass('up');
        }
    }


    // Icon maps
      var CAT_ICONS = { profit:'📉', cash:'💸', working_capital:'🏪', growth:'📈' };
      var WEEK_ICONS = ['🚰','✂️','🤝','🧾'];
      var TONE_ICONS = { good:'✅', mixed:'⚠️', critical:'🚨', steady:'📊', needs_attention:'⚠️' };

      // ══════════════════════════════════════════════
      //  SECTION BUILDERS
      // ══════════════════════════════════════════════

      function buildHeader(d) {
        var ex    = d.executive_summary;
        var attn  = d.owner_attention_meter || {};
        var level = (attn.level || 'needs_attention').toLowerCase();
        var label = level.replace(/_/g, ' ').replace(/\b\w/g, function(c){ return c.toUpperCase(); });

        return $('<div class="db-header">').append(
          $('<div>').append(
            $('<div class="brand">').html('Profit<em>Niti</em>'),
            $('<div class="db-sub">').text('Business Financial Dashboard · Current Period'),
            $('<div class="status-pill pill-' + level + '">').append(
              $('<span class="pill-dot">'),
              $('<span>').text(' ' + label)
            )
          ),
          $('<div class="h-right">').append(
            $('<div class="h-label">').text('Period'),
            $('<div class="h-val">').text('Current Month'),
            $('<div class="h-label" style="margin-top:8px">').text('Tone'),
            $('<div class="h-val">').text(
              (ex.tone || '').charAt(0).toUpperCase() + (ex.tone || '').slice(1)
            )
          )
        );
      }

      function buildSummary(d) {
        var ex = d.executive_summary;
        return $('<div class="summary-band">').append(
          $('<div class="s-icon">').text(TONE_ICONS[ex.tone] || '📊'),
          $('<div>').append(
            $('<div class="s-headline">').text(ex.headline),
            $('<div class="s-body">').text(ex.summary_paragraph)
          )
        );
      }

      function buildMetrics() {
        // Static rows parsed from the known JSON structure
        var rows = [
          { label:'Total Sales',       val:'₹4.08 Cr',  sub:'↑ +10.6% vs last period', cls:'up' },
          { label:'Gross Margin',      val:'52.6%',      sub:'↑ up from 48.85%',        cls:'up' },
          { label:'Net Profit Margin', val:'14.21%',     sub:'↓ down from 29.39%',      cls:'dn' },
          { label:'Operating Cash',    val:'₹3.98 Cr',  sub:'generated this period',   cls:'nt' },
          { label:'Withdrawals',       val:'₹24.81 Cr', sub:'↓ 6× business earnings',  cls:'dn', danger:true },
          { label:'Liquidity Ratio',   val:'2.28×',     sub:'short-term bills covered', cls:'up' }
        ];
        var $g = $('<div class="metric-grid">');
        $.each(rows, function(i, r) {
          var $v = $('<div class="m-val">').text(r.val);
          if (r.danger) $v.addClass('danger');
          $g.append(
            $('<div class="metric">').css('animation-delay', (i * 0.06) + 's').append(
              $('<div class="m-label">').text(r.label),
              $v,
              $('<div class="m-sub ' + r.cls + '">').text(r.sub)
            )
          );
        });
        return $g;
      }

      function buildKPIs() {
        var rows = [
          { label:'Days customers take to pay',    val:'76 days',       pct:'35',  color:'var(--red)',   note:'Target: collect sooner — offer 1% discount for 7-day payment' },
          { label:'Days you take to pay suppliers',val:'214 days',      pct:'100', color:'var(--blue)',  note:'Target: max 180 days to protect supplier relationships' },
          { label:'Debt vs own capital',           val:'₹2.86 : ₹1',  pct:'74',  color:'#D48A1C',      note:'74% funded by external loans — high leverage' },
          { label:'Return on investment',          val:'₹4.67 / ₹100', pct:'40',  color:'var(--green)', note:'₹2.57 per ₹100 total invested (own + borrowed)' }
        ];
        var $g = $('<div class="kpi-grid">');
        $.each(rows, function(_, r) {
          $g.append(
            $('<div class="kpi-card">').append(
              $('<div class="kpi-label">').text(r.label),
              $('<div class="kpi-val">').text(r.val),
              $('<div class="kpi-track">').append(
                $('<div class="kpi-fill">').css({ width: r.pct + '%', background: r.color })
              ),
              $('<div class="kpi-note">').text(r.note)
            )
          );
        });
        return $g;
      }

      function buildInsights(d) {
        var $wrap = $('<div>');
        $.each(d.top_3_insights || [], function(i, ins) {
          var sev = (ins.severity || 'watch').toLowerCase();
          $wrap.append(
            $('<div class="insight-card ' + sev + '">').css('animation-delay', (i * 0.08) + 's').append(
              $('<div class="i-badge ' + sev + '">').text(CAT_ICONS[ins.category] || '📊'),
              $('<div>').append(
                $('<div class="i-rank">').text('#' + ins.rank + ' ' + (ins.category || '').replace(/_/g,' ').toUpperCase()),
                $('<div class="i-title">').append(
                  $('<span>').text(ins.title),
                  $('<span class="sev-tag ' + sev + '">').text(sev)
                ),
                $('<div class="i-what">').text(ins.what_happened),
                $('<div class="i-fix">').append(
                  $('<span class="fix-label">').text('Fix →'),
                  $('<span>').text(ins.the_fix)
                )
              )
            )
          );
        });
        return $wrap;
      }

      function buildActionPlan(d) {
        var plan  = d['30_day_action_plan'] || {};
        var weeks = [plan.week_1, plan.week_2, plan.week_3, plan.week_4];
        var $grid = $('<div class="action-grid">');

        $.each(weeks, function(i, w) {
          if (!w) return;
          var $li = $('<ul class="week-tasks">');
          $.each(w.tasks || [], function(_, t) { $li.append($('<li>').text(t)); });
          $grid.append(
            $('<div class="action-card">').append(
              $('<span class="week-tag">').text(w.label),
              $('<div class="week-theme">').text(WEEK_ICONS[i] + ' ' + w.theme),
              $li,
              $('<span class="week-target">').text('Target: ' + w.target)
            )
          );
        });

        var $outcome = $('<div class="outcome-box">').append(
          $('<span style="font-size:22px;flex-shrink:0">').text('🎯'),
          $('<p>').html('<strong>Expected by Day 30:</strong> ' + (plan.expected_outcome_by_day_30 || ''))
        );

        return $('<div>').append($grid, $outcome);
      }

      function buildCommentary(d) {
        var comm = d.section_commentaries || {};
        var map = {
          growth:           '📈 Growth',
          profit_cash_flow: '💰 Profit & Cash Flow',
          working_capital:  '⏱ Working Capital',
          capital_debt:     '🏦 Capital & Debt',
          returns:          '📊 Returns',
          liquidity:        '💧 Liquidity'
        };
        var $g = $('<div class="comm-grid">');
        $.each(map, function(key, label) {
          if (!comm[key]) return;
          $g.append(
            $('<div class="comm-card">').append(
              $('<div class="comm-title">').text(label),
              $('<div class="comm-body">').text(comm[key])
            )
          );
        });
        return $g;
      }

      function buildAttention(d) {
        var attn = d.owner_attention_meter;
        if (!attn) return $();
        return $('<div class="attention-box">').append(
          $('<div class="att-icon">').text('⏰'),
          $('<div>').append(
            $('<div class="att-title">').text('Owner Attention Required'),
            $('<div class="att-body">').text(attn.one_line)
          )
        );
      }

      // ══════════════════════════════════════════════
      //  MAIN RENDER — called with your JSON object
      // ══════════════════════════════════════════════
      function renderDashboard(data) {
        var $c = $('#dashboard-container').empty();
        try {
          $c.append(
            
            $('<div class="sec-label">').text('Top 3 insights'),
            buildInsights(data),
            $('<div class="sec-label">').text('30-day action plan'),
            buildActionPlan(data),
            $('<div class="sec-label">').text('Section commentary'),
            buildCommentary(data),
            buildAttention(data),
            $('<div class="db-footer">').text('ProfitNiti · Current period · All amounts in INR')
          );
        } catch(err) {
          $c.html($('<div class="db-error">').text('Render error: ' + err.message));
          console.error(err);
        }
      }

    function loadDeepSeekData( type = '', promptObj, htmlWrapper, mainCLs ){
        jQuery.ajax({
            type: "POST",
            url: site_url+'/api/generateDashboardDataWithDeepSeek',
            data: {
                promptObj : promptObj,
                type : type,
                month: jQuery('.hg-filter-box select[name="month"]').val(),
                year : jQuery('.hg-filter-box select[name="year"]').val()
            },
            dataType:'json',
            success: function (data) {
                if( data.status == 'error' ){
                    swal.fire({
                        "title": "",
                        'icon': "error",
                        "text": data.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }else{
                    // jQuery(htmlWrapper).html(data.content);
                    // jQuery(mainCLs+' .skeleton-loader').addClass('d-none');

                    renderDashboard(jQuery.parseJSON(data.content));
                }
            },
            error: function (data) {
                var errMsg = '';
                $('#dashboard-container').empty();
                if( data.status == 504 ){
                    errMsg = 'Sorry, the page you are looking for is currently unavailable. Please try again later.';
                }else{
                    errMsg = data.responseJSON.message !== undefined ? data.responseJSON.message : data.message;
                }
                swal.fire({
                    "title": "",
                    'icon': "error",
                    "text": errMsg,
                    "type": "error",
                    "buttonStyling": false,
                    "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                });
            }
        });
    }

    // Change event
    $(document).on("change", "#dashboard-figureType", function () {
        let divisor = parseFloat($(this).val());
        let suffixVal = $("#figureType").val(); 
        var suffix = '';
        if( suffixVal == 1000 ){
            suffix = 'K';
        }else if( suffixVal == 100000 ){
            suffix = 'L';
        }else if( suffixVal == 1000000 ){
            suffix = 'M';
        }else if( suffixVal == 10000000 ){
            suffix = 'Cr';
        }
        var kpiRecArr = ['.pt-sales-amt','.pt-cogs-amt','.pt-gross-amt','.pt-profit-amt','.pt-working-cap-amt','.pt-other-cap-amt','.pt-sub-a-amt','.pt-sub-b-amt', '.pt-revenue-diff', '.profit-loss-plus','.profit-loss-minus','.working-capital-plus','.working-capital-minus','.other-capital-plus','.other-capital-minus','.capital-withdrawn-minus','.final-business-cash-a','.final-business-cash-b','.pnNetCash', '.pt-cogs-diff','.pt-gross-diff','.pt-opt-cash-diff','.pt-surplus-amt','.pt-cap-wth-amt','.pt-operating-cash-flow-amt','.pt-overheads-amt','.pt-operating-cash-profit-amt','.pt-cash-cust-amt','.pt-cash-sup-amt','.pt-gross-cash-amt','.acc-rec-box .wc-balance','.acc-rec-box .wc-row .val','.acc-ap-box .wc-balance','.acc-ap-box .wc-row .val','.inventory-box .wc-balance','.inventory-box .wc-row .val','.net-working-cap-box .wc-balance','.net-working-cap-box .wc-row .val','.pt-revenue-amt-box .kpi-value.num','.pt-operating-amt-box .kpi-value.num'];
        jQuery(kpiRecArr).each(function(kk,vv){
            var original = parseFloat(jQuery(vv).attr('data-amount'));
            if (original !== undefined) {
                let newValue = original / divisor;  
                if( isNaN(newValue) )             {
                    newValue = 0;
                }
                jQuery(vv).text(DSPNTFormatNumber(newValue));
            }
        });
    });
	


    // Function to format number with commas
    function DSPNTFormatNumber(num) {
        return Number(num).toLocaleString('en-IN', {
            maximumFractionDigits: 2
        });
    }
});