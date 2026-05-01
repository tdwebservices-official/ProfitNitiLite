// Global variables
let products = [
    { 
        id: 1, 
        name: 'Product A', 
        baseRevenue: 15394720, 
        priceChange: 5, 
        volumeChange: 5
    }
];

let expenseItems = [
    { 
        id: 1, 
        name: 'COGS', 
        baseAmount: 0, 
        reduction: 10, 
        category: 'cogs'
    },
    { 
        id: 2, 
        name: 'Overheads', 
        baseAmount: 0, 
        reduction: 10, 
        category: 'overheads'
    },
];

let businessParams = {
    receivablesDaysReduction: 10,
    inventoryDaysReduction: 15,
    payableDaysIncrease: 15,
    currentReceivablesDays: 92,
    currentInventoryDays: 95,
    currentPayableDays: 30
};
jQuery(document).ready(function() {
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

 $('#addMultipleProductsBtn').on('click', function (e) {
        e.preventDefault();
        console.log('Add Multiple Products button clicked');
        addMultipleProducts();
    });

    $('#clearAllProductsBtn').on('click', function (e) {
        e.preventDefault();
        console.log('Clear All Products button clicked');
        clearAllProducts();
    });

    $('#addExpenseBtn').on('click', function (e) {
        e.preventDefault();
        console.log('Add Expense button clicked');
        addExpenseItem();
    });

    $('#addMultipleExpensesBtn').on('click', function (e) {
        e.preventDefault();
        console.log('Add Multiple Expenses button clicked');
        addMultipleExpenses();
    });

    $('#clearAllExpensesBtn').on('click', function (e) {
        e.preventDefault();
        console.log('Clear All Expenses button clicked');
        clearAllExpenses();
    });

    $('#dataPeriod').on('change', function () {
        console.log('Data period changed');
        updatePeriodLabels();
        calculateResults();
    });

    setTimeout(function(){
        jQuery('.assign_by').select2({ 'placeholder' : "Choose Assign" });

        
    $('#date_filter').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
         showDropdowns: true,
    }, function(start, end, label) {
        var daterange = start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD');
        jQuery('#date_filter .form-control').val( daterange);
                    jQuery('.btn-download-report').removeAttr('disabled');

    });
        
    },100);
    
    jQuery(document).on('click','#addProductBtn',function(e){
         e.preventDefault();
         addProduct();
    });
    
    jQuery(document).on('click','.delete-custom-row',function(){
       jQuery(this).closest('tr').remove(); 
       var data_id = jQuery(this).attr('data-id');
       var data_type = jQuery(this).attr('data-type');
       if( data_type == 'product' ){
           products = $.grep(products, function(p_item) {
                return p_item.id != data_id;
            });
       }
       if( data_type == 'expense' ){
          expenseItems = $.grep(expenseItems, function(e_item) {
                return e_item.id != data_id;
          });
       }
       initialize();
    });


    $('#impact-of-change-report-form').validate({
        // Validate only visible fields
        ignore: ":hidden",
        // Validation rules
        rules: {
            // 'price_increase' : {
            //     required : true
            // },
            // 'volume_increase' : {
            //     required : true
            // },
            // 'cogs_reduction' : {
            //     required : true
            // }, 
            // 'overheads_reduction' : {
            //     required : true
            // }, 
            // 'receivable_days' :  {
            //     required : true
            // },
            // 'inventory_days' : {
            //     required : true
            // }, 
            // 'payable_days' :  {
            //     required : true
            // },
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
            
            jQuery('.balance-sheet-table-box').addClass('d-none');
            jQuery('.report_type').val('view');
            jQuery('#impact-of-change-report-form button[type="submit"]').siblings('.spinner-border').show();
             $('#impact-of-change-report-form').ajaxSubmit({
            url: site_url+'/api/getImpactOfChangeReport',
                success: function(res) {
                    jQuery('#impact-of-change-report-form button[type="submit"]').siblings('.spinner-border').hide();

                    if( res.status == 'success' ){
                        
                        jQuery('#totalRevenue').text( res.totalRevenue ).attr('data-total',res.totalRevenue).attr('data-cogs', res.total_cogs).attr('data-overheads', res.total_overheads);
                        products[0].baseRevenue = res.totalRevenue;
                        expenseItems[0].baseAmount = res.total_cogs;
                        expenseItems[1].baseAmount = res.total_overheads;
                        jQuery('#grossProfitPercent').text( res.grossProfitPercent+'%' ).attr('data-total',res.grossProfitPercent);
                        jQuery('#netCashFlow').text( res.totalRevenue ).attr('data-total',res.final_net_cashflow);
                        jQuery('#operatingProfit').text( res.operatingProfit ).attr('data-total',res.operatingProfit);
                        jQuery('#netMargin').text( res.netMargin+'%' ).attr('data-total',res.netMargin);
                        jQuery('#grossProfitAmount').text( res.grossProfit ).attr('data-total',res.grossProfit);
                        jQuery('.balance-sheet-table-box').removeClass('d-none');
                        jQuery('#dataPeriod').val(res.month_count);
                        businessParams.currentReceivablesDays = res.total_acc_rec_days;
                        businessParams.currentInventoryDays =  res.total_inventory_days;
                        businessParams.currentPayableDays =  res.total_acc_pay_days;
                        
                        jQuery("#currentReceivablesDays").val( res.total_acc_rec_days );
                        jQuery("#currentInventoryDays").val( res.total_inventory_days );
                        jQuery("#currentPayableDays").val( res.total_acc_pay_days );
                     
                        initialize();
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
                jQuery('#impact-of-change-report-form button[type="submit"]').siblings('.spinner-border').hide();
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
/*
    jQuery(document).on( 'click', '.btn-download-report', function () {
         jQuery('#impact-of-change-report-form .btn-download-report').siblings('.spinner-border').show();
         jQuery('.report_type').val('download');

        $('#impact-of-change-report-form').ajaxSubmit({
            url: site_url+'/api/getImpactOfChangeReport',
            success: function(res) {
                jQuery('#impact-of-change-report-form .btn-download-report').siblings('.spinner-border').hide();

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
                jQuery('#impact-of-change-report-form .btn-download-report').siblings('.spinner-border').hide();
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
    } ); */
    
});


// Utility functions
function formatCurrency(amount) {
    var amount_new = amount.toFixed(0);
    
    return parseFloat(amount_new).toLocaleString('en-IN');
    if (Math.abs(amount) >= 10000000) {
        return `${(amount / 10000000).toFixed(1)}Cr`;
    } else if (Math.abs(amount) >= 100000) {
        return `${(amount / 100000).toFixed(1)}L`;
    } else {
        return `₹${amount.toLocaleString('en-IN')}`;
    }
}

function getCategoryColor(category) {
    const colors = {
        revenue: 'bg-green-100',
        cost: 'bg-red-100', 
        working_capital: 'bg-blue-100'
    };
    return colors[category] || 'bg-gray-100';
}

// Product management functions
function addProduct() {
    console.log('Adding product...');
    const newId = Math.max(...products.map(p => p.id), 0) + 1;
    const newProduct = {
        id: newId,
        name: `Product ${String.fromCharCode(65 + products.length)}`,
        baseRevenue: 0,
        priceChange: 5,
        volumeChange: 5
    };
    products.push(newProduct);
    console.log('New product added:', newProduct);
    console.log('Total products:', products.length);
    renderProducts();
    calculateResults();
}

function applyExampleScenario() {
    console.log('Applying example scenario...');
    
    // Apply revenue changes: Price +5%, Volume -2%
    products = products.map(p => ({
        ...p,
        priceChange: 5,
        volumeChange: -2
    }));

    // Apply cost changes: COGS -5%, Overheads +2%
    expenseItems = expenseItems.map(e => ({
        ...e,
        reduction: e.category === 'cogs' ? 5 : -2  // Negative means increase for overheads
    }));

    // Apply working capital changes
    businessParams.receivablesDaysReduction = -2;  // Increase by 2 days (negative reduction)
    businessParams.inventoryDaysReduction = 10;    // Reduce by 10 days
    businessParams.payableDaysIncrease = 7;        // Increase by 7 days

    // Update the input fields to reflect changes
    document.getElementById('receivablesDaysReduction').value = -2;
    document.getElementById('inventoryDaysReduction').value = 10;
    document.getElementById('payableDaysIncrease').value = 7;

    // Re-render and recalculate
    renderProducts();
    renderExpenses();
    calculateResults();

    // Show confirmation
    alert('Example scenario applied! Check the "Overall Combined Impact Summary" to see how all changes work together.');
}

function addMultipleProducts() {
    console.log('Adding multiple products...');
    for (let i = 0; i < 5; i++) {
        const newId = Math.max(...products.map(p => p.id), 0) + 1;
        products.push({
            id: newId,
            name: `Product ${String.fromCharCode(65 + products.length)}`,
            baseRevenue: 0,
            priceChange: 5,
            volumeChange: 5
        });
    }
    console.log('Added 5 products. Total products:', products.length);
    renderProducts();
    calculateResults();
}

function clearAllProducts() {
    if (confirm('Are you sure you want to clear all products? This action cannot be undone.')) {
        console.log('Clearing all products...');
        products = [{
            id: 1,
            name: 'Product A',
            baseRevenue: 0,
            priceChange: 5,
            volumeChange: 5
        }];
        console.log('Products cleared. Reset to:', products.length);
        renderProducts();
        calculateResults();
    }
}

function updateProduct(id, field, value) {
    console.log('Updating product:', id, field, value);
    var totalAllSales = 0;
    var oldtotalAllSales = jQuery('#totalRevenue').attr('data-total');
     jQuery('#productsTableBody+tfoot').remove();
    if( products.length > 0 ){
        jQuery('#productsTableBody > tr').each(function(p_key,p_val){
            var d_baseRevenue = jQuery(this).find('td:nth-child(2) input').val();
            totalAllSales = parseInt(totalAllSales) + parseInt(d_baseRevenue);
        });
        if( parseFloat(totalAllSales) > parseFloat(oldtotalAllSales)  ){
            jQuery('#productsTableBody').after('<tfoot><tr><td colspan="5" class="text-red-800 text-xs font-medium">You have entered total revenue is '+parseFloat(totalAllSales).toLocaleString('en-IN')+' and this is more than '+parseFloat(oldtotalAllSales).toLocaleString('en-IN')+'</td></tr></tfoot>');
          return false;  
        }else if(parseFloat(totalAllSales) < parseFloat(oldtotalAllSales)){
           
            jQuery('#productsTableBody').after('<tfoot><tr><td colspan="5" class="text-red-800 text-xs font-medium">You have added total revenue is '+parseFloat(totalAllSales).toLocaleString('en-IN')+', So need to add total revenue at least '+parseFloat(oldtotalAllSales).toLocaleString('en-IN')+'</td></tr></tfoot>');
        }
    }else{
            jQuery('#productsTableBody').after('<tfoot><tr><td colspan="5" class="text-red-800 text-xs font-medium">You have added total revenue is '+parseFloat(totalAllSales).toLocaleString('en-IN')+', So need to add total revenue at least '+parseFloat(oldtotalAllSales).toLocaleString('en-IN')+'</td></tr></tfoot>');
        }
    
    products = products.map(p => 
        p.id === id ? { ...p, [field]: field === 'name' ? value : (parseFloat(value) || 0) } : p
    );
    calculateResults();
}

// Expense management functions
function addExpenseItem() {
    console.log('Adding expense item...');
    const newId = Math.max(...expenseItems.map(e => e.id), 0) + 1;
    const newExpenseItem = {
        id: newId,
        name: 'New Expense',
        baseAmount: 0,
        reduction: 5,
        category: 'cogs'
    };
    expenseItems.push(newExpenseItem);
    console.log('New expense added:', newExpenseItem);
    console.log('Total expenses:', expenseItems.length);
    renderExpenses();
    calculateResults();
}

function addMultipleExpenses() {
    console.log('Adding multiple expenses...');
    for (let i = 0; i < 5; i++) {
        const newId = Math.max(...expenseItems.map(e => e.id), 0) + 1;
        expenseItems.push({
            id: newId,
            name: `New Expense ${i + 1}`,
            baseAmount: 0,
            reduction: 5,
            category: 'cogs'
        });
    }
    console.log('Added 5 expenses. Total expenses:', expenseItems.length);
    renderExpenses();
    calculateResults();
}

function clearAllExpenses() {
    if (confirm('Are you sure you want to clear all expenses? This action cannot be undone.')) {
        console.log('Clearing all expenses...');
        expenseItems = [{
            id: 1,
            name: 'Raw Materials',
            baseAmount: 0,
            reduction: 5,
            category: 'cogs'
        }];
        console.log('Expenses cleared. Reset to:', expenseItems.length);
        renderExpenses();
        calculateResults();
    }
}

function updateExpenseItem(id, field, value) {
    console.log('Updating expense:', id, field, value);
    
    var totalExpense = 0;
    var oldCOgs = jQuery('#totalRevenue').attr('data-cogs');
    var oldOverheads = jQuery('#totalRevenue').attr('data-overheads');
    var oldtotalExpense = parseInt(oldCOgs) + parseInt(oldOverheads);
    jQuery('#expensesTableBody+tfoot').remove();
    if( products.length > 0 ){
        jQuery('#expensesTableBody > tr').each(function(p_key,p_val){
            var d_baseRevenue = jQuery(this).find('td:nth-child(2) input').val();
            totalExpense = parseInt(totalExpense) + parseInt(d_baseRevenue);
        });
        
        if( parseFloat(totalExpense) > parseFloat(oldtotalExpense)  ){
            jQuery('#expensesTableBody').after('<tfoot><tr><td colspan="5" class="text-red-800 text-xs font-medium">You have entered total expense is '+parseFloat(totalExpense).toLocaleString('en-IN')+' and this is more than '+parseFloat(oldtotalExpense).toLocaleString('en-IN')+'</td></tr></tfoot>');
          return false;  
        }else if( parseFloat(totalExpense) < parseFloat(oldtotalExpense) ){
            jQuery('#expensesTableBody').after('<tfoot><tr><td colspan="5" class="text-red-800 text-xs font-medium">You have added total expense is '+parseFloat(totalExpense).toLocaleString('en-IN')+', So need to add total expense at least '+parseFloat(oldtotalExpense).toLocaleString('en-IN')+'</td></tr></tfoot>');
        }
        
       
    }else{
 jQuery('#expensesTableBody').after('<tfoot><tr><td colspan="5" class="text-red-800 text-xs font-medium">You have added total expense is '+parseFloat(totalExpense).toLocaleString('en-IN')+', So need to add total expense at least '+parseFloat(oldtotalExpense).toLocaleString('en-IN')+'</td></tr></tfoot>');
 }
    
    expenseItems = expenseItems.map(e => 
        e.id === id ? { ...e, [field]: (field === 'name' || field === 'category') ? value : (parseFloat(value) || 0) } : e
    );
    calculateResults();
}

// Business parameters update
function updateBusinessParam(field, value) {
    businessParams[field] = parseFloat(value) || 0;
    calculateResults();
}

// Period label update function
function updatePeriodLabels() {
    const period = parseInt(document.getElementById('dataPeriod').value);
    let periodText = '';
    
    switch(period) {
        case 1: periodText = '(Monthly)'; break;
        case 2: periodText = '(2 Months)'; break;
        case 3: periodText = '(Quarterly)'; break;
        case 4: periodText = '(4 Months)'; break;
        case 6: periodText = '(Half-Yearly)'; break;
        case 9: periodText = '(9 Months)'; break;
        case 12: periodText = '(Annual)'; break;
        default: periodText = `(${period} Months)`;
    }
    
    // Update all period labels
    document.getElementById('revenueLabel').textContent = periodText;
    document.getElementById('cashFlowLabel').textContent = '(Annual)';
    document.getElementById('profitLabel').textContent = '(Annual)';
    document.getElementById('productRevenueLabel').textContent = `(₹ ${periodText.replace('(', '').replace(')', '')})`;
    document.getElementById('expenseAmountLabel').textContent = `(₹ ${periodText.replace('(', '').replace(')', '')})`;
    document.getElementById('analysisFlowLabel').textContent = '(Annual)';
    document.getElementById('analysisProfitLabel').textContent = '(Annual)';
    
    // Update explanatory notes
    if (period === 12) {
        document.getElementById('periodNote').textContent = 'Note: All financial impacts are calculated and displayed as annual figures.';
        document.getElementById('analysisNote').textContent = 'All impacts below are calculated as annual figures.';
    } else {
        document.getElementById('periodNote').textContent = `Note: Your input data represents ${period} month(s). All financial impacts are annualized and displayed as annual figures.`;
        document.getElementById('analysisNote').textContent = `All impacts below are annualized based on your ${period}-month input data.`;
    }
}

function renderProducts() {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';

    products.forEach(product => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="border border-gray-300 p-2">
                <input type="text" value="${product.name}" onchange="updateProduct(${product.id}, 'name', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs">
            </td>
            <td class="border border-gray-300 p-2">
                <input type="number" value="${product.baseRevenue}" onchange="updateProduct(${product.id}, 'baseRevenue', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs text-center">
            </td>
            <td class="border border-gray-300 p-2">
                <input type="number" step="0.1" value="${product.priceChange}" onchange="updateProduct(${product.id}, 'priceChange', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs text-center bg-yellow-100">
            </td>
            <td class="border border-gray-300 p-2">
                <input type="number" step="0.1" value="${product.volumeChange}" onchange="updateProduct(${product.id}, 'volumeChange', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs text-center bg-green-100">
            </td>
            <td class="border border-gray-300 p-2">
               <a href="javascript:void(0)" data-id="${product.id}" data-type="product" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-custom-row">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
               </a>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Update count displays
    document.getElementById('productCount').textContent = `${products.length} Products`;
    document.getElementById('productCountText').textContent = products.length;
}

function renderExpenses() {
    const tbody = document.getElementById('expensesTableBody');
    tbody.innerHTML = '';

    expenseItems.forEach(item => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="border border-gray-300 p-2">
                <input type="text" value="${item.name}" onchange="updateExpenseItem(${item.id}, 'name', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs">
            </td>
            <td class="border border-gray-300 p-2">
                <input type="number" value="${item.baseAmount}" onchange="updateExpenseItem(${item.id}, 'baseAmount', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs text-center">
            </td>
            <td class="border border-gray-300 p-2">
                <select onchange="updateExpenseItem(${item.id}, 'category', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs text-center">
                    <option value="cogs" ${item.category === 'cogs' ? 'selected' : ''}>COGS</option>
                    <option value="overheads" ${item.category === 'overheads' ? 'selected' : ''}>Overheads</option>
                </select>
            </td>
            <td class="border border-gray-300 p-2">
                <input type="number" step="0.1" value="${item.reduction}" onchange="updateExpenseItem(${item.id}, 'reduction', this.value)" class="w-full p-1 border border-gray-300 rounded text-xs text-center bg-orange-100">
            </td>
            <td class="border border-gray-300 p-2">
               <a href="javascript:void(0)" data-id="${item.id}" data-type="expense" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-custom-row">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
               </a>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Update count displays
    document.getElementById('expenseCount').textContent = `${expenseItems.length} Expenses`;
    document.getElementById('expenseCountText').textContent = expenseItems.length;
}

// Main calculation function
function calculateResults() {
    // Get the data period in months
    const dataPeriodMonths = parseInt(document.getElementById('dataPeriod').value);
    const annualizationFactor = 12 / dataPeriodMonths;
    
    // Calculate base totals and annualize them
    const inputRevenue = products.reduce((sum, p) => sum + p.baseRevenue, 0);
    const inputCOGS = expenseItems.filter(e => e.category === 'cogs').reduce((sum, e) => sum + e.baseAmount, 0);
    const inputOverheads = expenseItems.filter(e => e.category === 'overheads').reduce((sum, e) => sum + e.baseAmount, 0);
    
    // Annualized figures for calculations
    const totalRevenue = inputRevenue * annualizationFactor;
    const totalCOGS = inputCOGS * annualizationFactor;
    const totalOverheads = inputOverheads * annualizationFactor;
    const grossProfit = totalRevenue - totalCOGS;
    const currentOperatingProfit = grossProfit - totalOverheads;
    
    // Working capital calculations (always based on annual figures)
    const dailySales = totalRevenue / 365;
    const dailyCOGS = totalCOGS / 365;
    const currentNetCashFlow = -(dailySales * businessParams.currentReceivablesDays) - (dailyCOGS * businessParams.currentInventoryDays) + (dailyCOGS * businessParams.currentPayableDays);

    // Calculate improvement impacts (all annualized)
    const impacts = {
        productPriceOptimization: {
            name: 'Product-wise Price Optimization',
            driver: products.map(p => `${p.name}: ${p.priceChange}%`).join(', '),
            operatingProfitImpact: products.reduce((sum, p) => sum + (p.baseRevenue * annualizationFactor * (p.priceChange / 100)), 0),
            cashFlowImpact: products.reduce((sum, p) => sum + (p.baseRevenue * annualizationFactor * (p.priceChange / 100)), 0),
            category: 'revenue'
        },
        
        productVolumeGrowth: {
            name: 'Product-wise Volume Growth',
            driver: products.map(p => `${p.name}: ${p.volumeChange}%`).join(', '),
            operatingProfitImpact: products.reduce((sum, p) => {
                const annualizedRevenue = p.baseRevenue * annualizationFactor;
                const revenueIncrease = annualizedRevenue * (p.volumeChange / 100);
                // COGS increase proportionally with volume
                const cogsRatio = totalCOGS / totalRevenue;
                const cogsIncrease = revenueIncrease * cogsRatio;
                return sum + (revenueIncrease - cogsIncrease);
            }, 0),
            cashFlowImpact: products.reduce((sum, p) => {
                const annualizedRevenue = p.baseRevenue * annualizationFactor;
                const revenueIncrease = annualizedRevenue * (p.volumeChange / 100);
                const cogsRatio = totalCOGS / totalRevenue;
                const cogsIncrease = revenueIncrease * cogsRatio;
                return sum + (revenueIncrease - cogsIncrease);
            }, 0),
            category: 'revenue'
        },

        cogsOptimization: {
            name: 'COGS Line-item Optimization',
            driver: expenseItems.filter(e => e.category === 'cogs').map(e => `${e.name}: ${e.reduction}%`).join(', '),
            operatingProfitImpact: expenseItems.filter(e => e.category === 'cogs').reduce((sum, e) => sum + (e.baseAmount * annualizationFactor * (e.reduction / 100)), 0),
            cashFlowImpact: expenseItems.filter(e => e.category === 'cogs').reduce((sum, e) => sum + (e.baseAmount * annualizationFactor * (e.reduction / 100)), 0),
            category: 'cost'
        },

        overheadOptimization: {
            name: 'Overhead Cost Optimization',
            driver: expenseItems.filter(e => e.category === 'overheads').map(e => `${e.name}: ${e.reduction}%`).join(', '),
            operatingProfitImpact: expenseItems.filter(e => e.category === 'overheads').reduce((sum, e) => sum + (e.baseAmount * annualizationFactor * (e.reduction / 100)), 0),
            cashFlowImpact: expenseItems.filter(e => e.category === 'overheads').reduce((sum, e) => sum + (e.baseAmount * annualizationFactor * (e.reduction / 100)), 0),
            category: 'cost'
        },

        receivablesOptimization: {
            name: 'Receivables Days Reduction',
            driver: `${businessParams.receivablesDaysReduction} days`,
            operatingProfitImpact: 0,
            cashFlowImpact: dailySales * businessParams.receivablesDaysReduction,
            category: 'working_capital'
        },

        inventoryOptimization: {
            name: 'Inventory Days Reduction', 
            driver: `${businessParams.inventoryDaysReduction} days`,
            operatingProfitImpact: 0,
            cashFlowImpact: dailyCOGS * businessParams.inventoryDaysReduction,
            category: 'working_capital'
        },

        payablesOptimization: {
            name: 'Payables Days Increase',
            driver: `${businessParams.payableDaysIncrease} days`,
            operatingProfitImpact: 0,
            cashFlowImpact: dailyCOGS * businessParams.payableDaysIncrease,
            category: 'working_capital'
        }
    };

    // Calculate totals
    const totalOperatingProfitImpact = Object.values(impacts).reduce((sum, impact) => sum + impact.operatingProfitImpact, 0);
    const totalCashFlowImpact = Object.values(impacts).reduce((sum, impact) => sum + impact.cashFlowImpact, 0);

    const results = {
        currentOperatingProfit,
        currentNetCashFlow,
        grossProfit,
        totalCOGS,
        totalOverheads,
        impacts,
        totalOperatingProfitImpact,
        totalCashFlowImpact,
        newOperatingProfit: currentOperatingProfit + totalOperatingProfitImpact,
        newNetCashFlow: currentNetCashFlow + totalCashFlowImpact,
        totalRevenue,
        inputRevenue,
        inputCOGS,
        inputOverheads,
        dataPeriodMonths
    };

    updateDisplay(results);
}

// Display update functions
function updateDisplay(results) {
    // Update current position - show input period data for revenue, annualized for others
    if (results.dataPeriodMonths === 12) {
        document.getElementById('totalRevenue').textContent = formatCurrency(results.totalRevenue);
    } else {
        document.getElementById('totalRevenue').textContent = formatCurrency(results.inputRevenue);
    }
    
    // Calculate and display margins first
    const grossMargin = (results.grossProfit / results.totalRevenue) * 100;
    const netMargin = (results.currentOperatingProfit / results.totalRevenue) * 100;
    
    // Always show annualized figures for profit and cash flow
    document.getElementById('grossProfitPercent').textContent = `${grossMargin.toFixed(1)}%`;
    document.getElementById('grossProfitAmount').textContent = formatCurrency(results.grossProfit);
    document.getElementById('netCashFlow').textContent = formatCurrency(results.currentNetCashFlow);
    document.getElementById('operatingProfit').textContent = formatCurrency(results.currentOperatingProfit);
    document.getElementById('netMargin').textContent = `${netMargin.toFixed(1)}%`;

    // Update analysis table
    renderAnalysisTable(results);

    // Update overall impact summary
    updateOverallImpactSummary(results);

    // Update results summary
    document.getElementById('newOperatingProfit').textContent = formatCurrency(results.newOperatingProfit);
    document.getElementById('operatingProfitImprovement').textContent = `Improvement: ${formatCurrency(results.totalOperatingProfitImpact)}`;
    document.getElementById('newCashFlow').textContent = formatCurrency(results.newNetCashFlow);
    document.getElementById('cashFlowImprovement').textContent = `Improvement: ${formatCurrency(results.totalCashFlowImpact)}`;

    // Update performance metrics
    const currentNetMargin = (results.currentOperatingProfit / results.totalRevenue) * 100;
    const newNetMargin = (results.newOperatingProfit / results.totalRevenue) * 100;
    const marginImprovement = newNetMargin - currentNetMargin;
    const cashFlowPercent = (results.totalCashFlowImpact / results.totalRevenue) * 100;

    document.getElementById('currentMargin').textContent = `Current Net Margin: ${currentNetMargin.toFixed(1)}%`;
    document.getElementById('newMargin').textContent = `New Net Margin: ${newNetMargin.toFixed(1)}%`;
    document.getElementById('marginImprovement').textContent = `Net Margin Improvement: ${marginImprovement.toFixed(1)}%`;
    document.getElementById('cashFlowPercent').textContent = `Cash Flow Improvement: ${cashFlowPercent.toFixed(1)}% of revenue`;
}

function renderAnalysisTable(results) {
    const tbody = document.getElementById('analysisTableBody');
    tbody.innerHTML = '';

    Object.entries(results.impacts).forEach(([key, impact]) => {
        const row = document.createElement('tr');
        row.className = `hover:bg-gray-50 ${getCategoryColor(impact.category)}`;
        row.innerHTML = `
            <td class="border border-gray-300 p-3 font-medium">${impact.name}</td>
            <td class="border border-gray-300 p-3 text-center">${impact.driver}</td>
            <td class="border border-gray-300 p-3 text-right font-mono">${formatCurrency(impact.cashFlowImpact || 0)}</td>
            <td class="border border-gray-300 p-3 text-right font-mono">${formatCurrency(impact.operatingProfitImpact || 0)}</td>
        `;
        tbody.appendChild(row);
    });

    // Add total row
    const totalRow = document.createElement('tr');
    totalRow.className = 'bg-green-100 font-bold';
    totalRow.innerHTML = `
        <td class="border border-gray-300 p-3 font-bold">TOTAL IMPACT OF CHANGE</td>
        <td class="border border-gray-300 p-3"></td>
        <td class="border border-gray-300 p-3 text-right font-mono text-green-700">${formatCurrency(results.totalCashFlowImpact || 0)}</td>
        <td class="border border-gray-300 p-3 text-right font-mono text-green-700">${formatCurrency(results.totalOperatingProfitImpact || 0)}</td>
    `;
    tbody.appendChild(totalRow);
}

function updateOverallImpactSummary(results) {
    // Revenue impacts
    const priceImpact = results.impacts.productPriceOptimization.operatingProfitImpact;
    const volumeImpact = results.impacts.productVolumeGrowth.operatingProfitImpact;
    const totalRevenueImpact = priceImpact + volumeImpact;

    document.getElementById('totalPriceImpact').textContent = formatCurrency(priceImpact);
    document.getElementById('totalVolumeImpact').textContent = formatCurrency(volumeImpact);
    document.getElementById('totalRevenueImpact').textContent = formatCurrency(totalRevenueImpact);

    // Cost impacts
    const cogsImpact = results.impacts.cogsOptimization.operatingProfitImpact;
    const overheadImpact = results.impacts.overheadOptimization.operatingProfitImpact;
    const totalCostImpact = cogsImpact + overheadImpact;

    document.getElementById('totalCOGSImpact').textContent = formatCurrency(cogsImpact);
    document.getElementById('totalOverheadImpact').textContent = formatCurrency(overheadImpact);
    document.getElementById('totalCostImpact').textContent = formatCurrency(totalCostImpact);

    // Working capital impacts
    const receivablesImpact = results.impacts.receivablesOptimization.cashFlowImpact;
    const inventoryImpact = results.impacts.inventoryOptimization.cashFlowImpact;
    const payablesImpact = results.impacts.payablesOptimization.cashFlowImpact;
    const totalWCImpact = receivablesImpact + inventoryImpact + payablesImpact;

    document.getElementById('receivablesImpactSummary').textContent = formatCurrency(receivablesImpact);
    document.getElementById('inventoryImpactSummary').textContent = formatCurrency(inventoryImpact);
    document.getElementById('payablesImpactSummary').textContent = formatCurrency(payablesImpact);
    document.getElementById('totalWCImpact').textContent = formatCurrency(totalWCImpact);

    // Combined overall impact
    document.getElementById('combinedProfitImpact').textContent = formatCurrency(results.totalOperatingProfitImpact);
    document.getElementById('combinedCashFlowImpact').textContent = formatCurrency(results.totalCashFlowImpact);
}

// Initialize the application
function initialize() {
    updatePeriodLabels();
    renderProducts();
    renderExpenses();
    calculateResults();
    // Working capital parameter event listeners
    const currentReceivablesDays = document.getElementById('currentReceivablesDays');
    const receivablesDaysReduction = document.getElementById('receivablesDaysReduction');
    const currentInventoryDays = document.getElementById('currentInventoryDays');
    const inventoryDaysReduction = document.getElementById('inventoryDaysReduction');
    const currentPayableDays = document.getElementById('currentPayableDays');
    const payableDaysIncrease = document.getElementById('payableDaysIncrease');

    if (currentReceivablesDays) currentReceivablesDays.addEventListener('input', (e) => updateBusinessParam('currentReceivablesDays', e.target.value));
    if (receivablesDaysReduction) receivablesDaysReduction.addEventListener('input', (e) => updateBusinessParam('receivablesDaysReduction', e.target.value));
    if (currentInventoryDays) currentInventoryDays.addEventListener('input', (e) => updateBusinessParam('currentInventoryDays', e.target.value));
    if (inventoryDaysReduction) inventoryDaysReduction.addEventListener('input', (e) => updateBusinessParam('inventoryDaysReduction', e.target.value));
    if (currentPayableDays) currentPayableDays.addEventListener('input', (e) => updateBusinessParam('currentPayableDays', e.target.value));
    if (payableDaysIncrease) payableDaysIncrease.addEventListener('input', (e) => updateBusinessParam('payableDaysIncrease', e.target.value));

    // Apply scenario button
    const applyScenarioBtn = document.getElementById('applyScenarioBtn');
    if (applyScenarioBtn) {
        applyScenarioBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Apply Scenario button clicked');
            applyExampleScenario();
        });
    }

}