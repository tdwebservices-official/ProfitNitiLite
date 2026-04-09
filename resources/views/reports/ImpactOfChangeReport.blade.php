@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/pages/reports/comman-reports.css')}}">
  <script src="https://cdn.tailwindcss.com"></script>
    
<style>
    .icon {
            width: 1em;
            height: 1em;
            display: inline-block;
            vertical-align: middle;
        }
        .icon-lg {
            width: 2rem;
            height: 2rem;
        }
        .icon-md {
            width: 1.25rem;
            height: 1.25rem;
        }
        .icon-sm {
            width: 1rem;
            height: 1rem;
        }
        .icon-xs {
            width: 0.75rem;
            height: 0.75rem;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        main.dashboard-main * {
    font-family: Inter, sans-serif !important;
}
</style>
@endsection

@section('content')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Impact Of Change Report</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="/" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Dashboard
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Impact Of Change Report</li>
    </ul>
  </div>

  <div class="card h-100 p-0 radius-12">
    <div class="card-body p-0">
      <div class="row justify-content-center">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
          <div class="card border">
            <div class="card-body">
              @if (count($errors) > 0)
              <div class="alert alert-danger">                        
                <ul>
                  <li><strong>Whoops!</strong> There were some problems with your input.</li>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif                                
              <form method="post" enctype="multipart/form-data" class="impact-of-change-report-form" id="impact-of-change-report-form">
                @csrf               
                @include('reports.comman-filer', ['all_users' => $all_users])
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

      
 <div>
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden balance-sheet-table-box  d-none">
            
         

            <!-- Current Position -->
            <div class="bg-gray-100 p-6 border-b">
                <div class="flex justify-between items-center mb-4 d-none">
                    <h2 class="text-xl font-semibold text-gray-700">Your Current Position</h2>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-600">Data Period:</label>
                        <input id="dataPeriod" type="number" value="1"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Total Revenue <span id="revenueLabel">(Annual)</span></label>
                        <div id="totalRevenue" class="text-xl font-bold text-blue-600">
                            0Cr
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">GP% <span id="grossProfitLabel">(Gross Margin)</span></label>
                        <div id="grossProfitPercent" class="text-xl font-bold text-green-600">
                            0.0%
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Net Cash Flow <span id="cashFlowLabel">(Annual)</span></label>
                        <div id="netCashFlow" class="text-xl font-bold text-red-600">
                            0Cr
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Operating Profit <span id="profitLabel">(Annual)</span></label>
                        <div id="operatingProfit" class="text-xl font-bold text-red-600">
                            0Cr
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Net Margin</label>
                        <div id="netMargin" class="text-xl font-bold text-gray-700">
                            0.0%
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span>GP: </span><span id="grossProfitAmount" class="font-medium">₹0</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    <span id="periodNote">Note: All financial impacts are calculated and displayed as annual figures based on your selected data period.</span>
                </div>
            </div>

            <!-- Product-wise Revenue Configuration -->
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="icon icon-md text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Product-wise Revenue Configuration
                        <span id="productCount" class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">3 Products</span>
                    </h3>
                    <div class="flex gap-2">
                        <button id="addMultipleProductsBtn" class="d-none bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add 5 Products
                        </button>
                        <button id="addProductBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Product
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="sticky top-0 bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left text-xs font-medium">Product Name</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Base Revenue <span id="productRevenueLabel">(₹ Annual)</span></th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Price Change (%)</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Volume Change (%)</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-2 flex justify-between items-center text-sm text-gray-600">
                    <span>Total Products: <span id="productCountText">0</span></span>
                    <button id="clearAllProductsBtn" class="text-red-600 hover:text-red-800 px-3 py-1 rounded border border-red-300 hover:border-red-500 transition-colors">
                        Clear All Products
                    </button>
                </div>
            </div>

            <!-- Expense-wise Cost Configuration -->
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="icon icon-md text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                        Expense-wise Cost Configuration
                        <span id="expenseCount" class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">9 Expenses</span>
                    </h3>
                    <div class="flex gap-2">
                        <button id="addMultipleExpensesBtn" class="d-none bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-orange-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add 5 Expenses
                        </button>
                        <button id="addExpenseBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-colors">
                            <svg class="icon icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Expense
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="sticky top-0 bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left text-xs font-medium">Expense Name</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Base Amount <span id="expenseAmountLabel">(₹ Annual)</span></th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Category</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Reduction (%)</th>
                                    <th class="border border-gray-300 p-2 text-center text-xs font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody id="expensesTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-2 flex justify-between items-center text-sm text-gray-600">
                    <span>Total Expenses: <span id="expenseCountText">0</span></span>
                    <button id="clearAllExpensesBtn" class="text-red-600 hover:text-red-800 px-3 py-1 rounded border border-red-300 hover:border-red-500 transition-colors">
                        Clear All Expenses
                    </button>
                </div>
            </div>

            <!-- Working Capital Parameters -->
            <div class="p-6 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Working Capital Optimization
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-blue-700 mb-3">Receivables Management</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Current Receivables Days</label>
                                <input type="number" step="0.01" id="currentReceivablesDays" value="92" class="w-full p-2 border border-gray-300 rounded text-xs">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Receivables Days Reduction</label>
                                <input type="number" step="0.1" id="receivablesDaysReduction" value="10" class="w-full p-2 border border-gray-300 rounded text-xs bg-yellow-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-blue-700 mb-3">Inventory Management</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Current Inventory Days</label>
                                <input type="number" id="currentInventoryDays" step="0.01" value="95" class="w-full p-2 border border-gray-300 rounded text-xs">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Inventory Days Reduction</label>
                                <input type="number" step="0.1" id="inventoryDaysReduction" value="15" class="w-full p-2 border border-gray-300 rounded text-xs bg-yellow-50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border">
                        <h4 class="font-medium text-blue-700 mb-3">Payables Management</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Current Payables Days</label>
                                <input type="number" id="currentPayableDays" step="0.01" value="30" class="w-full p-2 border border-gray-300 rounded text-xs">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Payables Days Increase</label>
                                <input type="number" step="0.1" id="payableDaysIncrease" value="15" class="w-full p-2 border border-gray-300 rounded text-xs bg-yellow-50">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Impact Summary -->
            <div class="p-6 bg-gradient-to-r from-purple-50 to-blue-50 border-b">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Overall Combined Impact Summary
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg border border-purple-200">
                        <h4 class="font-medium text-purple-700 mb-2">Revenue Impact</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Price Optimization:</span>
                                <span id="totalPriceImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Volume Growth:</span>
                                <span id="totalVolumeImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="border-t pt-1 flex justify-between font-medium">
                                <span>Total Revenue Impact:</span>
                                <span id="totalRevenueImpact" class="font-mono text-green-600">₹0</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-purple-200">
                        <h4 class="font-medium text-purple-700 mb-2">Cost Impact</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>COGS Optimization:</span>
                                <span id="totalCOGSImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Overhead Optimization:</span>
                                <span id="totalOverheadImpact" class="font-mono">₹0</span>
                            </div>
                            <div class="border-t pt-1 flex justify-between font-medium">
                                <span>Total Cost Savings:</span>
                                <span id="totalCostImpact" class="font-mono text-green-600">₹0</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-purple-200">
                        <h4 class="font-medium text-purple-700 mb-2">Working Capital Impact</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Receivables:</span>
                                <span id="receivablesImpactSummary" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Inventory:</span>
                                <span id="inventoryImpactSummary" class="font-mono">₹0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Payables:</span>
                                <span id="payablesImpactSummary" class="font-mono">₹0</span>
                            </div>
                            <div class="border-t pt-1 flex justify-between font-medium">
                                <span>Total WC Impact:</span>
                                <span id="totalWCImpact" class="font-mono text-blue-600">₹0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-white rounded-lg border-2 border-purple-300">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-purple-800">Combined Overall Impact</h4>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Operating Profit Improvement</div>
                            <div id="combinedProfitImpact" class="text-2xl font-bold text-purple-700">₹0</div>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-between items-center text-sm">
                        <span class="text-gray-600">This represents the total impact when ALL changes are implemented simultaneously</span>
                        <div class="text-right">
                            <div class="text-gray-600">Cash Flow Improvement</div>
                            <div id="combinedCashFlowImpact" class="text-lg font-semibold text-blue-700">₹0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Impact of Change Analysis
                </h2>
                <div class="mb-4 text-sm text-gray-600">
                    <span id="analysisNote">All impacts below are calculated as annual figures based on your selected data period.</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-3 text-left font-semibold text-gray-700 w-1/3">Improvement Lever</th>
                                <th class="border border-gray-300 p-3 text-center font-semibold text-gray-700 w-1/4">Driver Details</th>
                                <th class="border border-gray-300 p-3 text-center font-semibold text-gray-700 w-1/5">Impact on Cash Flow <span id="analysisFlowLabel">(Annual)</span></th>
                                <th class="border border-gray-300 p-3 text-center font-semibold text-gray-700 w-1/5">Impact on Operating Profit <span id="analysisProfitLabel">(Annual)</span></th>
                            </tr>
                        </thead>
                        <tbody id="analysisTableBody">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="p-6 bg-gradient-to-r from-green-50 to-blue-50 border-t">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center gap-2">
                    <svg class="icon icon-md text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Transformation Results Summary
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg border border-green-200">
                        <h4 class="text-lg font-semibold text-green-700 mb-3">Financial Impact</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">New Operating Profit:</span>
                                <div id="newOperatingProfit" class="text-xl font-bold text-green-600">
                                    0Cr
                                </div>
                                <div id="operatingProfitImprovement" class="text-sm text-green-600">
                                    Improvement: 0Cr
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">New Cash Flow Position:</span>
                                <div id="newCashFlow" class="text-xl font-bold text-green-600">
                                    0Cr
                                </div>
                                <div id="cashFlowImprovement" class="text-sm text-green-600">
                                    Improvement: 0Cr
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg border border-blue-200">
                        <h4 class="text-lg font-semibold text-blue-700 mb-3">Performance Metrics</h4>
                        <div class="space-y-2 text-sm">
                            <div id="currentMargin">Current Profit Margin: 0.0%</div>
                            <div id="newMargin">New Profit Margin: 0.0%</div>
                            <div id="marginImprovement">Margin Improvement: 0.0%</div>
                            <div id="cashFlowPercent">Cash Flow Improvement: 0.0% of revenue</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script src="{{asset('assets/js/pages/report/impact-of-change-report.js?ver='.time())}}" type="text/javascript"></script>
@endsection