<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cash ProfitNiti AI — Financial Intelligence Guide</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=DM+Mono:wght@400;500&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:"DM Sans",sans-serif;font-size:14px;color:#1A1A18;background:#FAFAF8;line-height:1.65;-webkit-font-smoothing:antialiased}

/* ── TOPBAR ── */
.topbar{position:sticky;top:0;z-index:200;display:flex;align-items:center;gap:14px;padding:0 20px;height:52px;background:#fff;border-bottom:1px solid #E8E8E4;box-shadow:0 1px 6px rgba(0,0,0,.06)}
.brand{display:flex;align-items:center;gap:9px;text-decoration:none;flex-shrink:0}
.bmark{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#1D9E75,#0F6E56);display:grid;place-items:center;font-family:"Playfair Display",serif;font-weight:700;font-size:13px;color:#fff}
.bname{font-weight:600;font-size:14px;color:#1A1A18}
.bsub{font-size:11px;color:#9A9A97}
.sw{flex:1;max-width:420px;position:relative}
.sw input{width:100%;padding:8px 40px 8px 36px;border:1.5px solid #E8E8E4;border-radius:8px;font-family:"DM Sans",sans-serif;font-size:13px;background:#F5F5F3;outline:none;transition:border-color .15s,background .15s}
.sw input:focus{border-color:#1D9E75;background:#fff}
.sicon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#B0B0AC;pointer-events:none;font-size:15px}
.scnt{position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:11px;color:#9A9A97;font-weight:500}
.pills{display:flex;gap:5px;margin-left:auto;flex-wrap:wrap}
.pill{font-size:11.5px;padding:5px 12px;border-radius:20px;border:1.5px solid #E8E8E4;cursor:pointer;font-family:"DM Sans",sans-serif;background:#fff;color:#6B6B68;transition:all .13s;white-space:nowrap}
.pill:hover{border-color:#1D9E75;color:#1D9E75}
.pill.on{background:#1D9E75;border-color:#1D9E75;color:#fff;font-weight:500}

/* ── LAYOUT ── */
.lay{display:grid;grid-template-columns:220px 1fr;min-height:calc(100vh - 52px)}

/* ── SIDEBAR ── */
.sb{border-right:1px solid #E8E8E4;background:#fff;position:sticky;top:52px;height:calc(100vh - 52px);overflow-y:auto;padding:10px 0 60px}
.sb::-webkit-scrollbar{width:3px}
.sb::-webkit-scrollbar-thumb{background:#E0E0DC;border-radius:4px}
.ng{margin-bottom:1px}
.ng-title{font-size:10.5px;font-weight:600;padding:9px 16px 3px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;user-select:none;letter-spacing:.01em}
.ng-title .arr{font-size:9px;color:#C0C0BC;transition:transform .2s}
.ng-title.closed .arr{transform:rotate(-90deg)}
.ng-items{overflow:hidden}
.ni{display:flex;align-items:center;gap:6px;padding:4.5px 16px 4.5px 20px;font-size:12px;color:#6B6B68;text-decoration:none;border-left:2px solid transparent;transition:all .1s;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ni::before{content:"";width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.35;flex-shrink:0}
.ni:hover{color:#1A1A18;background:#F5F5F3}
.ni.on{color:#1D9E75;border-left-color:#1D9E75;font-weight:500;background:#F0FBF7}
.ndiv{height:1px;background:#F0F0EC;margin:5px 12px}

/* ── MAIN ── */
.main{max-width:940px}
.no-res{text-align:center;padding:80px 40px;color:#9A9A97;display:none}
.no-res h3{font-size:16px;color:#6B6B68;margin-bottom:8px}

/* ── SECTION STRUCTURE ── */
.sec{border-bottom:2px solid #E8E8E4}
.sec-hdr{padding:26px 32px 18px;position:relative}
.sec-bar{height:4px;position:absolute;left:0;top:0;right:0}
.sec-eye{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:5px}
.sec-title{font-family:"Playfair Display",serif;font-size:21px;font-weight:500;line-height:1.25}
.sec-sub{font-size:13px;color:#6B6B68;margin-top:6px;line-height:1.6}
.section-body{font-size:13.5px;color:#3D3D3A;line-height:1.7;margin-bottom:14px}
.cards-wrap{padding:22px 32px 36px}
.area-hdr{padding:18px 32px;display:flex;align-items:center;gap:14px}
.area-num{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;font-weight:700;font-size:15px;color:#fff;flex-shrink:0}
.area-name{font-family:"Playfair Display",serif;font-size:17px;font-weight:500}
.area-sub{font-size:12px;color:#6B6B68;margin-top:3px}

/* ── METRIC CARDS ── */
.card{background:#fff;border:1px solid #E8E8E4;border-radius:12px;margin-bottom:16px;overflow:hidden;scroll-margin-top:62px;transition:box-shadow .2s,border-color .2s}
.card:hover{box-shadow:0 4px 20px rgba(0,0,0,.07)}
.card.hidden{display:none}
.card.hi .card-head{background:#FFFBEA}
.card-head{padding:13px 18px 11px;border-bottom:1px solid #F0F0EC}
.card-head h3{font-size:14.5px;font-weight:600;color:#1A1A18}
.card-body{padding:15px 18px 18px}
.tagline{font-style:italic;font-family:"Playfair Display",serif;font-size:13.5px;color:#6B6B68;margin-bottom:12px;line-height:1.5}
.story{font-size:13.5px;color:#3D3D3A;line-height:1.75;margin-bottom:14px}

/* ── FORMULA BOXES ── */
.fbox{background:#EAF3DE;border:1px solid #5DCAA5;border-radius:8px;padding:9px 14px;margin-bottom:7px}
.flabel{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#0F6E56;display:block;margin-bottom:3px}
.fcode{font-family:"DM Mono",monospace;font-size:12px;color:#0F6E56;line-height:1.65;display:block}

/* ── INFO BOX ── */
.info-box{background:#EEEDFE;border:1px solid #AFA9EC;border-radius:9px;padding:12px 16px;margin-bottom:14px;font-size:13px;color:#26215C;line-height:1.65}

/* ── INTRO TABLE ── */
.intro-tbl{width:100%;border-collapse:collapse;font-size:13px;margin-bottom:8px}
.intro-tbl th{background:#1D9E75;color:#fff;padding:9px 14px;text-align:left;font-weight:600;font-size:12px}
.intro-tbl td{padding:8px 14px;border-bottom:1px solid #E8E8E4}
.intro-tbl tr:nth-child(even) td{background:#F0FBF7}

/* ── PVCF TABLE ── */
.pvcf-wrap{overflow-x:auto;margin-bottom:12px}
.pvcf{width:100%;border-collapse:collapse;font-size:12.5px}
.pvcf th{background:#1D9E75;color:#fff;padding:9px 11px;text-align:left;font-size:11.5px;font-weight:600}
.pvcf td{padding:8px 11px;border-bottom:1px solid #E8E8E4;vertical-align:top}
.pvcf tr:nth-child(odd) td{background:#F0FBF7}
.pvcf .tot td{background:#1D1D1B;color:#fff;font-weight:600}
.var{color:#993C1D;font-weight:600}
.nil{color:#9A9A97}

/* ── BGC TABLE ── */
.bgc-wrap{overflow-x:auto;margin:14px 0}
.bgc{width:100%;border-collapse:collapse;font-size:12.5px}
.bgc th{background:#1D9E75;color:#fff;padding:9px 13px;text-align:left;font-weight:600}
.bgc td{padding:8px 13px;border-bottom:1px solid #E8E8E4;vertical-align:top}
.bgc .p1 td{background:#F0FBF7}
.bgc .wc td,.bgc .oc td{background:#FFF8EE}
.bgc .tot td{background:#1D1D1B;color:#fff;font-weight:600}
.bgc-section{margin:0 32px 20px;background:#fff;border:1px solid #E8E8E4;border-radius:12px;padding:16px 18px}

/* ── FORMULA CLEAN ── */
.fl{font-weight:700;font-size:9px;text-transform:uppercase;letter-spacing:.07em;color:#0F6E56}
.fc{font-family:"DM Mono",monospace;font-size:12.5px;color:#0F6E56;line-height:1.65}
/* ── TITLE BANNER ── */
.title-banner{padding:32px 32px 24px;border-bottom:1px solid #E8E8E4;background:linear-gradient(135deg,#F4FBF8 0%,#fff 100%)}
.tb-main{font-family:"Playfair Display",serif;font-size:28px;font-weight:600;color:#1D9E75;margin-bottom:4px}
.tb-sub{font-size:15px;font-weight:500;color:#1A1A18;margin-bottom:4px}
.tb-by{font-size:12px;color:#9A9A97;margin-bottom:10px}
.tb-desc{font-size:13px;color:#6B6B68;font-style:italic;line-height:1.6}
/* ── HIGHLIGHT ── */
mark{background:#FFF176;color:inherit;border-radius:2px;padding:0 1px}
</style>
</head>
<body>
<div class="topbar">
  <a class="brand" href="#">
    <div class="bmark">P</div>
    <div><div class="bname">Cash ProfitNiti AI</div><div class="bsub">Financial Guide</div></div>
  </a>
  <div class="sw">
    <span class="sicon">&#9906;</span>
    <input type="text" id="sb" placeholder="Search any metric, term, formula, or concept…" oninput="doSearch(this.value)">
    <span class="scnt" id="sc"></span>
  </div>
  <div class="pills">
    <button class="pill on" onclick="filt('all',this)">All</button>
    <button class="pill" onclick="filt('bs',this)">Balance Sheet</button>
    <button class="pill" onclick="filt('pl',this)">P&amp;L</button>
    <button class="pill" onclick="filt('cf',this)">Cash Flow</button>
    <button class="pill" onclick="filt('area1',this)">Area 1</button>
    <button class="pill" onclick="filt('area2',this)">Area 2</button>
    <button class="pill" onclick="filt('area3',this)">Area 3</button>
    <button class="pill" onclick="filt('area4',this)">Area 4</button>
  </div>
</div>
<div class="lay">
<nav class="sb" id="nav">
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#1D9E75">Introduction</div>
<div class="ng-items"><a class="ni" href="#profitniti-ai">Cash ProfitNiti AI</a>
<a class="ni" href="#developed-by-chanakya-shah-profitniti-ai">Developed by Chanakya Shah  ·  Cash ProfitNiti AI</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#1D9E75">Source Reports</div>
<div class="ng-items"></div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#1D9E75">Balance Sheet</div>
<div class="ng-items"><a class="ni" href="#total-equity">Total Equity</a>
<a class="ni" href="#secured-long-term-borrowings-un-secured-long-term-borrowings">Secured Long-term Borrowings/ Un-Secured Long-term Borrowings</a>
<a class="ni" href="#long-term-provisions">Long Term Provisions</a>
<a class="ni" href="#deferred-tax-liabilities">Deferred Tax Liabilities</a>
<a class="ni" href="#other-non-current-liabilities">Other Non-Current Liabilities</a>
<a class="ni" href="#secured-short-term-borrowings">Secured Short-Term Borrowings</a>
<a class="ni" href="#accounts-payable">Accounts Payable</a>
<a class="ni" href="#short-term-provisions">Short Term Provisions</a>
<a class="ni" href="#current-deferred-tax-liabilities">Current Deferred Tax Liabilities</a>
<a class="ni" href="#other-current-liabilities">Other Current Liabilities</a>
<a class="ni" href="#fixed-assets">Fixed Assets</a>
<a class="ni" href="#non-current-investments">Non-Current Investments</a>
<a class="ni" href="#long-term-loans-advances">Long Term Loans &amp; Advances</a>
<a class="ni" href="#deferred-tax-assets">Deferred Tax Assets</a>
<a class="ni" href="#other-non-current-assets">Other Non-Current Assets</a>
<a class="ni" href="#current-investments">Current Investments</a>
<a class="ni" href="#closing-stock">Closing Stock</a>
<a class="ni" href="#short-term-loans-advances">Short Term Loans &amp; Advances</a>
<a class="ni" href="#accounts-receivable">Accounts Receivable</a>
<a class="ni" href="#cash-and-bank-balances">Cash and Bank Balances</a>
<a class="ni" href="#other-current-assets">Other Current Assets</a>
<a class="ni" href="#branch">Branch</a>
<a class="ni" href="#suspense">Suspense</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#185FA5">P&amp;L Report</div>
<div class="ng-items"><a class="ni" href="#sales">Sales</a>
<a class="ni" href="#cost-of-goods-sold-cogs">Cost of Goods Sold (COGS)</a>
<a class="ni" href="#gross-profit">Gross Profit</a>
<a class="ni" href="#employee-benefit-expenses">Employee Benefit Expenses</a>
<a class="ni" href="#selling-admin-expenses">Selling &amp; Admin Expenses</a>
<a class="ni" href="#other-expenses">Other Expenses</a>
<a class="ni" href="#depreciation">Depreciation</a>
<a class="ni" href="#finance-cost">Finance Cost</a>
<a class="ni" href="#other-non-operating-income">Other Non-Operating Income</a>
<a class="ni" href="#other-non-operating-expenses">Other Non-Operating Expenses</a>
<a class="ni" href="#extra-ordinary-income">Extra Ordinary Income</a>
<a class="ni" href="#extra-ordinary-expense">Extra Ordinary Expense</a>
<a class="ni" href="#pbt">PBT</a>
<a class="ni" href="#profit-after-tax-pat">Profit After Tax (PAT)</a>
<a class="ni" href="#dividend-paid">Dividend paid</a>
<a class="ni" href="#retained-profit">Retained Profit</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#854F0B">Cash Flow</div>
<div class="ng-items"><a class="ni" href="#cash-flows-from-operations">Cash Flows from Operations</a>
<a class="ni" href="#cash-flows-from-investing">Cash Flows from Investing</a>
<a class="ni" href="#cash-flows-from-financing">Cash Flows from Financing</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#534AB7">Financial Summary</div>
<div class="ng-items"></div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#1D9E75">Area 1 — Profit Power</div>
<div class="ng-items"><a class="ni" href="#revenue-growth">Revenue Growth %</a>
<a class="ni" href="#cogs-growth">COGS Growth %</a>
<a class="ni" href="#gross-margin">Gross Margin %</a>
<a class="ni" href="#overheads-and-overheads-growth">Overheads % and Overheads Growth %</a>
<a class="ni" href="#operating-profit-ebit-and-operating-profit">Operating Profit (EBIT) and Operating Profit %</a>
<a class="ni" href="#ebitda-and-operating-cash-profit">EBITDA and Operating Cash Profit</a>
<a class="ni" href="#net-profit-and-retained-profit">Net Profit % and Retained Profit</a>
<a class="ni" href="#interest-cover">Interest Cover</a>
<a class="ni" href="#break-even-sales">Break Even Sales</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#185FA5">Area 2 — Cash Management</div>
<div class="ng-items"><a class="ni" href="#accounts-receivable-days-ar-days">Accounts Receivable Days (AR Days)</a>
<a class="ni" href="#inventory-days">Inventory Days</a>
<a class="ni" href="#accounts-payable-days-ap-days">Accounts Payable Days (AP Days)</a>
<a class="ni" href="#working-capital-days">Working Capital Days</a>
<a class="ni" href="#working-capital-per-100">Working Capital per ₹100</a>
<a class="ni" href="#marginal-cash-flow">Marginal Cash Flow</a>
<a class="ni" href="#working-capital-turnover">Working Capital Turnover</a>
<a class="ni" href="#current-ratio">Current Ratio</a>
<a class="ni" href="#quick-ratio">Quick Ratio</a>
<a class="ni" href="#cash-ratio">Cash Ratio</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#854F0B">Area 3 — Capex</div>
<div class="ng-items"><a class="ni" href="#other-capital-and-other-capital">Other Capital and Other Capital %</a>
<a class="ni" href="#net-operating-assets-and-net-operating-assets">Net Operating Assets and Net Operating Assets %</a>
<a class="ni" href="#asset-turnover">Asset Turnover</a>
<a class="ni" href="#return-on-capital">Return on Capital %</a>
<a class="ni" href="#return-on-total-assets">Return on Total Assets %</a>
<a class="ni" href="#return-on-equity">Return on Equity %</a>
</div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#1D9E75">Profit vs Cash Flow</div>
<div class="ng-items"></div></div>
<div class="ng"><div class="ng-title" onclick="toggleNav(this)" style="color:#3B3B3A">Area 4 — Financing</div>
<div class="ng-items"><a class="ni" href="#net-debt-total-debt-and-debt-to-equity">Net Debt, Total Debt and Debt to Equity</a>
<a class="ni" href="#total-funding-the-balancing-identity">Total Funding — The Balancing Identity</a>
<a class="ni" href="#debt-payback">Debt Payback</a>
<a class="ni" href="#business-generated-cash">Business Generated Cash</a>
<a class="ni" href="#the-business-generated-cash-table">The Business Generated Cash Table</a>
<a class="ni" href="#operating-cash-flow">Operating Cash Flow</a>
<a class="ni" href="#net-cash-flow">Net Cash Flow</a>
<a class="ni" href="#operating-cash-flow-margin">Operating Cash Flow Margin %</a>
<a class="ni" href="#cash-flow-coverage">Cash Flow Coverage</a>
<a class="ni" href="#cash-flow-to-debt">Cash Flow to Debt %</a>
</div></div>

</nav>
<main class="main">
<div class="no-res" id="nr"><h3>No results found</h3><p>Try a different keyword or clear the search.</p></div>
<div id="sec-intro" class="sec" data-section="intro"><div class="title-banner"><div class="tb-main">Cash ProfitNiti AI</div><div class="tb-sub">Complete Financial Intelligence Framework</div><div class="tb-by">Developed by Chanakya Shah · Cash ProfitNiti AI</div><p class="tb-desc">The complete user guide: four source reports · four financial areas · all ratios explained · formulas · numerical examples</p></div><div class="sec-hdr"><div class="sec-bar" style="background:#1D9E75"></div><div class="sec-eye" style="color:#1D9E75">Introduction</div><h2 class="sec-title">The 4 Financial Areas</h2><p class="sec-sub">Cash ProfitNiti AI tells the story of every business through four financial areas. Each area answers a fundamental question about the business. Together they give the complete financial picture — from how much the business earns to how it funds its operations.</p></div><div class="cards-wrap"><table class="intro-tbl"><thead><tr><th>Financial Area</th><th>Name</th><th>What It Answers</th></tr></thead><tbody><tr><td>Financial Area 1</td><td>Profit Power</td><td>How much does the business earn? Revenue, margins, overheads, operating profit.</td></tr><tr><td>Financial Area 2</td><td>Cash Management</td><td>How efficiently does cash move through the cycle? Debtors, stock, creditors, working capital.</td></tr><tr><td>Financial Area 3</td><td>Capex</td><td>What capital does the business need to operate? Fixed assets, returns, capital efficiency.</td></tr><tr><td>Financial Area 4</td><td>Financing</td><td>How is the business funded? Debt, equity, cash flow, sustainability.</td></tr></tbody></table></div></div>
<div id="sec-bs" class="sec" data-section="bs" style="background:#F4FBF8"><div class="sec-hdr"><div class="sec-bar" style="background:#1D9E75"></div><div class="sec-eye" style="color:#1D9E75">Source Report 1</div><h2 class="sec-title">Balance Sheet</h2></div><div class="cards-wrap"><p class="section-body">The balance sheet is a photograph taken at a single moment — the last day of the period. Unlike the P&amp;L which records what happened during a period, the balance sheet shows the position right now: every asset owned, every liability owed, and the equity that remains for the owners. Three sections: Equity, Liabilities, and Assets.</p>
<div class="card" id="total-equity" data-section="bs" data-search="Total Equity What the business is truly worth to its owners — after every debt is settled. If the business closed today, sold everything it owns, and paid off every rupee it owes, the amount left over is the equity. It grows every year the business retains profit and shrinks every year it makes a loss or the owner withdraws more than it earns. Total Equity is the scoreboard of a business&#x27;s financial lifetime — the accumulated result of every decision ever made.">
<div class="card-head"><h3>Total Equity</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What the business is truly worth to its owners — after every debt is settled.&#8221;</p>
<p class="story">If the business closed today, sold everything it owns, and paid off every rupee it owes, the amount left over is the equity. It grows every year the business retains profit and shrinks every year it makes a loss or the owner withdraws more than it earns. Total Equity is the scoreboard of a business&#x27;s financial lifetime — the accumulated result of every decision ever made.</p>
</div>
</div></div>
<div class="card" id="secured-long-term-borrowings-un-secured-long-term-borrowings" data-section="bs" data-search="Secured Long-term Borrowings/ Un-Secured Long-term Borrowings The mortgage on the business — borrowed to build, repaid over years. When a business needs a new factory, fleet of vehicles, or a major asset generating returns over many years, it borrows long term. These are loans with repayment schedules beyond one year — secured term loans from banks, debentures, and other long-dated obligations. Long-term borrowings fund the &#x27;Other Capital&#x27; side of the business (Financial Area 3).">
<div class="card-head"><h3>Secured Long-term Borrowings/ Un-Secured Long-term Borrowings</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The mortgage on the business — borrowed to build, repaid over years.&#8221;</p>
<p class="story">When a business needs a new factory, fleet of vehicles, or a major asset generating returns over many years, it borrows long term. These are loans with repayment schedules beyond one year — secured term loans from banks, debentures, and other long-dated obligations. Long-term borrowings fund the &#x27;Other Capital&#x27; side of the business (Financial Area 3).</p>
</div>
</div></div>
<div class="card" id="long-term-provisions" data-section="bs" data-search="Long Term Provisions Money set aside today for obligations the business knows will arrive — just not immediately. When a business employs people for years, it builds up obligations: gratuity on retirement, leave encashment, pending warranties, and potential litigation settlements. Long Term Provisions set aside funds for these known future obligations today, rather than recording a sudden large expense when they crystallise. They represent sound financial governance — acknowledging that liabilities exist even before they become payable.">
<div class="card-head"><h3>Long Term Provisions</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money set aside today for obligations the business knows will arrive — just not immediately.&#8221;</p>
<p class="story">When a business employs people for years, it builds up obligations: gratuity on retirement, leave encashment, pending warranties, and potential litigation settlements. Long Term Provisions set aside funds for these known future obligations today, rather than recording a sudden large expense when they crystallise. They represent sound financial governance — acknowledging that liabilities exist even before they become payable.</p>
</div>
</div></div>
<div class="card" id="deferred-tax-liabilities" data-section="bs" data-search="Deferred Tax Liabilities A tax bill that has been postponed — it will arrive eventually, just not this year. Deferred Tax Liabilities arise when accounting rules and tax rules recognise income or expenses at different times. The most common example: a business claims accelerated depreciation for tax purposes, reducing current tax, but records standard depreciation in its books. This creates a gap — tax saved now will be owed later. It is not avoidance; it is timing. Over a business&#x27;s life, deferred tax liabilities and assets tend to net out.">
<div class="card-head"><h3>Deferred Tax Liabilities</h3></div>
<div class="card-body">
<p class="tagline">&#8220;A tax bill that has been postponed — it will arrive eventually, just not this year.&#8221;</p>
<p class="story">Deferred Tax Liabilities arise when accounting rules and tax rules recognise income or expenses at different times. The most common example: a business claims accelerated depreciation for tax purposes, reducing current tax, but records standard depreciation in its books. This creates a gap — tax saved now will be owed later. It is not avoidance; it is timing. Over a business&#x27;s life, deferred tax liabilities and assets tend to net out.</p>
</div>
</div></div>
<div class="card" id="other-non-current-liabilities" data-section="bs" data-search="Other Non-Current Liabilities Long-term obligations outside standard categories — often free funding from customers and distributors. Other Non-Current Liabilities is the catch-all for long-term obligations beyond borrowings and provisions: security deposits received from customers or distributors, deferred revenue on multi-year contracts, and long-term government grants received in advance. For businesses with large dealer or distributor networks, this can be substantial — representing interest-free, long-term funding provided by the trade channel.">
<div class="card-head"><h3>Other Non-Current Liabilities</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Long-term obligations outside standard categories — often free funding from customers and distributors.&#8221;</p>
<p class="story">Other Non-Current Liabilities is the catch-all for long-term obligations beyond borrowings and provisions: security deposits received from customers or distributors, deferred revenue on multi-year contracts, and long-term government grants received in advance. For businesses with large dealer or distributor networks, this can be substantial — representing interest-free, long-term funding provided by the trade channel.</p>
</div>
</div></div>
<div class="card" id="secured-short-term-borrowings" data-section="bs" data-search="Secured Short-Term Borrowings The daily fuel — short-term credit that keeps the cash cycle running. A business needs working capital: money to buy stock before selling it, money to pay staff while waiting for customers to pay. Short-term borrowings — Cash Credit (CC) limits, overdraft facilities, and short-term loans — bridge this gap. They are typically renewed annually with the bank. The level of CC drawn relative to the limit sanctioned shows how stretched working capital is.">
<div class="card-head"><h3>Secured Short-Term Borrowings</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The daily fuel — short-term credit that keeps the cash cycle running.&#8221;</p>
<p class="story">A business needs working capital: money to buy stock before selling it, money to pay staff while waiting for customers to pay. Short-term borrowings — Cash Credit (CC) limits, overdraft facilities, and short-term loans — bridge this gap. They are typically renewed annually with the bank. The level of CC drawn relative to the limit sanctioned shows how stretched working capital is.</p>
</div>
</div></div>
<div class="card" id="accounts-payable" data-section="bs" data-search="Accounts Payable Free money you owe suppliers — the interest-free loan they extend with every delivery. When a supplier delivers goods and gives 30, 60, or 90 days to pay, they are lending money for free. Accounts Payable is the total of all unpaid supplier invoices at any moment. Managing it well — using full credit terms without damaging relationships — is the cheapest possible source of working capital funding. A falling AP balance (paying suppliers faster than required) is silently giving away free funding.">
<div class="card-head"><h3>Accounts Payable</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Free money you owe suppliers — the interest-free loan they extend with every delivery.&#8221;</p>
<p class="story">When a supplier delivers goods and gives 30, 60, or 90 days to pay, they are lending money for free. Accounts Payable is the total of all unpaid supplier invoices at any moment. Managing it well — using full credit terms without damaging relationships — is the cheapest possible source of working capital funding. A falling AP balance (paying suppliers faster than required) is silently giving away free funding.</p>
</div>
</div></div>
<div class="card" id="short-term-provisions" data-section="bs" data-search="Short Term Provisions Bills you know are coming within the next year — set aside before they arrive. Short Term Provisions cover obligations expected within the next 12 months: income tax provision for the current year, employee bonus payable, leave encashment falling due, and product warranties expiring soon. They form part of Current Liabilities and affect the Current Ratio. These are not surprises — they are known obligations that disciplined financial management recognises and provides for before they become cash payments.">
<div class="card-head"><h3>Short Term Provisions</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Bills you know are coming within the next year — set aside before they arrive.&#8221;</p>
<p class="story">Short Term Provisions cover obligations expected within the next 12 months: income tax provision for the current year, employee bonus payable, leave encashment falling due, and product warranties expiring soon. They form part of Current Liabilities and affect the Current Ratio. These are not surprises — they are known obligations that disciplined financial management recognises and provides for before they become cash payments.</p>
</div>
</div></div>
<div class="card" id="current-deferred-tax-liabilities" data-section="bs" data-search="Current Deferred Tax Liabilities The near-term portion of postponed tax — timing differences expected to reverse within the year. Current Deferred Tax Liabilities represent the portion of deferred tax timing differences expected to reverse within the next 12 months. They form part of Current Liabilities and affect both the Current Ratio and the Quick Ratio. For most businesses this is a minor line — but where material, it indicates that accelerated tax relief claimed on short-duration items will require settlement in the near term.">
<div class="card-head"><h3>Current Deferred Tax Liabilities</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The near-term portion of postponed tax — timing differences expected to reverse within the year.&#8221;</p>
<p class="story">Current Deferred Tax Liabilities represent the portion of deferred tax timing differences expected to reverse within the next 12 months. They form part of Current Liabilities and affect both the Current Ratio and the Quick Ratio. For most businesses this is a minor line — but where material, it indicates that accelerated tax relief claimed on short-duration items will require settlement in the near term.</p>
</div>
</div></div>
<div class="card" id="other-current-liabilities" data-section="bs" data-search="Other Current Liabilities Short-term obligations outside standard categories — often the largest current liability line for distribution businesses. Other Current Liabilities captures everything owed within 12 months that is not trade payables, borrowings, or standard provisions: dealer security deposits, statutory dues payable (GST, TDS, PF employer contributions), advance payments received from customers, accrued expenses, and current lease liabilities. For trading and distribution businesses this line frequently exceeds trade payables. It directly affects Current Ratio and Quick Ratio calculations.">
<div class="card-head"><h3>Other Current Liabilities</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Short-term obligations outside standard categories — often the largest current liability line for distribution businesses.&#8221;</p>
<p class="story">Other Current Liabilities captures everything owed within 12 months that is not trade payables, borrowings, or standard provisions: dealer security deposits, statutory dues payable (GST, TDS, PF employer contributions), advance payments received from customers, accrued expenses, and current lease liabilities. For trading and distribution businesses this line frequently exceeds trade payables. It directly affects Current Ratio and Quick Ratio calculations.</p>
</div>
</div></div>
<div class="card" id="fixed-assets" data-section="bs" data-search="Fixed Assets The physical bones of the business — after their age shows. Fixed Assets represents the value of all long-term physical assets: land, buildings, machinery, vehicles, computers, and fit-out after deducting accumulated depreciation — the wear and tear the accountant has recognised since the asset was purchased. A machine bought for ₹10L 5 years ago at a 10-year life shows at ₹5L. Declining fixed assets without new capex means the business&#x27;s infrastructure is ageing.">
<div class="card-head"><h3>Fixed Assets</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The physical bones of the business — after their age shows.&#8221;</p>
<p class="story">Fixed Assets represents the value of all long-term physical assets: land, buildings, machinery, vehicles, computers, and fit-out after deducting accumulated depreciation — the wear and tear the accountant has recognised since the asset was purchased. A machine bought for ₹10L 5 years ago at a 10-year life shows at ₹5L. Declining fixed assets without new capex means the business&#x27;s infrastructure is ageing.</p>
</div>
</div></div>
<div class="card" id="non-current-investments" data-section="bs" data-search="Non-Current Investments Money the business has placed in other companies or long-term instruments — strategic, not operational. Non-Current Investments represent funds deployed in long-term financial assets: equity stakes in subsidiaries, joint ventures, or associate companies; long-term mutual funds; bonds and debentures held as strategic assets. These are not trading assets — they are held for strategic control, dividend income, or long-term appreciation. For operating businesses they are typically stable, changing only when the business acquires or divests strategic holdings.">
<div class="card-head"><h3>Non-Current Investments</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money the business has placed in other companies or long-term instruments — strategic, not operational.&#8221;</p>
<p class="story">Non-Current Investments represent funds deployed in long-term financial assets: equity stakes in subsidiaries, joint ventures, or associate companies; long-term mutual funds; bonds and debentures held as strategic assets. These are not trading assets — they are held for strategic control, dividend income, or long-term appreciation. For operating businesses they are typically stable, changing only when the business acquires or divests strategic holdings.</p>
</div>
</div></div>
<div class="card" id="long-term-loans-advances" data-section="bs" data-search="Long Term Loans &amp; Advances Money given out by the business — to be recovered after more than one year. Long Term Loans &amp; Advances represent funds the business has paid out and expects to recover, but not within the next 12 months: security deposits paid to landlords, capital advances to equipment suppliers awaiting delivery, loans to subsidiary or group companies, and long-term employee advances. These are assets, not expenses — but they are illiquid. A large and growing balance can indicate capital being transferred out of the operating business, formally or informally.">
<div class="card-head"><h3>Long Term Loans &amp; Advances</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money given out by the business — to be recovered after more than one year.&#8221;</p>
<p class="story">Long Term Loans &amp; Advances represent funds the business has paid out and expects to recover, but not within the next 12 months: security deposits paid to landlords, capital advances to equipment suppliers awaiting delivery, loans to subsidiary or group companies, and long-term employee advances. These are assets, not expenses — but they are illiquid. A large and growing balance can indicate capital being transferred out of the operating business, formally or informally.</p>
</div>
</div></div>
<div class="card" id="deferred-tax-assets" data-section="bs" data-search="Deferred Tax Assets Pre-paid tax — a benefit already earned through timing differences, to be offset against future tax bills. Deferred Tax Assets arise when the business has paid more tax than its accounting profit requires — typically because it has recognised an expense in its books before the tax authorities allow it — or when it has accumulated losses eligible to offset future profits. They represent future tax savings. A large and growing DTA, especially one backed by carried-forward losses, has no realisable value to a creditor if the business fails to generate future profits.">
<div class="card-head"><h3>Deferred Tax Assets</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Pre-paid tax — a benefit already earned through timing differences, to be offset against future tax bills.&#8221;</p>
<p class="story">Deferred Tax Assets arise when the business has paid more tax than its accounting profit requires — typically because it has recognised an expense in its books before the tax authorities allow it — or when it has accumulated losses eligible to offset future profits. They represent future tax savings. A large and growing DTA, especially one backed by carried-forward losses, has no realisable value to a creditor if the business fails to generate future profits.</p>
</div>
</div></div>
<div class="card" id="other-non-current-assets" data-section="bs" data-search="Other Non-Current Assets Long-term assets not classified elsewhere — prepayments, regulatory deposits, and deferred items. Other Non-Current Assets covers long-term assets not captured in standard categories: prepaid expenses extending beyond 12 months, long-term deposits with government or regulatory bodies, advance payments on multi-year contracts, and deferred acquisition costs. They are typically a small, stable line. Unusual growth in this category warrants investigation — it can occasionally be used to defer expenses that belong in the P&amp;L, making profits appear higher than they truly are.">
<div class="card-head"><h3>Other Non-Current Assets</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Long-term assets not classified elsewhere — prepayments, regulatory deposits, and deferred items.&#8221;</p>
<p class="story">Other Non-Current Assets covers long-term assets not captured in standard categories: prepaid expenses extending beyond 12 months, long-term deposits with government or regulatory bodies, advance payments on multi-year contracts, and deferred acquisition costs. They are typically a small, stable line. Unusual growth in this category warrants investigation — it can occasionally be used to defer expenses that belong in the P&amp;L, making profits appear higher than they truly are.</p>
</div>
</div></div>
<div class="card" id="current-investments" data-section="bs" data-search="Current Investments Short-term money put to work — liquid assets parked briefly before being needed. Current Investments are financial instruments the business holds temporarily: short-term mutual funds (liquid or ultra-short funds), treasury bills, commercial paper, and fixed deposits with maturity within 12 months. Unlike long-term investments (which are strategic), current investments are essentially a parking lot for surplus cash — kept here because they earn slightly more than a current account while remaining quickly accessible. A large and growing current investments balance is a positive signal: the business generates more cash than its immediate needs require. However, if borrowings are rising at the same time, it signals poor cash management — paying interest on loans while simultaneously earning lower returns on idle funds.">
<div class="card-head"><h3>Current Investments</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Short-term money put to work — liquid assets parked briefly before being needed.&#8221;</p>
<p class="story">Current Investments are financial instruments the business holds temporarily: short-term mutual funds (liquid or ultra-short funds), treasury bills, commercial paper, and fixed deposits with maturity within 12 months. Unlike long-term investments (which are strategic), current investments are essentially a parking lot for surplus cash — kept here because they earn slightly more than a current account while remaining quickly accessible. A large and growing current investments balance is a positive signal: the business generates more cash than its immediate needs require. However, if borrowings are rising at the same time, it signals poor cash management — paying interest on loans while simultaneously earning lower returns on idle funds.</p>
</div>
</div></div>
<div class="card" id="closing-stock" data-section="bs" data-search="Closing Stock Money wearing a warehouse costume — waiting to become a sale. Every unit of stock sitting in the warehouse was bought with cash or supplier credit. Until it is sold and collected, that cash is unavailable for anything else. Inventory is typically the largest single working capital item in product businesses — and the most prone to growing quietly. A ₹15L inventory balance means ₹15L of the business&#x27;s cash is currently wearing a warehouse costume, waiting for someone to buy it.">
<div class="card-head"><h3>Closing Stock</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money wearing a warehouse costume — waiting to become a sale.&#8221;</p>
<p class="story">Every unit of stock sitting in the warehouse was bought with cash or supplier credit. Until it is sold and collected, that cash is unavailable for anything else. Inventory is typically the largest single working capital item in product businesses — and the most prone to growing quietly. A ₹15L inventory balance means ₹15L of the business&#x27;s cash is currently wearing a warehouse costume, waiting for someone to buy it.</p>
</div>
</div></div>
<div class="card" id="short-term-loans-advances" data-section="bs" data-search="Short Term Loans &amp; Advances Money given out and expected back within the year — advances, prepayments, and near-term receivables. Short Term Loans &amp; Advances captures funds the business has paid out and expects to recover within 12 months: advance payments to suppliers for goods not yet received, prepaid expenses, security deposits refundable within a year, advance tax paid, and inter-company advances. They form part of Current Assets and affect the Current Ratio. An unusually large balance relative to purchase volumes warrants investigation — it can obscure informal short-term cash transfers.">
<div class="card-head"><h3>Short Term Loans &amp; Advances</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money given out and expected back within the year — advances, prepayments, and near-term receivables.&#8221;</p>
<p class="story">Short Term Loans &amp; Advances captures funds the business has paid out and expects to recover within 12 months: advance payments to suppliers for goods not yet received, prepaid expenses, security deposits refundable within a year, advance tax paid, and inter-company advances. They form part of Current Assets and affect the Current Ratio. An unusually large balance relative to purchase volumes warrants investigation — it can obscure informal short-term cash transfers.</p>
</div>
</div></div>
<div class="card" id="accounts-receivable" data-section="bs" data-search="Accounts Receivable Money you earned — but haven&#x27;t collected yet. Real revenue, not real cash. Every invoice raised but not yet paid by a customer sits in Accounts Receivable. This is genuine revenue — the goods have been delivered, the service rendered. But the cash hasn&#x27;t arrived yet. The longer customers take to pay, the more AR grows, and the more the business must borrow to fund the gap between earning and collecting. Falling AR means collecting faster — one of the highest-return activities a business can pursue.">
<div class="card-head"><h3>Accounts Receivable</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money you earned — but haven&#x27;t collected yet. Real revenue, not real cash.&#8221;</p>
<p class="story">Every invoice raised but not yet paid by a customer sits in Accounts Receivable. This is genuine revenue — the goods have been delivered, the service rendered. But the cash hasn&#x27;t arrived yet. The longer customers take to pay, the more AR grows, and the more the business must borrow to fund the gap between earning and collecting. Falling AR means collecting faster — one of the highest-return activities a business can pursue.</p>
</div>
</div></div>
<div class="card" id="cash-and-bank-balances" data-section="bs" data-search="Cash and Bank Balances The only number that tells you how the business truly is right now. After all the accounting theory, ratios, and forecasts — this is the simplest truth: how much is actually in the bank today? Cash and Bank Balances includes all current accounts, savings accounts, fixed deposits maturing within 90 days, and petty cash. It is the starting and ending point of the Cash Flow Statement, and the final arbiter of whether the business can pay tomorrow&#x27;s bills.">
<div class="card-head"><h3>Cash and Bank Balances</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The only number that tells you how the business truly is right now.&#8221;</p>
<p class="story">After all the accounting theory, ratios, and forecasts — this is the simplest truth: how much is actually in the bank today? Cash and Bank Balances includes all current accounts, savings accounts, fixed deposits maturing within 90 days, and petty cash. It is the starting and ending point of the Cash Flow Statement, and the final arbiter of whether the business can pay tomorrow&#x27;s bills.</p>
</div>
</div></div>
<div class="card" id="other-current-assets" data-section="bs" data-search="Other Current Assets Short-term assets that defy standard classification — accrued income and miscellaneous receivables. Other Current Assets typically includes interest accrued but not yet received, export incentives receivable, government subsidy receivables, GST input tax credits pending utilisation, and other miscellaneous short-term receivables. They form part of Current Assets and contribute to both the Current Ratio and Quick Ratio. The quality of these assets matters — some items may take months or years to collect despite their current balance sheet classification.">
<div class="card-head"><h3>Other Current Assets</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Short-term assets that defy standard classification — accrued income and miscellaneous receivables.&#8221;</p>
<p class="story">Other Current Assets typically includes interest accrued but not yet received, export incentives receivable, government subsidy receivables, GST input tax credits pending utilisation, and other miscellaneous short-term receivables. They form part of Current Assets and contribute to both the Current Ratio and Quick Ratio. The quality of these assets matters — some items may take months or years to collect despite their current balance sheet classification.</p>
</div>
</div></div>
<div class="card" id="branch" data-section="bs" data-search="Branch The inter-branch balance — funds in transit between head office and branch operations. In Tally and similar accounting systems, the Branch account represents the net balance between head office and branch operations. A positive balance means the branch owes the head office (funds sent to branch not yet absorbed into branch books). A negative means the head office owes the branch. This should ideally be zero at every reporting date. A persistent large balance indicates reconciliation backlogs, unresolved inter-entity transactions, or informal fund movements that require investigation.">
<div class="card-head"><h3>Branch</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The inter-branch balance — funds in transit between head office and branch operations.&#8221;</p>
<p class="story">In Tally and similar accounting systems, the Branch account represents the net balance between head office and branch operations. A positive balance means the branch owes the head office (funds sent to branch not yet absorbed into branch books). A negative means the head office owes the branch. This should ideally be zero at every reporting date. A persistent large balance indicates reconciliation backlogs, unresolved inter-entity transactions, or informal fund movements that require investigation.</p>
</div>
</div></div>
<div class="card" id="suspense" data-section="bs" data-search="Suspense Transactions awaiting classification — the accounting team&#x27;s unresolved pile. A Suspense account holds transactions that cannot yet be classified to their correct codes: cash received with purpose unknown, bank entries pending clarification, or system entries awaiting proper coding. In a well-run business, the Suspense balance is zero at every reporting date. A large or persistent Suspense balance indicates accounting backlogs, unreconciled transactions, or potential irregularities. Financial statements with a material Suspense balance should be treated with caution until the items are resolved.">
<div class="card-head"><h3>Suspense</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Transactions awaiting classification — the accounting team&#x27;s unresolved pile.&#8221;</p>
<p class="story">A Suspense account holds transactions that cannot yet be classified to their correct codes: cash received with purpose unknown, bank entries pending clarification, or system entries awaiting proper coding. In a well-run business, the Suspense balance is zero at every reporting date. A large or persistent Suspense balance indicates accounting backlogs, unreconciled transactions, or potential irregularities. Financial statements with a material Suspense balance should be treated with caution until the items are resolved.</p>
</div>
</div></div>
</div></div>
<div id="sec-pl" class="sec" data-section="pl" style="background:#F0F5FC"><div class="sec-hdr"><div class="sec-bar" style="background:#185FA5"></div><div class="sec-eye" style="color:#185FA5">Source Report 2</div><h2 class="sec-title">Profit &amp; Loss Report</h2></div><div class="cards-wrap"><p class="section-body">The Profit &amp; Loss Report records what happened during the period — the business activity. Unlike the Balance Sheet (a snapshot), the P&amp;L is a movie: every rupee earned and every rupee spent, from the first sale to the final retained profit. Read it from top to bottom: Revenue minus COGS gives Gross Profit; minus Overheads gives Operating Profit; minus Interest and Tax gives Net Profit.</p>
<div class="card" id="sales" data-section="pl" data-search="Sales The applause at the end of your show — loud, but it doesn&#x27;t pay the electricity bill. Sale is the total money the business earned from selling goods or services. No costs deducted yet. It is often called the top line because it sits at the top of the P&amp;L. Growing sales is necessary but not sufficient — it is only meaningful when margins are healthy and cash is being collected.">
<div class="card-head"><h3>Sales</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The applause at the end of your show — loud, but it doesn&#x27;t pay the electricity bill.&#8221;</p>
<p class="story">Sale is the total money the business earned from selling goods or services. No costs deducted yet. It is often called the top line because it sits at the top of the P&amp;L. Growing sales is necessary but not sufficient — it is only meaningful when margins are healthy and cash is being collected.</p>
</div>
</div></div>
<div class="card" id="cost-of-goods-sold-cogs" data-section="pl" data-search="Cost of Goods Sold (COGS) The mandatory toll on every rupee earned — the direct cost of making or buying what you sell. COGS is deducted from Sales before any overhead can be covered. For a trading business, it is the purchase price of goods sold. For a manufacturer, it includes raw materials, direct labour, and factory overhead.">
<div class="card-head"><h3>Cost of Goods Sold (COGS)</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The mandatory toll on every rupee earned — the direct cost of making or buying what you sell.&#8221;</p>
<p class="story">COGS is deducted from Sales before any overhead can be covered. For a trading business, it is the purchase price of goods sold. For a manufacturer, it includes raw materials, direct labour, and factory overhead.</p>
</div>
</div></div>
<div class="card" id="gross-profit" data-section="pl" data-search="Gross Profit  The gap between Sales and COGS is Gross Profit — the engine that must power everything else the business does. If COGS grows faster than Sales, that engine loses power with every passing month.">
<div class="card-head"><h3>Gross Profit</h3></div>
<div class="card-body">
<p class="story">The gap between Sales and COGS is Gross Profit — the engine that must power everything else the business does. If COGS grows faster than Sales, that engine loses power with every passing month.</p>
</div>
</div></div>
<div class="card" id="employee-benefit-expenses" data-section="pl" data-search="Employee Benefit Expenses The total cost of your people — salaries, statutory contributions, and everything the business owes its workforce. Employee Benefit Expenses is the complete workforce cost that goes into overheads: basic salaries, house rent allowance, Provident Fund and ESIC employer contributions, gratuity, bonus, leave encashment, and other staff-related costs. Unlike direct labour (which sits in COGS for manufacturing businesses), this line covers management, administration, sales, and support staff. It is typically the largest overhead item and the first place to investigate when overheads grow faster than revenue.">
<div class="card-head"><h3>Employee Benefit Expenses</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The total cost of your people — salaries, statutory contributions, and everything the business owes its workforce.&#8221;</p>
<p class="story">Employee Benefit Expenses is the complete workforce cost that goes into overheads: basic salaries, house rent allowance, Provident Fund and ESIC employer contributions, gratuity, bonus, leave encashment, and other staff-related costs. Unlike direct labour (which sits in COGS for manufacturing businesses), this line covers management, administration, sales, and support staff. It is typically the largest overhead item and the first place to investigate when overheads grow faster than revenue.</p>
</div>
</div></div>
<div class="card" id="selling-admin-expenses" data-section="pl" data-search="Selling &amp; Admin Expenses The cost of winning business and keeping the lights on. Selling &amp; Admin Expenses covers the cost of acquiring customers — advertising, sales commissions, trade promotions, travel and entertainment — and running the administrative machinery: office costs, professional fees, IT, insurance, and other general administrative overheads. These are largely discretionary and can be reduced during downturns, but cannot be eliminated without damaging either market presence or operational capability. The trend as a percentage of revenue matters more than the absolute amount.">
<div class="card-head"><h3>Selling &amp; Admin Expenses</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The cost of winning business and keeping the lights on.&#8221;</p>
<p class="story">Selling &amp; Admin Expenses covers the cost of acquiring customers — advertising, sales commissions, trade promotions, travel and entertainment — and running the administrative machinery: office costs, professional fees, IT, insurance, and other general administrative overheads. These are largely discretionary and can be reduced during downturns, but cannot be eliminated without damaging either market presence or operational capability. The trend as a percentage of revenue matters more than the absolute amount.</p>
</div>
</div></div>
<div class="card" id="other-expenses" data-section="pl" data-search="Other Expenses The catch-all overhead bucket — individually small, collectively significant. Other Expenses is the residual overhead category: repairs and maintenance, power and fuel for non-manufacturing operations, packing materials, freight outward, printing and stationery, and other miscellaneous operating costs. While individually small, these items accumulate and can grow unnoticed. A sudden spike in Other Expenses warrants immediate line-by-line investigation — it can hide irregular payments, misclassified capital expenses, or unusual one-off costs.">
<div class="card-head"><h3>Other Expenses</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The catch-all overhead bucket — individually small, collectively significant.&#8221;</p>
<p class="story">Other Expenses is the residual overhead category: repairs and maintenance, power and fuel for non-manufacturing operations, packing materials, freight outward, printing and stationery, and other miscellaneous operating costs. While individually small, these items accumulate and can grow unnoticed. A sudden spike in Other Expenses warrants immediate line-by-line investigation — it can hide irregular payments, misclassified capital expenses, or unusual one-off costs.</p>
</div>
</div></div>
<div class="card" id="depreciation" data-section="pl" data-search="Depreciation The accountant&#x27;s way of acknowledging everything ages — even when no cash changes hands. When a business buys a ₹10L machine, it does not record ₹10L as an expense that year. Instead, it spreads the cost over the machine&#x27;s useful life — say 10 years at ₹1L per year. That annual charge is depreciation. It reduces accounting profit — but no cash leaves the bank for it each year. That is precisely why EBITDA (which adds back depreciation) is closer to the cash the business generates than EBIT.">
<div class="card-head"><h3>Depreciation</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The accountant&#x27;s way of acknowledging everything ages — even when no cash changes hands.&#8221;</p>
<p class="story">When a business buys a ₹10L machine, it does not record ₹10L as an expense that year. Instead, it spreads the cost over the machine&#x27;s useful life — say 10 years at ₹1L per year. That annual charge is depreciation. It reduces accounting profit — but no cash leaves the bank for it each year. That is precisely why EBITDA (which adds back depreciation) is closer to the cash the business generates than EBIT.</p>
</div>
</div></div>
<div class="card" id="finance-cost" data-section="pl" data-search="Finance Cost The rent you pay the bank for borrowing their money. Every rupee borrowed comes at a cost — the interest charge. Finance cost is the total interest paid across all borrowings: term loans, working capital CC, overdrafts, and other facilities. It appears below the Operating Profit line on the P&amp;L, intentionally separating the business&#x27;s operational performance (Operating Profit) from its financial structure (how it is funded). A rising Finance Cost with stable EBITDA is the first sign of deteriorating Interest Cover.">
<div class="card-head"><h3>Finance Cost</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The rent you pay the bank for borrowing their money.&#8221;</p>
<p class="story">Every rupee borrowed comes at a cost — the interest charge. Finance cost is the total interest paid across all borrowings: term loans, working capital CC, overdrafts, and other facilities. It appears below the Operating Profit line on the P&amp;L, intentionally separating the business&#x27;s operational performance (Operating Profit) from its financial structure (how it is funded). A rising Finance Cost with stable EBITDA is the first sign of deteriorating Interest Cover.</p>
</div>
</div></div>
<div class="card" id="other-non-operating-income" data-section="pl" data-search="Other Non-Operating Income Earnings from outside the core business — welcome, but never rely on them. Non-operating income includes interest received on deposits, rental income from owned property, dividends from investments, and one-time gains. It appears after Finance Cost and supplements core business profit. The danger: when non-operating income props up an otherwise loss-making operation, the business looks profitable on paper while the core engine is failing. When other income exceeds 50% of EBITDA, it is a structural dependency, not a strength.">
<div class="card-head"><h3>Other Non-Operating Income</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Earnings from outside the core business — welcome, but never rely on them.&#8221;</p>
<p class="story">Non-operating income includes interest received on deposits, rental income from owned property, dividends from investments, and one-time gains. It appears after Finance Cost and supplements core business profit. The danger: when non-operating income props up an otherwise loss-making operation, the business looks profitable on paper while the core engine is failing. When other income exceeds 50% of EBITDA, it is a structural dependency, not a strength.</p>
</div>
</div></div>
<div class="card" id="other-non-operating-expenses" data-section="pl" data-search="Other Non-Operating Expenses Costs from outside the core business — losses, write-offs, and one-time charges. Other Non-Operating Expenses captures costs not arising from the core trading activity: losses on asset disposals, foreign exchange losses on borrowings or payables, penalties and fines, write-offs of irrecoverable balances, and one-time restructuring charges. They appear below Operating Profit and reduce PBT. The key question is always: are these truly one-time events, or are they recurring items being labelled as non-operating to protect the appearance of core operating performance?">
<div class="card-head"><h3>Other Non-Operating Expenses</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Costs from outside the core business — losses, write-offs, and one-time charges.&#8221;</p>
<p class="story">Other Non-Operating Expenses captures costs not arising from the core trading activity: losses on asset disposals, foreign exchange losses on borrowings or payables, penalties and fines, write-offs of irrecoverable balances, and one-time restructuring charges. They appear below Operating Profit and reduce PBT. The key question is always: are these truly one-time events, or are they recurring items being labelled as non-operating to protect the appearance of core operating performance?</p>
</div>
</div></div>
<div class="card" id="extra-ordinary-income" data-section="pl" data-search="Extra Ordinary Income A one-off windfall — real money, but plan nothing around seeing it again. Extraordinary Income represents genuinely unusual, infrequent income outside normal operations: profit on sale of a major fixed asset, large insurance claim settlements, reversal of previously written-off debts, or significant government grants. They are recorded separately to prevent distortion of the recurring performance picture. Cash ProfitNiti AI&#x27;s CFO Mastery analysis always checks whether extraordinary income is propping up an otherwise marginal business — a structural risk that must be explicitly named.">
<div class="card-head"><h3>Extra Ordinary Income</h3></div>
<div class="card-body">
<p class="tagline">&#8220;A one-off windfall — real money, but plan nothing around seeing it again.&#8221;</p>
<p class="story">Extraordinary Income represents genuinely unusual, infrequent income outside normal operations: profit on sale of a major fixed asset, large insurance claim settlements, reversal of previously written-off debts, or significant government grants. They are recorded separately to prevent distortion of the recurring performance picture. Cash ProfitNiti AI&#x27;s CFO Mastery analysis always checks whether extraordinary income is propping up an otherwise marginal business — a structural risk that must be explicitly named.</p>
</div>
</div></div>
<div class="card" id="extra-ordinary-expense" data-section="pl" data-search="Extra Ordinary Expense A one-off hit — painful this year, but should not repeat itself. Extraordinary Expenses are unusual, non-recurring charges: major impairments of assets or investments, large restructuring and redundancy costs, significant litigation settlements, or substantial losses from natural disasters. They reduce reported profit in the period but are segregated to prevent distortion of the underlying performance trend. When &#x27;extraordinary&#x27; expenses appear every year, they are not extraordinary — they are a structural cost that management must address directly rather than bury below the operating line.">
<div class="card-head"><h3>Extra Ordinary Expense</h3></div>
<div class="card-body">
<p class="tagline">&#8220;A one-off hit — painful this year, but should not repeat itself.&#8221;</p>
<p class="story">Extraordinary Expenses are unusual, non-recurring charges: major impairments of assets or investments, large restructuring and redundancy costs, significant litigation settlements, or substantial losses from natural disasters. They reduce reported profit in the period but are segregated to prevent distortion of the underlying performance trend. When &#x27;extraordinary&#x27; expenses appear every year, they are not extraordinary — they are a structural cost that management must address directly rather than bury below the operating line.</p>
</div>
</div></div>
<div class="card" id="pbt" data-section="pl" data-search="PBT The government&#x27;s starting point — profit before they take their share. Profit Before Tax is the total profit after all operating costs, interest, non-operating income and expenses — before income tax is deducted. It is the number the Income Tax department uses as its starting point (after further adjustments for disallowances and deductions). A large difference between PBT and PAT beyond the standard 25–30% corporate tax rate signals either deferred tax adjustments or reassessments from earlier years that require explanation.">
<div class="card-head"><h3>PBT</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The government&#x27;s starting point — profit before they take their share.&#8221;</p>
<p class="story">Profit Before Tax is the total profit after all operating costs, interest, non-operating income and expenses — before income tax is deducted. It is the number the Income Tax department uses as its starting point (after further adjustments for disallowances and deductions). A large difference between PBT and PAT beyond the standard 25–30% corporate tax rate signals either deferred tax adjustments or reassessments from earlier years that require explanation.</p>
</div>
</div></div>
<div class="card" id="profit-after-tax-pat" data-section="pl" data-search="Profit After Tax (PAT) What the owner finally keeps after every single cost — the official bottom line. Sales minus COGS minus Overheads minus Depreciation minus Finance Cost minus Tax equals Net Profit. This is the number most business owners think of as &#x27;their&#x27; profit — and yet, it does not equal cash in the bank. The gap between PAT and actual cash (Net Cash Flow) is always explained by working capital movements. PAT is an accounting score. Cash is the reality.">
<div class="card-head"><h3>Profit After Tax (PAT)</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What the owner finally keeps after every single cost — the official bottom line.&#8221;</p>
<p class="story">Sales minus COGS minus Overheads minus Depreciation minus Finance Cost minus Tax equals Net Profit. This is the number most business owners think of as &#x27;their&#x27; profit — and yet, it does not equal cash in the bank. The gap between PAT and actual cash (Net Cash Flow) is always explained by working capital movements. PAT is an accounting score. Cash is the reality.</p>
</div>
</div></div>
<div class="card" id="dividend-paid" data-section="pl" data-search="Dividend paid Cash returned to shareholders — a reward for ownership, but one that slows equity accumulation. Dividend Paid is the cash distributed to shareholders from the current year&#x27;s or accumulated profits, declared by the Board of Directors. Unlike proprietor drawings, dividends are formal corporate distributions. For Cash ProfitNiti AI, dividends reduce Retained Profit and slow equity build-up. Appropriate in profitable years; a serious governance concern when paid from reserves despite current losses, or when funded by new borrowing.">
<div class="card-head"><h3>Dividend paid</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Cash returned to shareholders — a reward for ownership, but one that slows equity accumulation.&#8221;</p>
<p class="story">Dividend Paid is the cash distributed to shareholders from the current year&#x27;s or accumulated profits, declared by the Board of Directors. Unlike proprietor drawings, dividends are formal corporate distributions. For Cash ProfitNiti AI, dividends reduce Retained Profit and slow equity build-up. Appropriate in profitable years; a serious governance concern when paid from reserves despite current losses, or when funded by new borrowing.</p>
</div>
</div></div>
<div class="card" id="retained-profit" data-section="pl" data-search="Retained Profit What stays in the business after the owner takes their draw — the seed corn of growth. Retained profit is PAT minus whatever the owner withdrew (distributions or dividends). It is the only internal source of equity growth. Every rupee retained reduces the need for borrowing or external equity. A business that retains nothing stays forever dependent on banks for growth. The Retained Profit from the P&amp;L should exactly match the increase in Equity on the Balance Sheet — Cash ProfitNiti AI uses this as a built-in data validation check.">
<div class="card-head"><h3>Retained Profit</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What stays in the business after the owner takes their draw — the seed corn of growth.&#8221;</p>
<p class="story">Retained profit is PAT minus whatever the owner withdrew (distributions or dividends). It is the only internal source of equity growth. Every rupee retained reduces the need for borrowing or external equity. A business that retains nothing stays forever dependent on banks for growth. The Retained Profit from the P&amp;L should exactly match the increase in Equity on the Balance Sheet — Cash ProfitNiti AI uses this as a built-in data validation check.</p>
</div>
</div></div>
</div></div>
<div id="sec-cf" class="sec" data-section="cf" style="background:#FBF8F0"><div class="sec-hdr"><div class="sec-bar" style="background:#854F0B"></div><div class="sec-eye" style="color:#854F0B">Source Report 3</div><h2 class="sec-title">Cash Flow Statement</h2></div><div class="cards-wrap"><p class="section-body">The Cash Flow Statement answers the question the P&amp;L cannot: where did the cash actually go? It takes the accounting profit and reconciles it to the real change in the bank balance — showing every rupee that came in and every rupee that went out, grouped into three activities: Operations (core business), Investing (assets), and Financing (borrowings and equity).</p>
<div class="card" id="cash-flows-from-operations" data-section="cf" data-search="Cash Flows from Operations The truth test — did the core business actually generate cash, or did it consume it? This section starts with net profit, adds back non-cash charges (depreciation), and then adjusts for working capital movements. An increase in debtors or stock consumes cash (more money is sitting in the cycle). A decrease releases cash. An increase in creditors provides cash. The result — Operating Cash Flow — is the single most important number in Cash ProfitNiti AI. If this is positive and growing, the business is fundamentally healthy. If negative, it is consuming cash with every transaction.">
<div class="card-head"><h3>Cash Flows from Operations</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The truth test — did the core business actually generate cash, or did it consume it?&#8221;</p>
<p class="story">This section starts with net profit, adds back non-cash charges (depreciation), and then adjusts for working capital movements. An increase in debtors or stock consumes cash (more money is sitting in the cycle). A decrease releases cash. An increase in creditors provides cash. The result — Operating Cash Flow — is the single most important number in Cash ProfitNiti AI. If this is positive and growing, the business is fundamentally healthy. If negative, it is consuming cash with every transaction.</p>
</div>
</div></div>
<div class="card" id="cash-flows-from-investing" data-section="cf" data-search="Cash Flows from Investing The cost of building tomorrow — money spent on assets that will generate future returns. Every rupee spent buying new machines, buildings, vehicles, or long-term investments shows here as a negative number (cash out). Proceeds from selling fixed assets show as positive. This section answers: is the business investing for growth, maintaining its asset base, or running down its infrastructure? In a healthy business, Capital Expenditure is funded from Operating Cash Flow — it is concerning when it is funded by new borrowing alone.">
<div class="card-head"><h3>Cash Flows from Investing</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The cost of building tomorrow — money spent on assets that will generate future returns.&#8221;</p>
<p class="story">Every rupee spent buying new machines, buildings, vehicles, or long-term investments shows here as a negative number (cash out). Proceeds from selling fixed assets show as positive. This section answers: is the business investing for growth, maintaining its asset base, or running down its infrastructure? In a healthy business, Capital Expenditure is funded from Operating Cash Flow — it is concerning when it is funded by new borrowing alone.</p>
</div>
</div></div>
<div class="card" id="cash-flows-from-financing" data-section="cf" data-search="Cash Flows from Financing Money borrowed, repaid, raised, and returned — the bank and owner&#x27;s cash flows. New borrowings appear here as positive cash inflows. Loan repayments appear as negative. Equity raised from new investors is positive. Dividends and distributions paid are negative. This section reveals whether the business is building or reducing its dependence on external funding — and whether it can generate its own cash or relies on banks to survive. A business that consistently shows large positive financing flows (new borrowings) alongside negative operating flows is using debt to fund operations.">
<div class="card-head"><h3>Cash Flows from Financing</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Money borrowed, repaid, raised, and returned — the bank and owner&#x27;s cash flows.&#8221;</p>
<p class="story">New borrowings appear here as positive cash inflows. Loan repayments appear as negative. Equity raised from new investors is positive. Dividends and distributions paid are negative. This section reveals whether the business is building or reducing its dependence on external funding — and whether it can generate its own cash or relies on banks to survive. A business that consistently shows large positive financing flows (new borrowings) alongside negative operating flows is using debt to fund operations.</p>
</div>
</div></div>
</div></div>
<div id="sec-fns" class="sec" data-section="fns" style="background:#F5F4FE"><div class="sec-hdr"><div class="sec-bar" style="background:#534AB7"></div><div class="sec-eye" style="color:#534AB7">Source Report 4</div><h2 class="sec-title">Financial Summary</h2></div><div class="cards-wrap"><p class="section-body">The Financial Summary combines the key lines from both the P&amp;L and Balance Sheet into one condensed view. It is not a replacement for either — it is a quick-scan consolidation that allows an instant assessment of whether revenue, margins, assets, and funding are moving in the right direction. The most important feature: the Validation row.</p>
<div class="info-box">The Validation row verifies that Assets = Liabilities + Equity (the fundamental accounting equation). If this row equals zero: data is clean — Cash ProfitNiti AI proceeds with all calculations. If non-zero: there is a data discrepancy between the P&amp;L and Balance Sheet. Cash ProfitNiti AI shows an amber warning on that period and advises verification before trusting ratios.  The Validation Row — the most important line in the Financial Summary</div>
</div></div>
<div id="sec-area1" class="sec" data-section="area1" style="background:#F4FBF8"><div class="area-hdr" style="border-bottom:1px solid #1D9E7530"><div class="area-num" style="background:#1D9E75">1</div><div><div class="area-name" style="color:#1D9E75">Profit Power</div><div class="area-sub">Revenue · Margins · Overheads · Operating Profit · Profitability Ratios</div></div></div><div class="cards-wrap"><div class="card" id="revenue-growth" data-section="area1" data-search="Revenue Growth % Are you running faster than last period — or just running in place? Revenue Growth % compares this period&#x27;s sales to the prior period. It answers the first question every banker asks. But here is the trap: growing revenue means nothing if COGS and overheads are growing faster. Always read Revenue Growth % alongside COGS Growth % and Overheads Growth % together — the three together reveal whether growth is creating or destroying value.">
<div class="card-head"><h3>Revenue Growth %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Are you running faster than last period — or just running in place?&#8221;</p>
<p class="story">Revenue Growth % compares this period&#x27;s sales to the prior period. It answers the first question every banker asks. But here is the trap: growing revenue means nothing if COGS and overheads are growing faster. Always read Revenue Growth % alongside COGS Growth % and Overheads Growth % together — the three together reveal whether growth is creating or destroying value.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Revenue Growth % = (Current Revenue − Prior Revenue) ÷ Prior Revenue × 100</code></div>
</div>
</div></div>
<div class="card" id="cogs-growth" data-section="area1" data-search="COGS Growth % Are your production costs running faster than your sales? Think of Revenue and COGS as two runners in a race. If COGS is consistently running faster, the gross margin shrinks with every period — even as total sales grow. COGS Growth % is the speed monitor for purchasing or production costs. Read it alongside Revenue Growth % always. The gap between them is the gross margin direction signal: widening gap = improving margin; narrowing or negative gap = compressing margin.">
<div class="card-head"><h3>COGS Growth %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Are your production costs running faster than your sales?&#8221;</p>
<p class="story">Think of Revenue and COGS as two runners in a race. If COGS is consistently running faster, the gross margin shrinks with every period — even as total sales grow. COGS Growth % is the speed monitor for purchasing or production costs. Read it alongside Revenue Growth % always. The gap between them is the gross margin direction signal: widening gap = improving margin; narrowing or negative gap = compressing margin.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">COGS Growth % = (Current COGS − Prior COGS) ÷ Prior COGS × 100</code></div>
</div>
</div></div>
<div class="card" id="gross-margin" data-section="area1" data-search="Gross Margin % Your business&#x27;s engine size — everything else runs on what this produces. Imagine your business is a water tank. Revenue fills it from the top. COGS is a mandatory hole at the bottom — the moment a sale is made, that much flows out. Gross Margin % is how full the tank is after that leak. Every rupee of rent, salary, interest, and profit must come from what remains. If the Gross Margin % is 29%, then ₹29 from every ₹100 earned is available for everything else. If overheads consume ₹18.39, only ₹10.61 remains as operating profit.">
<div class="card-head"><h3>Gross Margin %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Your business&#x27;s engine size — everything else runs on what this produces.&#8221;</p>
<p class="story">Imagine your business is a water tank. Revenue fills it from the top. COGS is a mandatory hole at the bottom — the moment a sale is made, that much flows out. Gross Margin % is how full the tank is after that leak. Every rupee of rent, salary, interest, and profit must come from what remains. If the Gross Margin % is 29%, then ₹29 from every ₹100 earned is available for everything else. If overheads consume ₹18.39, only ₹10.61 remains as operating profit.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Gross Margin % = Gross Margin ÷ Revenue × 100</code></div>
<div class="fbox"><strong class="fl">Gross Margin:</strong> <code class="fc">= Revenue – COGS</code></div>
</div>
</div></div>
<div class="card" id="overheads-and-overheads-growth" data-section="area1" data-search="Overheads % and Overheads Growth % The fixed membership fee your business charges itself — and whether it is getting more affordable. Overheads are costs the business incurs simply by existing — rent, staff, utilities, subscriptions — regardless of whether it sells anything that day. The critical question is not the rupee amount but the percentage relationship: are overheads growing slower than revenue (operating leverage working) or faster (leverage in reverse)? The Overheads Growth % vs Revenue Growth % gap tells this story precisely.">
<div class="card-head"><h3>Overheads % and Overheads Growth %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The fixed membership fee your business charges itself — and whether it is getting more affordable.&#8221;</p>
<p class="story">Overheads are costs the business incurs simply by existing — rent, staff, utilities, subscriptions — regardless of whether it sells anything that day. The critical question is not the rupee amount but the percentage relationship: are overheads growing slower than revenue (operating leverage working) or faster (leverage in reverse)? The Overheads Growth % vs Revenue Growth % gap tells this story precisely.</p>
<div class="fbox"><strong class="fl">Overheads %:</strong> <code class="fc">Overheads % = Total Overheads ÷ Revenue × 100</code></div>
<div class="fbox"><strong class="fl">Overheads Growth %:</strong> <code class="fc">Overheads Growth % = (Current OH − Prior OH) ÷ Prior OH × 100</code></div>
</div>
</div></div>
<div class="card" id="operating-profit-ebit-and-operating-profit" data-section="area1" data-search="Operating Profit (EBIT) and Operating Profit % What the business earns from its core job — before the bank and government take their share. Picture a mango orchard. Revenue is all mangoes harvested. COGS is the cost of seeds, fertiliser, and labour directly in the orchard. Overheads are the management office, vehicles, and storage. Operating Profit is what remains after running the entire orchard — before paying the bank that financed it (interest) or the government (tax). This pure trading result is the best measure of management&#x27;s performance because it excludes financing decisions.">
<div class="card-head"><h3>Operating Profit (EBIT) and Operating Profit %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What the business earns from its core job — before the bank and government take their share.&#8221;</p>
<p class="story">Picture a mango orchard. Revenue is all mangoes harvested. COGS is the cost of seeds, fertiliser, and labour directly in the orchard. Overheads are the management office, vehicles, and storage. Operating Profit is what remains after running the entire orchard — before paying the bank that financed it (interest) or the government (tax). This pure trading result is the best measure of management&#x27;s performance because it excludes financing decisions.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Operating Profit (EBIT) = Gross Margin − Total Overheads (incl. Depreciation)</code></div>
<div class="fbox"><strong class="fl">Operating Profit %:</strong> <code class="fc">= Operating Profit ÷ Revenue × 100</code></div>
</div>
</div></div>
<div class="card" id="ebitda-and-operating-cash-profit" data-section="area1" data-search="EBITDA and Operating Cash Profit The cash engine output — EBITDA is Operating Profit before the non-cash accounting deductions. Depreciation is an accounting entry — no cash leaves the bank for it each year. Adding it back to Operating Profit gives EBITDA: the closest P&amp;L proxy for the cash the business operations could generate. Banks use EBITDA for Debt Payback. Cash ProfitNiti AI calls it &#x27;Operating Cash Profit&#x27; — the theoretical ceiling of cash the business could produce if every working capital transaction settled instantly. The gap between this and actual Operating Cash Flow is always the Working Capital movement.">
<div class="card-head"><h3>EBITDA and Operating Cash Profit</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The cash engine output — EBITDA is Operating Profit before the non-cash accounting deductions.&#8221;</p>
<p class="story">Depreciation is an accounting entry — no cash leaves the bank for it each year. Adding it back to Operating Profit gives EBITDA: the closest P&amp;L proxy for the cash the business operations could generate. Banks use EBITDA for Debt Payback. Cash ProfitNiti AI calls it &#x27;Operating Cash Profit&#x27; — the theoretical ceiling of cash the business could produce if every working capital transaction settled instantly. The gap between this and actual Operating Cash Flow is always the Working Capital movement.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">EBITDA = Operating Profit + Depreciation &amp; Amortisation</code></div>
<div class="fbox"><strong class="fl">Also called:</strong> <code class="fc">Operating Cash Profit — the theoretical cash ceiling</code></div>
<div class="fbox"><strong class="fl">Used for:</strong> <code class="fc">Debt Payback = Net Debt ÷ EBITDA  ·  Cash Flow Quality = OCF ÷ EBITDA</code></div>
</div>
</div></div>
<div class="card" id="net-profit-and-retained-profit" data-section="area1" data-search="Net Profit % and Retained Profit What the owner keeps — and what the business keeps for itself. You earn ₹66L revenue. After costs, interest and tax, ₹4.10L remains — that is net profit. You draw ₹1.50L. Only ₹2.60L stays. This is the reality: the journey from ₹100 earned to rupees kept involves COGS (₹71), Overheads (₹18.39), Depreciation (₹1.51), Interest (₹2.66), and Tax (₹1.74). Understanding this journey — which step consumes how much — is the entire purpose of Financial Area 1.">
<div class="card-head"><h3>Net Profit % and Retained Profit</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What the owner keeps — and what the business keeps for itself.&#8221;</p>
<p class="story">You earn ₹66L revenue. After costs, interest and tax, ₹4.10L remains — that is net profit. You draw ₹1.50L. Only ₹2.60L stays. This is the reality: the journey from ₹100 earned to rupees kept involves COGS (₹71), Overheads (₹18.39), Depreciation (₹1.51), Interest (₹2.66), and Tax (₹1.74). Understanding this journey — which step consumes how much — is the entire purpose of Financial Area 1.</p>
<div class="fbox"><strong class="fl">Net Profit %:</strong> <code class="fc">Net Profit % = Net Profit After Tax ÷ Revenue × 100</code></div>
<div class="fbox"><strong class="fl">Retained Profit:</strong> <code class="fc">Retained Profit = Net Profit − Distributions / Dividends Paid</code></div>
</div>
</div></div>
<div class="card" id="interest-cover" data-section="area1" data-search="Interest Cover Can the business pay its rent to the bank — and how many times over? Your interest expense is ₹1L per month. Your operating profit is ₹4L. Interest cover is 4x — you earn 4 times what you owe the bank. If operating profit drops to ₹1.5L, cover is 1.5x — dangerously thin. If it drops below ₹1L, you cannot pay interest from operations. Banks start taking notice when Interest Cover falls below 2x. Below 1.5x triggers covenant reviews. Below 1x means the business is technically not servicing its debt.">
<div class="card-head"><h3>Interest Cover</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Can the business pay its rent to the bank — and how many times over?&#8221;</p>
<p class="story">Your interest expense is ₹1L per month. Your operating profit is ₹4L. Interest cover is 4x — you earn 4 times what you owe the bank. If operating profit drops to ₹1.5L, cover is 1.5x — dangerously thin. If it drops below ₹1L, you cannot pay interest from operations. Banks start taking notice when Interest Cover falls below 2x. Below 1.5x triggers covenant reviews. Below 1x means the business is technically not servicing its debt.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Interest Cover = Operating Profit (EBIT) ÷ Finance Cost (Interest Paid)</code></div>
</div>
</div></div>
<div class="card" id="break-even-sales" data-section="area1" data-search="Break Even Sales The minimum altitude your business must maintain to keep flying. A commercial aircraft must maintain minimum altitude to stay airborne. Break Even Sales is the revenue level below which every rupee earned creates a loss. It is calculated by asking: how much revenue is needed to generate enough Gross Margin to cover all overheads exactly? Below break even, the business is losing money. Every rupee above break even is pure operating profit.">
<div class="card-head"><h3>Break Even Sales</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The minimum altitude your business must maintain to keep flying.&#8221;</p>
<p class="story">A commercial aircraft must maintain minimum altitude to stay airborne. Break Even Sales is the revenue level below which every rupee earned creates a loss. It is calculated by asking: how much revenue is needed to generate enough Gross Margin to cover all overheads exactly? Below break even, the business is losing money. Every rupee above break even is pure operating profit.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Break Even Sales = Total Overheads (incl. Depreciation) ÷ Gross Margin %</code></div>
</div>
</div></div>
</div></div>
<div id="sec-area2" class="sec" data-section="area2" style="background:#EDF4FC"><div class="area-hdr" style="border-bottom:1px solid #185FA530"><div class="area-num" style="background:#185FA5">2</div><div><div class="area-name" style="color:#185FA5">Cash Management</div><div class="area-sub">AR Days · Inventory Days · AP Days · Working Capital Cycle · Liquidity Ratios</div></div></div><div class="cards-wrap"><p class="section-body">Working capital is the cash trapped in the business cycle — money that has left the bank to buy stock or fund debtors but has not yet returned from customer collections. Financial Area 2 measures how efficiently this cycle operates. The goal: minimum days in AR, minimum days in inventory, maximum days in AP.</p>
<div class="card" id="accounts-receivable-days-ar-days" data-section="area2" data-search="Accounts Receivable Days (AR Days) How many days your money goes on holiday in your customer&#x27;s bank account. You delivered goods on 1st January. Your terms say payment in 30 days. Today is 1st March and you still haven&#x27;t been paid. That is 60 days of your money sitting in someone else&#x27;s account — at zero interest, funded by your bank overdraft at 12–14%. AR Days measures the average length of that holiday across all customers. Reducing AR Days by 10 days on ₹1 Crore revenue releases ₹2,74,000 of cash instantly — from your own business, with zero new sales.">
<div class="card-head"><h3>Accounts Receivable Days (AR Days)</h3></div>
<div class="card-body">
<p class="tagline">&#8220;How many days your money goes on holiday in your customer&#x27;s bank account.&#8221;</p>
<p class="story">You delivered goods on 1st January. Your terms say payment in 30 days. Today is 1st March and you still haven&#x27;t been paid. That is 60 days of your money sitting in someone else&#x27;s account — at zero interest, funded by your bank overdraft at 12–14%. AR Days measures the average length of that holiday across all customers. Reducing AR Days by 10 days on ₹1 Crore revenue releases ₹2,74,000 of cash instantly — from your own business, with zero new sales.</p>
<div class="fbox"><strong class="fl">Formula (Annual):</strong> <code class="fc">AR Days = (Previous Year Accounts Receivable+ Current Year receivable)/2 ÷ Revenue × 365</code></div>
<div class="fbox"><strong class="fl">Formula (Monthly):</strong> <code class="fc">AR Days = (Previous month Accounts Receivable + Current month Accounts Receivable)/2 ÷ Monthly Revenue × 30</code></div>
</div>
</div></div>
<div class="card" id="inventory-days" data-section="area2" data-search="Inventory Days Rupees sitting in a warehouse pretending to be productive. Imagine walking into your warehouse and seeing ₹15L of stock on shelves. That stock is not product — it is money wearing a costume. Every day it sits there, it costs you storage, insurance, obsolescence risk, and the interest on the borrowing used to buy it. Inventory Days measures how long stock sits before being sold. For ABC Pvt Ltd at 120 days: stock takes 4 months to turn. The cash cost of every extra day: ₹12,862.">
<div class="card-head"><h3>Inventory Days</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Rupees sitting in a warehouse pretending to be productive.&#8221;</p>
<p class="story">Imagine walking into your warehouse and seeing ₹15L of stock on shelves. That stock is not product — it is money wearing a costume. Every day it sits there, it costs you storage, insurance, obsolescence risk, and the interest on the borrowing used to buy it. Inventory Days measures how long stock sits before being sold. For ABC Pvt Ltd at 120 days: stock takes 4 months to turn. The cash cost of every extra day: ₹12,862.</p>
<div class="fbox"><strong class="fl">Formula (Annual):</strong> <code class="fc">Inventory Days = Inventory ÷ COGS × 365</code></div>
<div class="fbox"><strong class="fl">Formula (Monthly):</strong> <code class="fc">Inventory Days = (Previous Month Inventory + Current Month Inventory)/2 ÷ Monthly COGS × 30</code></div>
</div>
</div></div>
<div class="card" id="accounts-payable-days-ap-days" data-section="area2" data-search="Accounts Payable Days (AP Days) Free funding from your suppliers — the only interest-free loan in business. Your supplier gives you 60 days to pay after delivering goods. During those 60 days, you have received value (stock, services) without paying for it. That gap is free funding. AP Days measures how well the business uses this free credit. Every extra day within agreed terms that payment is delayed retains COGS ÷ 365 of cash. Paying suppliers faster than required is gifting them an interest-free loan at the business&#x27;s expense.">
<div class="card-head"><h3>Accounts Payable Days (AP Days)</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Free funding from your suppliers — the only interest-free loan in business.&#8221;</p>
<p class="story">Your supplier gives you 60 days to pay after delivering goods. During those 60 days, you have received value (stock, services) without paying for it. That gap is free funding. AP Days measures how well the business uses this free credit. Every extra day within agreed terms that payment is delayed retains COGS ÷ 365 of cash. Paying suppliers faster than required is gifting them an interest-free loan at the business&#x27;s expense.</p>
<div class="fbox"><strong class="fl">Formula (Annual):</strong> <code class="fc">AP Days = Accounts Payable ÷ COGS × 365</code></div>
<div class="fbox"><strong class="fl">Formula (Monthly):</strong> <code class="fc">AP Days = (Previous Month Accounts Payable + Current Month Accounts Payable)/2 ÷ Monthly COGS × 30</code></div>
</div>
</div></div>
<div class="card" id="working-capital-days" data-section="area2" data-search="Working Capital Days Total days your money is imprisoned — from supplier payment to customer collection. On Day 1, stock arrives. On Day 46 you pay the supplier. On Day 121 the stock is sold. On Day 201 the customer pays. Working Capital Days measures the total length of this journey — 154 days for ABC Pvt Ltd. That is 5 months of money in prison. The shorter this number, the faster cash cycles back to the business. A negative Working Capital Days (AP Days &gt; AR Days + Inventory Days) means the business is funded entirely by supplier credit — the ideal state.">
<div class="card-head"><h3>Working Capital Days</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Total days your money is imprisoned — from supplier payment to customer collection.&#8221;</p>
<p class="story">On Day 1, stock arrives. On Day 46 you pay the supplier. On Day 121 the stock is sold. On Day 201 the customer pays. Working Capital Days measures the total length of this journey — 154 days for ABC Pvt Ltd. That is 5 months of money in prison. The shorter this number, the faster cash cycles back to the business. A negative Working Capital Days (AP Days &gt; AR Days + Inventory Days) means the business is funded entirely by supplier credit — the ideal state.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Working Capital Days = AR Days + Inventory Days − AP Days</code></div>
</div>
</div></div>
<div class="card" id="working-capital-per-100" data-section="area2" data-search="Working Capital per ₹100 For every ₹100 earned, this much cash is immediately kidnapped by the business cycle. You close a new sale for ₹1 Lakh. Before any of that money reaches your bank, the business cycle needs ₹36.34 of working capital to support it — stock to sell, debtors to create. This is the working capital cost of growth. Compare it directly with Gross Margin %: if Working Capital per ₹100 is higher than Gross Margin %, Marginal Cash Flow is negative — meaning every new sale consumes more cash than it generates.">
<div class="card-head"><h3>Working Capital per ₹100</h3></div>
<div class="card-body">
<p class="tagline">&#8220;For every ₹100 earned, this much cash is immediately kidnapped by the business cycle.&#8221;</p>
<p class="story">You close a new sale for ₹1 Lakh. Before any of that money reaches your bank, the business cycle needs ₹36.34 of working capital to support it — stock to sell, debtors to create. This is the working capital cost of growth. Compare it directly with Gross Margin %: if Working Capital per ₹100 is higher than Gross Margin %, Marginal Cash Flow is negative — meaning every new sale consumes more cash than it generates.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Working Capital per ₹100 = Working Capital ÷ Revenue × 100</code></div>
</div>
</div></div>
<div class="card" id="marginal-cash-flow" data-section="area2" data-search="Marginal Cash Flow The moment of truth — does your next sale help you or hurt you? This is the most important and most misunderstood metric in Cash ProfitNiti AI. Every sale generates Gross Margin % of the sale as profit. But it also requires Working Capital per ₹100 to support it. If WC per ₹100 is higher than Gross Margin %, the business is growing broke — each new sale generates accounting profit but consumes more cash than it creates. For ABC Pvt Ltd, every ₹100 of new revenue earns ₹29 in gross margin but needs ₹36.34 of working capital → net loss of ₹7.34 in cash.">
<div class="card-head"><h3>Marginal Cash Flow</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The moment of truth — does your next sale help you or hurt you?&#8221;</p>
<p class="story">This is the most important and most misunderstood metric in Cash ProfitNiti AI. Every sale generates Gross Margin % of the sale as profit. But it also requires Working Capital per ₹100 to support it. If WC per ₹100 is higher than Gross Margin %, the business is growing broke — each new sale generates accounting profit but consumes more cash than it creates. For ABC Pvt Ltd, every ₹100 of new revenue earns ₹29 in gross margin but needs ₹36.34 of working capital → net loss of ₹7.34 in cash.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Marginal Cash Flow = Gross Margin % − Working Capital per ₹100</code></div>
</div>
</div></div>
<div class="card" id="working-capital-turnover" data-section="area2" data-search="Working Capital Turnover How many times revenue cycles through the working capital engine. Working Capital Turnover measures how efficiently working capital generates revenue. A turnover of 2.75x means every ₹1 of working capital generates ₹2.75 of revenue. Higher is better — it means the business is squeezing more output from the same capital base. A declining WC Turnover means the working capital base is growing faster than revenue — a leading indicator of cash pressure.">
<div class="card-head"><h3>Working Capital Turnover</h3></div>
<div class="card-body">
<p class="tagline">&#8220;How many times revenue cycles through the working capital engine.&#8221;</p>
<p class="story">Working Capital Turnover measures how efficiently working capital generates revenue. A turnover of 2.75x means every ₹1 of working capital generates ₹2.75 of revenue. Higher is better — it means the business is squeezing more output from the same capital base. A declining WC Turnover means the working capital base is growing faster than revenue — a leading indicator of cash pressure.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Working Capital Turnover = Revenue ÷ Working Capital</code></div>
</div>
</div></div>
<div class="card" id="current-ratio" data-section="area2" data-search="Current Ratio Can the business cover all short-term obligations from its current assets? Imagine receiving demands from three creditors at once. Current Ratio asks: if you had to pay every short-term obligation right now using everything you own as current assets, could you? Below 1.0x means the business cannot cover short-term liabilities even by liquidating all current assets.">
<div class="card-head"><h3>Current Ratio</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Can the business cover all short-term obligations from its current assets?&#8221;</p>
<p class="story">Imagine receiving demands from three creditors at once. Current Ratio asks: if you had to pay every short-term obligation right now using everything you own as current assets, could you? Below 1.0x means the business cannot cover short-term liabilities even by liquidating all current assets.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Current Ratio = Current Assets ÷ Current Liabilities</code></div>
</div>
</div></div>
<div class="card" id="quick-ratio" data-section="area2" data-search="Quick Ratio Can the business pay its short-term bills without selling any stock? Quick Ratio removes inventory from current assets — because inventory takes months to convert to cash. It asks the harder question: using only assets that convert quickly (debtors, cash, liquid investments), can the business cover short-term obligations? Below 0.5x is a serious liquidity warning.">
<div class="card-head"><h3>Quick Ratio</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Can the business pay its short-term bills without selling any stock?&#8221;</p>
<p class="story">Quick Ratio removes inventory from current assets — because inventory takes months to convert to cash. It asks the harder question: using only assets that convert quickly (debtors, cash, liquid investments), can the business cover short-term obligations? Below 0.5x is a serious liquidity warning.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Quick Ratio = (Current Assets − Inventory) ÷ Current Liabilities</code></div>
<div class="fbox"><strong class="fl">Also called:</strong> <code class="fc">Acid Test Ratio</code></div>
</div>
</div></div>
<div class="card" id="cash-ratio" data-section="area2" data-search="Cash Ratio The most conservative liquidity test — can the business pay today&#x27;s bills with cash alone? Current Ratio and Quick Ratio ask whether assets can be converted to cover liabilities. Cash Ratio asks the hardest question: right now, with only what is in the bank, can the business pay its short-term obligations? No stock to sell, no debtors to chase — just the cash balance. A Cash Ratio below 0.02 signals a business one missed payment away from a crisis.">
<div class="card-head"><h3>Cash Ratio</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The most conservative liquidity test — can the business pay today&#x27;s bills with cash alone?&#8221;</p>
<p class="story">Current Ratio and Quick Ratio ask whether assets can be converted to cover liabilities. Cash Ratio asks the hardest question: right now, with only what is in the bank, can the business pay its short-term obligations? No stock to sell, no debtors to chase — just the cash balance. A Cash Ratio below 0.02 signals a business one missed payment away from a crisis.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Cash Ratio = Cash and Bank Balances ÷ Current Liabilities</code></div>
</div>
</div></div>
</div></div>
<div id="sec-area3" class="sec" data-section="area3" style="background:#FBF3E8"><div class="area-hdr" style="border-bottom:1px solid #854F0B30"><div class="area-num" style="background:#854F0B">3</div><div><div class="area-name" style="color:#854F0B">Capex — Other Capital</div><div class="area-sub">Fixed Assets · Returns on Capital · Capital Efficiency · Capex Coverage</div></div></div><div class="cards-wrap"><p class="section-body">Financial Area 3 measures how efficiently the business deploys its physical infrastructure. Every asset must earn its place — if Return on Capital % exceeds the cost of borrowing, the business is creating value. If it does not, the business is destroying value despite making accounting profit.</p>
<div class="card" id="other-capital-and-other-capital" data-section="area3" data-search="Other Capital and Other Capital % The permanent price of admission to your industry. To run a restaurant, you need a kitchen. To run a factory, you need machines. To run a logistics company, you need vehicles. Other Capital is the capital permanently committed to infrastructure — the long-term physical assets net of any long-term liabilities they carry. Other Capital % shows this as a proportion of revenue: how much permanent infrastructure is required per ₹100 earned.">
<div class="card-head"><h3>Other Capital and Other Capital %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The permanent price of admission to your industry.&#8221;</p>
<p class="story">To run a restaurant, you need a kitchen. To run a factory, you need machines. To run a logistics company, you need vehicles. Other Capital is the capital permanently committed to infrastructure — the long-term physical assets net of any long-term liabilities they carry. Other Capital % shows this as a proportion of revenue: how much permanent infrastructure is required per ₹100 earned.</p>
<div class="fbox"><strong class="fl">Other Capital:</strong> <code class="fc">Fixed Assets + Other Non-Current Assets − Other Non-Current Liabilities</code></div>
<div class="fbox"><strong class="fl">Other Capital %:</strong> <code class="fc">Other Capital ÷ Revenue × 100</code></div>
<div class="fbox"><strong class="fl">Other Capital Turnover:</strong> <code class="fc">Revenue ÷ Other Capital</code></div>
</div>
</div></div>
<div class="card" id="net-operating-assets-and-net-operating-assets" data-section="area3" data-search="Net Operating Assets and Net Operating Assets % The total capital permanently committed just to keep the doors open. Net Operating Assets is the sum of everything tied up in running the business — working capital (cash cycle) and other capital (infrastructure). It must always equal Equity + Net Debt — the two sources of funding. Net Operating Assets % shows how capital-intensive the business is relative to its revenue.">
<div class="card-head"><h3>Net Operating Assets and Net Operating Assets %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The total capital permanently committed just to keep the doors open.&#8221;</p>
<p class="story">Net Operating Assets is the sum of everything tied up in running the business — working capital (cash cycle) and other capital (infrastructure). It must always equal Equity + Net Debt — the two sources of funding. Net Operating Assets % shows how capital-intensive the business is relative to its revenue.</p>
<div class="fbox"><strong class="fl">Net Operating Assets:</strong> <code class="fc">= Working Capital + Other Capital</code></div>
<div class="fbox"><strong class="fl">NOA %:</strong> <code class="fc">= Net Operating Assets ÷ Revenue × 100</code></div>
</div>
</div></div>
<div class="card" id="asset-turnover" data-section="area3" data-search="Asset Turnover How hard is your capital working — sweating or sleeping? Asset Turnover measures how many rupees of revenue each rupee of capital generates. A business with Asset Turnover of 1.59x generates ₹1.59 of revenue for every ₹1 of capital employed. Higher is better — it means the capital base is being worked harder. Asset Turnover × Operating Profit % = Return on Capital %. So improving either the margin or the efficiency of capital use (or both) improves the master return metric.">
<div class="card-head"><h3>Asset Turnover</h3></div>
<div class="card-body">
<p class="tagline">&#8220;How hard is your capital working — sweating or sleeping?&#8221;</p>
<p class="story">Asset Turnover measures how many rupees of revenue each rupee of capital generates. A business with Asset Turnover of 1.59x generates ₹1.59 of revenue for every ₹1 of capital employed. Higher is better — it means the capital base is being worked harder. Asset Turnover × Operating Profit % = Return on Capital %. So improving either the margin or the efficiency of capital use (or both) improves the master return metric.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Asset Turnover = Revenue ÷ Net Operating Assets</code></div>
</div>
</div></div>
<div class="card" id="return-on-capital" data-section="area3" data-search="Return on Capital % The master scorecard — what the business earns on every rupee tied up in it. You could put money in a bank FD at 7%, a mutual fund at 12%, or your own business. If your Return on Capital is 16.89%, the business wins. If it is 5%, you would earn more by closing the business and investing elsewhere. Return on Capital is the CEO&#x27;s and investor&#x27;s primary metric — it cuts through all other noise to answer: is this business a good use of money?">
<div class="card-head"><h3>Return on Capital %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The master scorecard — what the business earns on every rupee tied up in it.&#8221;</p>
<p class="story">You could put money in a bank FD at 7%, a mutual fund at 12%, or your own business. If your Return on Capital is 16.89%, the business wins. If it is 5%, you would earn more by closing the business and investing elsewhere. Return on Capital is the CEO&#x27;s and investor&#x27;s primary metric — it cuts through all other noise to answer: is this business a good use of money?</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Return on Capital % = Operating Profit ÷ Net Operating Assets × 100</code></div>
<div class="fbox"><strong class="fl">Equivalent:</strong> <code class="fc">= Operating Profit % × Asset Turnover</code></div>
</div>
</div></div>
<div class="card" id="return-on-total-assets" data-section="area3" data-search="Return on Total Assets % How much operating profit is earned from every rupee of total assets. Return on Total Assets uses the full balance sheet — including cash, investments, and all assets — as the denominator, not just Net Operating Assets. It is a broader efficiency measure that shows how well management is deploying all resources, not just the operating capital. Banks and analysts use ROTA to compare asset efficiency across companies.">
<div class="card-head"><h3>Return on Total Assets %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;How much operating profit is earned from every rupee of total assets.&#8221;</p>
<p class="story">Return on Total Assets uses the full balance sheet — including cash, investments, and all assets — as the denominator, not just Net Operating Assets. It is a broader efficiency measure that shows how well management is deploying all resources, not just the operating capital. Banks and analysts use ROTA to compare asset efficiency across companies.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Return on Total Assets % = Operating Profit ÷ Total Assets × 100</code></div>
<div class="fbox"><strong class="fl">Monthly:</strong> <code class="fc">(Monthly Operating Profit × 12) ÷ Total Assets × 100</code></div>
</div>
</div></div>
<div class="card" id="return-on-equity" data-section="area3" data-search="Return on Equity % What the owner earns on the money they invested — the personal scorecard. You invested ₹13.10L of your own money. The business made ₹4.10L net profit for you. Your return is 31.30%. But be careful: high ROE driven by high leverage is partly illusory — it reflects how much the bank owns (and therefore amplifies your return on the small equity base), not just operational excellence. Always read ROE alongside Debt to Equity.">
<div class="card-head"><h3>Return on Equity %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What the owner earns on the money they invested — the personal scorecard.&#8221;</p>
<p class="story">You invested ₹13.10L of your own money. The business made ₹4.10L net profit for you. Your return is 31.30%. But be careful: high ROE driven by high leverage is partly illusory — it reflects how much the bank owns (and therefore amplifies your return on the small equity base), not just operational excellence. Always read ROE alongside Debt to Equity.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Return on Equity % = Net Profit After Tax ÷ Total Equity × 100</code></div>
</div>
</div></div>
</div></div>
<div id="sec-pvcf" class="sec" data-section="pvcf"><div class="sec-hdr"><div class="sec-bar" style="background:#1D9E75"></div><div class="sec-eye" style="color:#1D9E75">Reconciliation</div><h2 class="sec-title">Profit vs Cash Flow — The Reconciliation Table</h2><p class="sec-sub">Cash ProfitNiti AI shows this table in every report. Every variance is auto-generated from five working capital inputs. Golden Rule: the variance between EBITDA and Operating Cash Flow will ALWAYS equal the change in Working Capital.</p></div><div class="cards-wrap"><p class="section-body">Cash ProfitNiti AI shows this table in every report. Every variance is auto-generated from the five working capital inputs: ΔAR, ΔInventory, ΔAP, Capex vs Depreciation, and timing differences for interest and tax. Golden Rule: the variance between EBITDA and Operating Cash Flow will ALWAYS equal the change in Working Capital.</p>

<div class="ex-box">
  <div class="ex-title">Numerical Example — ABC Private Limited</div>
  <p class="ex-intro">The reconciliation table below uses actual figures from ABC Pvt Ltd. Here is where every number comes from:</p>

  <div class="ex-grid">
    <div class="ex-col">
      <div class="ex-col-head">P&amp;L for the period</div>
      <table class="ex-tbl">
        <tr><td>Revenue (Sales)</td><td class="ex-num">₹66,12,000</td></tr>
        <tr><td>Cost of Goods Sold (COGS)</td><td class="ex-num">₹46,94,500</td></tr>
        <tr class="ex-sub"><td>Gross Margin</td><td class="ex-num">₹19,17,500</td></tr>
        <tr><td>Cash Overheads (excl. Depreciation)</td><td class="ex-num">₹11,16,200</td></tr>
        <tr class="ex-sub"><td>EBITDA / Operating Cash Profit</td><td class="ex-num">₹8,01,300</td></tr>
      </table>
    </div>
    <div class="ex-col">
      <div class="ex-col-head">Balance Sheet movements (Opening → Closing)</div>
      <table class="ex-tbl">
        <tr><td>Accounts Receivable (AR)</td><td class="ex-num">₹12,00,000 → ₹14,43,000</td><td class="ex-delta">+₹2,43,000</td></tr>
        <tr><td>Inventory (Closing Stock)</td><td class="ex-num">₹12,50,000 → ₹15,50,000</td><td class="ex-delta">+₹3,00,000</td></tr>
        <tr><td>Accounts Payable (AP)</td><td class="ex-num">₹5,00,000 → ₹5,90,000</td><td class="ex-delta ex-pos">+₹90,000</td></tr>
        <tr class="ex-sub"><td colspan="2">Net Working Capital increase (AR↑ + Inv↑ − AP↑)</td><td class="ex-delta">₹4,53,000</td></tr>
      </table>
    </div>
  </div>

  <div class="ex-derive">
    <div class="ex-derive-title">How each Cash Flow column figure is derived</div>
    <div class="ex-derive-grid">
      <div class="ex-derive-item">
        <span class="ex-derive-label">Cash from Customers</span>
        <span class="ex-derive-calc">Revenue ₹66,12,000 − AR increase ₹2,43,000</span>
        <span class="ex-derive-result">= ₹63,69,000</span>
      </div>
      <div class="ex-derive-item">
        <span class="ex-derive-label">Cash to Suppliers</span>
        <span class="ex-derive-calc">COGS ₹46,94,500 + Inventory increase ₹3,00,000 − AP increase ₹90,000</span>
        <span class="ex-derive-result">= ₹49,04,500</span>
      </div>
      <div class="ex-derive-item">
        <span class="ex-derive-label">Gross Cash Profit</span>
        <span class="ex-derive-calc">Cash from Customers ₹63,69,000 − Cash to Suppliers ₹49,04,500</span>
        <span class="ex-derive-result">= ₹14,64,500</span>
      </div>
      <div class="ex-derive-item">
        <span class="ex-derive-label">Operating Cash Flow</span>
        <span class="ex-derive-calc">Gross Cash Profit ₹14,64,500 − Cash Overheads ₹11,16,200</span>
        <span class="ex-derive-result">= ₹3,48,300</span>
      </div>
      <div class="ex-derive-item ex-derive-key">
        <span class="ex-derive-label">EBITDA vs OCF gap</span>
        <span class="ex-derive-calc">EBITDA ₹8,01,300 − OCF ₹3,48,300 = Working Capital increase ₹4,53,000 ✓</span>
        <span class="ex-derive-result">Golden Rule confirmed</span>
      </div>
    </div>
  </div>
</div>

<style>
.ex-box{background:#F4FBF8;border:1px solid #5DCAA5;border-radius:12px;padding:20px 22px;margin-bottom:20px}
.ex-title{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#0F6E56;margin-bottom:8px}
.ex-intro{font-size:13px;color:#3D3D3A;margin-bottom:16px;line-height:1.6}
.ex-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
@media(max-width:700px){.ex-grid{grid-template-columns:1fr}}
.ex-col-head{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0F6E56;margin-bottom:8px}
.ex-tbl{width:100%;border-collapse:collapse;font-size:12.5px}
.ex-tbl td{padding:5px 8px;border-bottom:1px solid #C6EAD9;color:#1A1A18;vertical-align:middle}
.ex-tbl tr:last-child td{border-bottom:none}
.ex-tbl .ex-sub td{font-weight:600;background:#D4EDDF;color:#0F6E56}
.ex-num{text-align:right;font-family:"DM Mono",monospace;font-size:12px;white-space:nowrap}
.ex-delta{text-align:right;font-family:"DM Mono",monospace;font-size:12px;color:#993C1D;font-weight:600;white-space:nowrap}
.ex-pos{color:#0F6E56!important}
.ex-derive{background:#fff;border:1px solid #C6EAD9;border-radius:9px;padding:14px 16px}
.ex-derive-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#0F6E56;margin-bottom:10px}
.ex-derive-grid{display:grid;gap:8px}
.ex-derive-item{display:grid;grid-template-columns:180px 1fr auto;gap:8px;align-items:center;padding:6px 10px;background:#F0FBF7;border-radius:6px;font-size:12.5px}
.ex-derive-item.ex-derive-key{background:#D4EDDF;grid-template-columns:180px 1fr auto}
.ex-derive-label{font-weight:600;color:#0F6E56}
.ex-derive-calc{color:#3D3D3A}
.ex-derive-result{font-family:"DM Mono",monospace;font-weight:700;color:#0F6E56;text-align:right;white-space:nowrap}
</style>
<div class="pvcf-wrap"><table class="pvcf"><thead><tr><th>Profit Line Item</th><th>Profit ₹</th><th>Cash Flow Line Item</th><th>Cash Flow ₹</th><th>Variance ₹</th><th>Why They Differ</th></tr></thead><tbody><tr><td>Revenue</td><td>₹66,12,000</td><td>Cash from Customers  (Revenue − ΔAR)</td><td>₹63,69,000</td><td><span class="var">−₹2,43,000</span></td><td>AR grew ₹2,43,000 — customers invoiced, not yet paid</td></tr><tr><td>COGS</td><td>₹46,94,500</td><td>Cash to Suppliers  (COGS + ΔInv − ΔAP)</td><td>₹49,04,500</td><td><span class="var">−₹2,10,000</span></td><td>Stock grew ₹3L; AP offset only ₹0.90L</td></tr><tr><td>Gross Margin</td><td>₹19,17,500</td><td>Gross Cash Profit</td><td>₹14,64,500</td><td><span class="var">−₹4,53,000</span></td><td>Total WC absorbed — sum of above two lines</td></tr><tr><td>Overheads excl. Depn</td><td>₹11,16,200</td><td>Cash Overheads excl. Depn</td><td>₹11,16,200</td><td><span class="nil">NIL</span></td><td>Always identical — depreciation excluded both sides</td></tr><tr class="tot"><td>EBITDA / Op. Cash Profit</td><td>₹8,01,300</td><td>Operating Cash Flow</td><td>₹3,48,300</td><td>−₹4,53,000</td><td>Gap = WC increase. Cash Flow Quality = 43.47%</td></tr></tbody></table></div></div></div>
<div id="sec-area4" class="sec" data-section="area4" style="background:#F2F2F0"><div class="area-hdr" style="border-bottom:1px solid #3B3B3A30"><div class="area-num" style="background:#3B3B3A">4</div><div><div class="area-name" style="color:#3B3B3A">Financing</div><div class="area-sub">Debt · Equity · Cash Flow Quality · Business Sustainability</div></div></div><div class="cards-wrap"><p class="section-body">The funding equation: Net Operating Assets = Equity + Net Debt. Every rupee trapped in the working capital cycle (Area 2) and every rupee of infrastructure (Area 3) must be funded by either the owner&#x27;s equity or borrowed money. Financial Area 4 measures the health of this funding structure.</p>
<div class="card" id="net-debt-total-debt-and-debt-to-equity" data-section="area4" data-search="Net Debt, Total Debt and Debt to Equity How much of the business belongs to the owner vs the bank. ">
<div class="card-head"><h3>Net Debt, Total Debt and Debt to Equity</h3></div>
<div class="card-body">
<p class="tagline">&#8220;How much of the business belongs to the owner vs the bank.&#8221;</p>
<div class="fbox"><strong class="fl">Net Debt:</strong> <code class="fc">= Total Debt − Cash and Bank Balances</code></div>
<div class="fbox"><strong class="fl">Total Debt:</strong> <code class="fc">= Bank Loans Current + Bank Loans Non-Current + Other Borrowings</code></div>
<div class="fbox"><strong class="fl">Debt to Equity:</strong> <code class="fc">= Net Debt ÷ Total Equity</code></div>
<div class="fbox"><strong class="fl">Debt to Capital %:</strong> <code class="fc">= Net Debt ÷ (Net Debt + Equity) × 100</code></div>
</div>
</div></div>
<div class="card" id="total-funding-the-balancing-identity" data-section="area4" data-search="Total Funding — The Balancing Identity The complete capital equation that must always balance — Cash ProfitNiti AI&#x27;s built-in accuracy check. Total Funding = Equity + Net Debt. It equals Net Operating Assets (Working Capital + Other Capital). This is not optional — it is the fundamental accounting identity. If these two sides do not balance, there is a data entry error somewhere in the inputs. Cash ProfitNiti AI checks this automatically before generating any analysis.">
<div class="card-head"><h3>Total Funding — The Balancing Identity</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The complete capital equation that must always balance — Cash ProfitNiti AI&#x27;s built-in accuracy check.&#8221;</p>
<p class="story">Total Funding = Equity + Net Debt. It equals Net Operating Assets (Working Capital + Other Capital). This is not optional — it is the fundamental accounting identity. If these two sides do not balance, there is a data entry error somewhere in the inputs. Cash ProfitNiti AI checks this automatically before generating any analysis.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Total Funding = Equity + Net Debt</code></div>
<div class="fbox"><strong class="fl">Always equals:</strong> <code class="fc">= Net Operating Assets = Working Capital + Other Capital</code></div>
</div>
</div></div>
<div class="card" id="debt-payback" data-section="area4" data-search="Debt Payback If the business worked only to repay the bank, how long would it take? ">
<div class="card-head"><h3>Debt Payback</h3></div>
<div class="card-body">
<p class="tagline">&#8220;If the business worked only to repay the bank, how long would it take?&#8221;</p>
<div class="fbox"><strong class="fl">Annual:</strong> <code class="fc">Debt Payback = Net Debt ÷ Annual EBITDA  (in years)</code></div>
<div class="fbox"><strong class="fl">Monthly:</strong> <code class="fc">Net Debt ÷ (Monthly EBITDA × 12)  [annualises monthly EBITDA first]</code></div>
</div>
</div></div>
<div class="card" id="business-generated-cash" data-section="area4" data-search="Business Generated Cash Did the business fill its own tank — or did it need the bank to top it up? At the end of every period, one question cuts through all the noise: did the business generate cash or consume it? Business Generated Cash (= Net Cash Flow) combines Retained Profit, Working Capital movement, and Other Capital movement into a single verdict. Positive means the business funded itself. Negative means it borrowed to survive — even if the P&amp;L shows profit.">
<div class="card-head"><h3>Business Generated Cash</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Did the business fill its own tank — or did it need the bank to top it up?&#8221;</p>
<p class="story">At the end of every period, one question cuts through all the noise: did the business generate cash or consume it? Business Generated Cash (= Net Cash Flow) combines Retained Profit, Working Capital movement, and Other Capital movement into a single verdict. Positive means the business funded itself. Negative means it borrowed to survive — even if the P&amp;L shows profit.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Business Generated Cash = Retained Profit ± WC Change ± Other Capital Change</code></div>
<div class="fbox"><strong class="fl">Table below:</strong> <code class="fc">Shows whether each component added or consumed cash</code></div>
</div>
</div></div>
<div class="card" id="the-business-generated-cash-table" data-section="area4" data-search="The Business Generated Cash Table  ">
<div class="card-head"><h3>The Business Generated Cash Table</h3></div>
<div class="card-body">

<div class="bgc-wrap"><table class="bgc"><thead><tr><th>Line Item</th><th>Cash Flow (+)  [A]</th><th>Cash Flow (−)  [B]</th></tr></thead><tbody><tr class="p1"><td>Profit / (Loss)(Net Profit or Net Loss After Tax)</td><td>If NET PROFIT <br>→ place in (+) columnEarnings add cash to the business</td><td>If NET LOSS <br>→ place in (−) columnLoss consumes cash from the business</td></tr><tr class="wc"><td>Working Capital Change(ΔAR + ΔInventory − ΔAP)</td><td>If WC DECREASED <br>→ place in (+)Cycle compressed <br>→ cash released</td><td>If WC INCREASED <br>→ place in (−)More cash trapped in the cycle</td></tr><tr class="oc"><td>Other Capital Change(ΔFixed Assets ± Other Assets/Liabilities)</td><td>If OC DECREASED <br>→ place in (+)Assets reduced / depreciation <br>→ cash released</td><td>If OC INCREASED <br>→ place in (−)New assets purchased <br>→ cash used</td></tr><tr class="cap"><td>Capital Withdrawn(Distributions / Dividends paid to owner)</td><td>—</td><td>Always a use — always in (−) columnOwner draws cash out of the business</td></tr><tr class="tot"><td>BUSINESS GENERATED CASH  =  A − B</td><td>Total of (+) column  [A]</td><td>Total of (−) column  [B]</td></tr></tbody></table></div>
</div></div>
<div class="card" id="operating-cash-flow" data-section="area4" data-search="Operating Cash Flow The actual cash your trading operations put in the bank — after the working capital cycle takes its share. ">
<div class="card-head"><h3>Operating Cash Flow</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The actual cash your trading operations put in the bank — after the working capital cycle takes its share.&#8221;</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Operating Cash Flow = Gross Cash Profit − Cash Overheads (excl. Depreciation)</code></div>
<div class="fbox"><strong class="fl">Gross Cash Profit:</strong> <code class="fc">= Cash from Customers − Cash to Suppliers</code></div>
</div>
</div></div>
<div class="card" id="net-cash-flow" data-section="area4" data-search="Net Cash Flow The final score — did the business end the period with more or less cash? ">
<div class="card-head"><h3>Net Cash Flow</h3></div>
<div class="card-body">
<p class="tagline">&#8220;The final score — did the business end the period with more or less cash?&#8221;</p>
<div class="fbox"><strong class="fl">From Cash Flow Statement:</strong> <code class="fc">Net Cash Flow = Operating CF − Interest − Tax − Distributions − Capex + Capital Introduced</code></div>
<div class="fbox"><strong class="fl">From Balance Sheet:</strong> <code class="fc">Net Cash Flow = Retained Profit − Increase in Working Capital − Increase in Other Capital</code></div>
</div>
</div></div>
<div class="card" id="operating-cash-flow-margin" data-section="area4" data-search="Operating Cash Flow Margin % For every ₹100 earned, how much actually becomes real operating cash? Net Profit Margin is what the accountant says you kept. OCF Margin is what the bank account shows. A healthy business generates 4–8% of revenue as operating cash. Below 2%, there is barely enough to service debt. Negative means consuming cash with every sale.">
<div class="card-head"><h3>Operating Cash Flow Margin %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;For every ₹100 earned, how much actually becomes real operating cash?&#8221;</p>
<p class="story">Net Profit Margin is what the accountant says you kept. OCF Margin is what the bank account shows. A healthy business generates 4–8% of revenue as operating cash. Below 2%, there is barely enough to service debt. Negative means consuming cash with every sale.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Operating Cash Flow Margin % = Operating Cash Flow ÷ Revenue × 100</code></div>
</div>
</div></div>
<div class="card" id="cash-flow-coverage" data-section="area4" data-search="Cash Flow Coverage Can the business pay its bank interest from real cash — not just accounting profit? Interest Cover (EBIT-based) uses accounting profit. Cash Flow Coverage uses real cash. A business can show Interest Cover of 4x on the P&amp;L while its cash barely covers interest — this happens when the working capital cycle absorbs cash faster than the business earns it. When Cash Flow Coverage falls below 1.0x, the business is using reserves or new borrowing just to pay existing interest charges.">
<div class="card-head"><h3>Cash Flow Coverage</h3></div>
<div class="card-body">
<p class="tagline">&#8220;Can the business pay its bank interest from real cash — not just accounting profit?&#8221;</p>
<p class="story">Interest Cover (EBIT-based) uses accounting profit. Cash Flow Coverage uses real cash. A business can show Interest Cover of 4x on the P&amp;L while its cash barely covers interest — this happens when the working capital cycle absorbs cash faster than the business earns it. When Cash Flow Coverage falls below 1.0x, the business is using reserves or new borrowing just to pay existing interest charges.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Cash Flow Coverage = Operating Cash Flow ÷ Interest Paid</code></div>
<div class="fbox"><strong class="fl">Compare:</strong> <code class="fc">Interest Cover = Operating Profit ÷ Interest Paid  [accounting version]</code></div>
</div>
</div></div>
<div class="card" id="cash-flow-to-debt" data-section="area4" data-search="Cash Flow to Debt % What percentage of total borrowings could be repaid from one year&#x27;s operating cash? Debt Payback uses accounting EBITDA. Cash Flow to Debt uses actual operating cash generated. At 20%, all debt is repayable in 5 years from operations alone. At 10%, it takes 10 years. At negative, debt grows every year. Know this number before your banker calculates it.">
<div class="card-head"><h3>Cash Flow to Debt %</h3></div>
<div class="card-body">
<p class="tagline">&#8220;What percentage of total borrowings could be repaid from one year&#x27;s operating cash?&#8221;</p>
<p class="story">Debt Payback uses accounting EBITDA. Cash Flow to Debt uses actual operating cash generated. At 20%, all debt is repayable in 5 years from operations alone. At 10%, it takes 10 years. At negative, debt grows every year. Know this number before your banker calculates it.</p>
<div class="fbox"><strong class="fl">Formula:</strong> <code class="fc">Cash Flow to Debt % = Operating Cash Flow ÷ Total Debt × 100</code></div>
</div>
</div></div>
</div></div>

</main>
</div>
<script>
var curFilt='all';
function doSearch(q){
  q=q.trim().toLowerCase();
  var cards=document.querySelectorAll('.card');
  var n=0;
  if(!q){cards.forEach(c=>{c.classList.remove('hidden','hi');clearMarks(c);});document.getElementById('sc').textContent='';document.getElementById('nr').style.display='none';applyFilt(curFilt);return;}
  document.querySelectorAll('.pill').forEach(p=>p.classList.remove('on'));
  document.querySelectorAll('.sec').forEach(s=>s.style.display='');
  cards.forEach(c=>{
    var d=(c.dataset.search||'')+' '+c.innerText;
    clearMarks(c);
    if(d.toLowerCase().includes(q)){c.classList.remove('hidden');c.classList.add('hi');markText(c,q);n++;}
    else{c.classList.add('hidden');c.classList.remove('hi');}
  });
  document.getElementById('sc').textContent=n+' found';
  document.getElementById('nr').style.display=n===0?'block':'none';
}
function clearMarks(el){el.querySelectorAll('mark').forEach(m=>{var p=m.parentNode;p.replaceChild(document.createTextNode(m.textContent),m);p.normalize();});}
function markText(el,q){
  var targets=el.querySelectorAll('.card-head h3,.tagline,.story,.fcode,.tltxt');
  var esc=q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&');
  targets.forEach(t=>{t.innerHTML=t.innerHTML.replace(new RegExp('('+esc+')','gi'),'<mark>$1</mark>');});
}
function filt(sec,btn){
  curFilt=sec;
  document.getElementById('sb').value='';document.getElementById('sc').textContent='';document.getElementById('nr').style.display='none';
  document.querySelectorAll('.card').forEach(c=>{c.classList.remove('hidden','hi');clearMarks(c);});
  document.querySelectorAll('.pill').forEach(p=>p.classList.remove('on'));
  if(btn)btn.classList.add('on');
  applyFilt(sec);
}
function applyFilt(sec){
  var secs=document.querySelectorAll('.sec');
  if(sec==='all'){secs.forEach(s=>s.style.display='');return;}
  secs.forEach(s=>{
    var sd=s.dataset.section;
    var show=(sd===sec)||(sec==='area4'&&sd==='pvcf');
    s.style.display=show?'':'none';
  });
}
function toggleNav(el){
  el.classList.toggle('closed');
  var c=el.nextElementSibling;
  if(c)c.style.maxHeight=el.classList.contains('closed')?'0':'4000px';
}
document.querySelectorAll('.ng-items').forEach(el=>el.style.maxHeight='4000px');
var obs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{if(e.isIntersecting){
    var id=e.target.id;
    document.querySelectorAll('.ni').forEach(a=>a.classList.toggle('on',a.getAttribute('href')==='#'+id));
  }});
},{rootMargin:'-5% 0px -85% 0px'});
document.querySelectorAll('.card[id]').forEach(el=>obs.observe(el));
document.addEventListener('keydown',e=>{
  if((e.metaKey||e.ctrlKey)&&e.key==='f'){e.preventDefault();document.getElementById('sb').focus();}
  if(e.key==='Escape'){document.getElementById('sb').value='';doSearch('');}
});
</script>
</body>
</html>