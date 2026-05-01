<?php
return [
    'default' => [
        'asset' => [
            'label' => 'Assets',
            'sub_account' => [
                'fixed_assets' => 'Fixed Assets',
                'non_current_investments' => 'Non-current  Investments',
                'long_term_loans_advances' => 'Long Term Loans & Advances',
                'deferred_tax_assets' => 'Deferred Tax Assets',
                'other_non_current_assets' => 'Other Non-Current Assets',
                'current_investments' => 'Current Investments',
                'closing_stock' => 'Closing Stock',
                'short_term_loans_advances' => 'Short Term Loans & Advances',
                'accounts_receivable' => 'Accounts Receivable',
                'cash_and_bank_balances' => 'Cash and Bank Balances',
                'other_current_assets' => 'Other Current Assets',
                'branch' => 'Branch',
                'suspense' => 'Suspense',
            ]
        ],
        'liability' => [
            'label' => 'Liability',
            'sub_account' => [
                'secured_long_term_borrowings' => 'Secured Long-term Borrowings',
                'unsecured_long_term_borrowings' => 'Unsecured Long-term Borrowings',
                'long_term_provisions' => 'Long Term Provisions',
                'deferred_tax_liabilities' => 'Deferred Tax Liabilities',
                'other_non_current_liabilities' => 'Other Non-Current Liabilities',
                'secured_short_term_borrowings' => 'Secured Short-term Borrowings',
                'accounts_payable' => 'Accounts Payable',
                'short_term_provisions' => 'Short Term Provisions',
                'current_deferred_tax_liabilities' => 'Current Deferred Tax Liabilities',
                'other_current_liabilities' => 'Other Current Liabilities',
                'difference_in_opening_balance' => 'Difference in Opening Balance'
            ]
        ],
        'equity' => [
            'label' => 'Equity',
            'sub_account' => [
                'equity_share_capital' => 'Equity Share Capital',
                'other_equity' => 'Other Equity',
                'reserve_surplus' => 'Reserve & Surplus',
                'current_year_profit' => 'Current Year Profit',            
            ]
        ],
        'income' => [
            'label' => 'Income',
            'sub_account' => [
                'sales' => 'Sales',                
                'other_non_operating_income' => 'Other Non Operating Income',
                'exceptional_extraordinary_income' => 'Exceptional/Extraordinary Income',
  
            ]
        ],
        'expense' => [
            'label' => 'Expense',
            'sub_account' => [
                'cost_of_goods_sold' => 'Cost of Goods Sold',
                'opening_stock' => 'Opening Stock',
                'direct_manufacturing_expenses' => 'Direct/Manufacturing Expenses',
                'gross_profit' => 'Gross Profit',
                'employee_benefit_expenses' => 'Employee Benefit Expenses',
                'selling_general_and_administrative_expenses' => 'Selling, General and Administrative Expenses',
                'depreciation_amortization' => 'Depreciation & Amortization',
                'interest_bank_charges' => 'Interest & Bank Charges',
                'other_non_operating_expenses' => 'Other Non Operating Expenses',
                'exceptional_extraordinary_expense' => 'Exceptional/Extraordinary Expense',
                'current_tax' => 'Current Tax',
                'deferred_tax' => 'Deferred Tax',   
            ]
        ],
        'special_other_accounts' => [
            'label' => 'Special/Other Accounts',
            'sub_account' => [
                'branch_divisions' => 'Branch / Divisions',    
                'suspense_ac' => 'Suspense A/c',    
            ]
        ],
    ],

    'kpi_bl_pl_items' => [
        'equity_share_capital' => 'Equity Share Capital', 
        'other_equity' => 'Other Equity', 
        'reserve_surplus' => 'Reserve & Surplus', 
        'current_year_profit' => 'Current Year Profit', 
        'secured_long_term_borrowings' => 'Secured Long-term Borrowings', 
        'unsecured_long_term_borrowings' => 'Unsecured Long-term Borrowings', 
        'long_term_provisions' => 'Long Term Provisions', 
        'deferred_tax_liabilities' => 'Deferred Tax Liabilities', 
        'other_non_current_liabilities' => 'Other Non-Current Liabilities', 
        'secured_short_term_borrowings' => 'Secured Short-term Borrowings', 
        'accounts_payable' => 'Accounts Payable', 
        'short_term_provisions' => 'Short Term Provisions', 
        'current_deferred_tax_liabilities' => 'Current Deferred Tax Liabilities', 
        'other_current_liabilities' => 'Other Current Liabilities', 
        'difference_in_opening_balance' => 'Difference in Opening Balance', 
        'fixed_assets' => 'Fixed Assets', 
        'non_current_investments' => 'Non-current  Investments', 
        'long_term_loans_advances' => 'Long Term Loans & Advances', 
        'deferred_tax_assets' => 'Deferred Tax Assets', 
        'other_non_current_assets' => 'Other Non-Current Assets', 
        'current_investments' => 'Current Investments', 
        'closing_stock' => 'Closing Stock', 
        'short_term_loans_advances' => 'Short Term Loans & Advances', 
        'accounts_receivable' => 'Accounts Receivable', 
        'cash_and_bank_balances' => 'Cash and Bank Balances', 
        'other_current_assets' => 'Other Current Assets', 
        'branch' => 'Branch', 
        'suspense' => 'Suspense', 
        'sales' => 'Sales', 
        'opening_stock' => 'Opening Stock', 
        //'cost_of_goods_sold' => 'Cost of Goods Sold',
        'direct_manufacturing_expenses' => 'Direct/Manufacturing Expenses',
        'employee_benefit_expenses' => 'Employee Benefit Expenses', 
        'selling_general_and_administrative_expenses' => 'Selling, General and Administrative Expenses', 
        'other_expenses' => 'Other Expenses', 
        'depreciation_amortization' => 'Depreciation & Amortization', 
        'interest_bank_charges' => 'Interest & Bank Charges', 
        'other_non_operating_income' => 'Other Non Operating Income',  
        'other_non_operating_expenses' => 'Other Non Operating Expenses', 
        'exceptional_extraordinary_income' => 'Extraordinary Income', 
        'exceptional_extraordinary_expense' => 'Extraordinary Expense', 
        'current_tax' => 'Current Tax', 
        'deferred_tax' => 'Deferred Tax', 
    ],

    'default_keys' => [
        'asset||fixed_assets' => 'Fixed Assets',
        'asset||non_current_investments' => 'Non-current  Investments',
        'asset||long_term_loans_advances' => 'Long Term Loans & Advances',
        'asset||deferred_tax_assets' => 'Deferred Tax Assets',
        'asset||other_non_current_assets' => 'Other Non-Current Assets',
        'asset||current_investments' => 'Current Investments',
        'asset||closing_stock' => 'Closing Stock',
        'asset||short_term_loans_advances' => 'Short Term Loans & Advances',
        'asset||accounts_receivable' => 'Accounts Receivable',
        'asset||cash_and_bank_balances' => 'Cash and Bank Balances',
        'asset||other_current_assets' => 'Other Current Assets',
        'asset||branch' => 'Branch',
        'asset||suspense' => 'Suspense',
        'liability||secured_long_term_borrowings' => 'Secured Long-term Borrowings',
        'liability||unsecured_long_term_borrowings' => 'Unsecured Long-term Borrowings',
        'liability||long_term_provisions' => 'Long Term Provisions',
        'liability||deferred_tax_liabilities' => 'Deferred Tax Liabilities',
        'liability||other_non_current_liabilities' => 'Other Non-Current Liabilities',
        'liability||secured_short_term_borrowings' => 'Secured Short-term Borrowings',
        'liability||accounts_payable' => 'Accounts Payable',
        'liability||short_term_provisions' => 'Short Term Provisions',
        'liability||current_deferred_tax_liabilities' => 'Current Deferred Tax Liabilities',
        'liability||other_current_liabilities' => 'Other Current Liabilities',
        'liability||difference_in_opening_balance' => 'Difference in Opening Balance',
        'equity||equity_share_capital' => 'Equity Share Capital',
        'equity||other_equity' => 'Other Equity',
        'equity||reserve_surplus' => 'Reserve & Surplus',
        'equity||current_year_profit' => 'Current Year Profit', 
        'income||sales' => 'Sales',
        'income||other_non_operating_income' => 'Other Non Operating Income',
        'income||exceptional_extraordinary_income' => 'Exceptional/Extraordinary Income',
        'expense||cost_of_goods_sold' => 'Cost of Goods Sold',
        'expense||opening_stock' => 'Opening Stock',
        'expense||direct_manufacturing_expenses' => 'Direct/Manufacturing Expenses',
        'expense||gross_profit' => 'Gross Profit',
        'expense||employee_benefit_expenses' => 'Employee Benefit Expenses',
        'expense||selling_general_and_administrative_expenses' => 'Selling, General and Administrative Expenses',        
        'expense||depreciation_amortization' => 'Depreciation & Amortization',
        'expense||interest_bank_charges' => 'Interest & Bank Charges',
        'expense||other_non_operating_expenses' => 'Other Non Operating Expenses',
        'expense||exceptional_extraordinary_expense' => 'Exceptional/Extraordinary Expense',
        'expense||current_tax' => 'Current Tax',
        'expense||deferred_tax' => 'Deferred Tax',    
        'branch_divisions' => 'Branch / Divisions',    
        'suspense_ac' => 'Suspense A/c',    
    ],
    'financial_summary_list' => [
        1 => 'Sales',
        2 => 'Cost of Goods Sold',
        3 => 'Overheads',
        4 => 'Depreciation ',
        5 => 'Other Income',
        6 => 'Extraordinary Expenses',
        7 => 'Finance Costs',
        8 => 'Tax Paid',
        9 => 'Dividends Paid',
        10 => 'Cash & Bank',
        11 => 'Accounts Receivable',
        12 => 'Closing Stock',
        13 => 'Other Current Assets',
        14 => 'Fixed Assets',
        15 => 'Other Non Current Assets',
        16 => 'Accounts Payable',
        17 => 'Bank Loans - Current',
        18 => 'Other Current Liabilities',
        19 => 'Bank Loans - Non Current',
        20 => 'Other Non Current Liabilities',
        21 => 'Equity',                                      
        22 => 'Branch',                                      
        23 => 'Suspense',                                      
    ],
    'balancesheet_report_list' => [
        [
            'data' => [
                'items' => [
                    'equity_share_capital' => [ 'label' => 'Equity Share Capital', 'bold' => false, 'pl' => false, 'bl_rule' => true ],
                    'other_equity' => [ 'label' => 'Other Equity', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'reserve_surplus' => [ 'label' => 'Reserve & Surplus', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'current_year_profit' => [ 'label' => 'Current Year Profit', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                ],
                'total' => [ 'label' => 'Total Equity', 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'secured_long_term_borrowings' => [ 'label' => 'Secured Long-term Borrowings', 'bold' => false, 'pl' => false, 'bl_rule' => true ],
                    'unsecured_long_term_borrowings' => [ 'label' => 'Unsecured Long-term Borrowings', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'long_term_provisions' => [ 'label' => 'Long Term Provisions', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'deferred_tax_liabilities' => [ 'label' => 'Deferred Tax Liabilities', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'other_non_current_liabilities' => [ 'label' => 'Other Non-Current Liabilities', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                ],
                'total' => [ 'label' => 'Total Non-Current Liabilities', 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'secured_short_term_borrowings' => [ 'label' => 'Secured Short-term Borrowings', 'bold' => false, 'pl' => false, 'bl_rule' => true ],
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'short_term_provisions' => [ 'label' => 'Short Term Provisions', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'current_deferred_tax_liabilities' => [ 'label' => 'Current Deferred Tax Liabilities', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'other_current_liabilities' => [ 'label' => 'Other Current Liabilities', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'difference_in_opening_balance' => [ 'label' => 'Difference in Opening Balance', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                ],
                'total' => [ 'label' => 'Total Current Liabilities', 'bold' => true ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Total Liabilites',
                    'items' => [ 'Total Non-Current Liabilities', 'Total Current Liabilities' ],
                    'operators' => [ '+', '+' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Total Equity and Liabilities',
                    'items' => [ 'Total Equity', 'Total Liabilites' ],
                    'operators' => [ '+', '+' ],
                ]
            ]
        ],
        [
            'data' => [
                'items' => [
                    'fixed_assets' => [ 'label' => 'Fixed Assets', 'bold' => false, 'pl' => false, 'bl_rule' => true ],
                    'non_current_investments' => [ 'label' => 'Non-current  Investments', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'long_term_loans_advances' => [ 'label' => 'Long Term Loans & Advances', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'deferred_tax_assets' => [ 'label' => 'Deferred Tax Assets', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'other_non_current_assets' => [ 'label' => 'Other Non-Current Assets', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                ],
                'total' => [ 'label' => 'Total Non-current Assets', 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'current_investments' => [ 'label' => 'Current Investments', 'bold' => false, 'pl' => false, 'bl_rule' => true ],
                    'closing_stock' => [ 'label' => 'Closing Stock', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'short_term_loans_advances' => [ 'label' => 'Short Term Loans & Advances', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'cash_and_bank_balances' => [ 'label' => 'Cash and Bank Balances', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'other_current_assets' => [ 'label' => 'Other Current Assets', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'branch' => [ 'label' => 'Branch', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                    'suspense' => [ 'label' => 'Suspense', 'bold' => false, 'pl' => false, 'bl_rule' => true ],                    
                ],
                'total' => [ 'label' => 'Total Current Assets', 'bold' => true ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Total Assets',
                    'items' => [ 'Total Non-current Assets', 'Total Current Assets' ],
                    'operators' => [ '+', '+' ],
                ]
            ]
        ],
    ],
    'profitloss_report_list' => [
        [
            'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'pl' => false, 'bl_rule' => false ],
                    //'other_income' => [ 'label' => 'Other Income', 'bold' => false, 'pl' => false ],                                        
                ],
                'total' => [ 'label' => 'Sales', 'hide_tr' => true, 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => false  ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold', 'parent_tr_hide' => true, 'download_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'bl_rule' => false ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'bl_rule' => false ],                      
                    'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => false ],      
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'minus_items' => [ 3 ], 'show_label' => 'COGS', 'main_row' => 'final_cogs',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Gross Profit',
                    'items' => [ 'Sales', 'Total Direct Expenses' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'employee_benefit_expenses' => [ 'label' => 'Employee Benefit Expenses', 'bold' => false, 'pl' => true, 'bl_rule' => false ],
                    'selling_general_and_administrative_expenses' => [ 'label' => 'Selling, General and Administrative Expenses', 'bold' => false, 'pl' => true, 'bl_rule' => false ],                    
                    'other_expenses' => [ 'label' => 'Other Expenses', 'bold' => false, 'pl' => true ],                                        
                ],
                'total' => [ 'label' => 'Total Expenses', 'bold' => true ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'EBITDA',
                    'items' => [ 'Gross Profit', 'Total Expenses' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'depreciation_amortization' => [ 'label' => 'Depreciation & Amortization', 'bold' => false, 'pl' => true, 'bl_rule' => false ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'PBIT',
                    'items' => [ 'EBITDA', 'Depreciation & Amortization' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'interest_bank_charges' => [ 'label' => 'Interest & Bank Charges', 'bold' => false, 'pl' => true, 'bl_rule' => false ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit after Interest and before Tax',
                    'items' => [ 'PBIT', 'Interest & Bank Charges' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'other_non_operating_income' => [ 'label' => 'Other Non Operating Income',  'bold' => false, 'pl' => false, 'bl_rule' => false ],                                        
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'other_non_operating_expenses' => [ 'label' => 'Other Non Operating Expenses', 'bold' => false, 'pl' => true, 'bl_rule' => false ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit before Exceptional/Extraordinary Items and Tax',
                    'items' => [ 'Profit after Interest and before Tax', 'Other Non Operating Income', 'Other Non Operating Expenses' ],
                    'operators' => [ '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'exceptional_extraordinary_income' => [ 'label' => 'Extraordinary Income', 'bold' => false, 'pl' => false, 'bl_rule' => false ],    
                         
                    'exceptional_extraordinary_expense' => [ 'label' => 'Extraordinary Expense', 'bold' => false, 'pl' => true, 'bl_rule' => false ],      
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'PBT',
                    'items' => [ 'Profit before Exceptional/Extraordinary Items and Tax', 'Extraordinary Income', 'Extraordinary Expense' ],
                    'operators' => [ '+', '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'current_tax' => [ 'label' => 'Current Tax', 'bold' => false, 'pl' => true, 'bl_rule' => false ],                                        
                    'deferred_tax' => [ 'label' => 'Deferred Tax', 'bold' => false, 'pl' => true, 'bl_rule' => false ],      
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit after Tax',
                    'items' => [ 'PBT', 'Current Tax', 'Deferred Tax' ],
                    'operators' => [ '+', '-', '-' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Retained Dividend Paid',
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => true,
                    'label' => 'Retained Profit',
                    'items' => [ 'Profit after Tax', 'Retained Dividend Paid' ],
                    'operators' => [ '+', '-'],
                ]                
            ]
        ]
    ],
    'new_financial_summary_report_list' => [
        [
            'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'pl' => false, 'r_type' => 'pl', 'bl_rule' => false ],
                    //'other_income' => [ 'label' => 'Other Income', 'bold' => false, 'pl' => false ],                                        
                ],
                'total' => [ 'label' => 'Sales', 'hide_tr' => true, 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => false ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold',  'download_tr_hide' => true, 'parent_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                                          
                    'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false,  'r_type' => 'bl', 'bl_rule' => false ],      
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'minus_items' => [3], 'show_label' => 'COGS', 'main_row' => 'final_cogs',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Gross Profit',
                    'items' => [ 'Sales', 'Total Direct Expenses' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'employee_benefit_expenses' => [ 'label' => 'Employee Benefit Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],
                    'selling_general_and_administrative_expenses' => [ 'label' => 'Selling, General and Administrative Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                    
                    'other_expenses' => [ 'label' => 'Other Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                                        
                ],
                'total' => [ 'label' => 'Overheads', 'minus_items' => [], 'show_label' => 'Overheads', 'main_row' => 'overheads',  'bold' => false ]
            ],
            // 'final' => [
            //     [
            //         'bold' => true,
            //         'label' => 'Operating Profit',
            //         'items' => [ 'Gross Profit', 'Overheads' ],
            //         'operators' => [ '+', '-' ],
            //     ]                
            // ]
        ],
        [
            'data' => [
                'items' => [
                    'depreciation_amortization' => [ 'label' => 'Depreciation & Amortization',  'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Operating Profit',
                    'items' => [  'Gross Profit', 'Overheads', 'Depreciation & Amortization' ],
                    'operators' => [ '+', '-', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'interest_bank_charges' => [ 'label' => 'Interest & Bank Charges', 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit after Interest and before Tax',
                    'items' => [ 'Operating Profit', 'Interest & Bank Charges' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'other_non_operating_income' => [ 'label' => 'Other Non Operating Income',  'download_tr_hide' => true,  'sub_row' => 'non-operating-income', 'bold' => false, 'pl' => false, 'r_type' => 'pl', 'bl_rule' => false ],                    
                ],
                'total' => [ 'label' => 'Total Other Non Operating Income', 'show_label' => 'Other Income', 'main_row' => 'non-operating-income',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'other_non_operating_expenses' => [ 'label' => 'Other Non Operating Expenses',  'download_tr_hide' => true,  'sub_row' => 'non-operating-expense', 'bold' => false,  'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                
                ],
                'total' => [ 'label' => 'Total Non Operating Expenses', 'show_label' => 'Other Expenses', 'main_row' => 'non-operating-expense',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit before Exceptional/Extraordinary Items and Tax',
                    'items' => [ 'Profit after Interest and before Tax', 'Total Other Non Operating Income', 'Total Non Operating Expenses' ],
                    'operators' => [ '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'exceptional_extraordinary_income' => [ 'label' => 'Extraordinary Income', 'bold' => false, 'pl' => false, 'r_type' => 'pl', 'bl_rule' => false ],    
                    'exceptional_extraordinary_expense' => [ 'label' => 'Extraordinary Expense', 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],      
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'PBT',
                    'items' => [ 'Profit before Exceptional/Extraordinary Items and Tax', 'Extraordinary Income', 'Extraordinary Expense' ],
                    'operators' => [ '+', '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
             
                'items' => [
                    'current_tax' => [ 'label' => 'Current Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],
                    'deferred_tax' => [ 'label' => 'Deferred Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl', 'bl_rule' => false ],                    
                ],
                'total' => [ 'label' => 'Tax Paid', 'minus_items' => [], 'main_row' => 'tax-paid',  'bold' => false ]
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit after Tax',
                    'items' => [ 'PBT', 'Tax Paid' ],
                    'operators' => [ '+', '-' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Dividend Paid',
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => true,
                    'label' => 'Retained Profit',
                    'items' => [ 'Profit after Tax', 'Dividend Paid' ],
                    'operators' => [ '+', '-'],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                   
                    'cash_and_bank_balances' => [ 'label' => 'Cash and Bank Balances',  'sub_row' => 'cash-bank', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                                         
                ],
                'total' => [ 'label' => 'Cash & Bank', 'main_row' => 'cash-bank',  'bold' => false, 'bl_rule' => true ]                
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable',  'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],            
                    'closing_stock' => [ 'label' => 'Closing Stock',  'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                            
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'current_investments' => [ 'label' => 'Current Investments', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],
                    'other_current_assets' => [ 'label' => 'Other Current Assets', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'short_term_loans_advances' => [ 'label' => 'Short Term Loans & Advances', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true,  'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'branch' => [ 'label' => 'Branch', 'bold' => false,  'sub_row' => 'other-current-assets', 'download_tr_hide' => true,'pl' => false, 'r_type' => 'bl' ],                    
                    'suspense' => [ 'label' => 'Suspense', 'bold' => false, 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],    
                ],
                'total' => [ 'label' => 'Other Current Assets', 'main_row' => 'other-current-assets',  'bold' => false, 'bl_rule' => true ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Current Assets',
                    'items' => [ 'Cash & Bank', 'Accounts Receivable', 'Closing Stock', 'Other Current Assets' ],
                    'operators' => [ '+', '+', '+', '+' ],
                    'bl_rule' => true
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                   
                    'fixed_assets' => [ 'label' => 'Fixed Assets', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],
                ],
            ],
        ],
        [
            'data' => [
                'items' => [  
                    'non_current_investments' => [ 'label' => 'Non-current  Investments',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'long_term_loans_advances' => [ 'label' => 'Long Term Loans & Advances',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'deferred_tax_assets' => [ 'label' => 'Deferred Tax Assets',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'other_non_current_assets' => [ 'label' => 'Other Non-Current Assets',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],       
                ],
                'total' => [ 'label' => 'Other Non Current Assets', 'main_row' => 'other-none-current-assets',  'bold' => false, 'bl_rule' => true ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'label' => 'Non- Current Assets',
                    'items' => [ 'Fixed Assets', 'Other Non Current Assets' ],
                    'operators' => [ '+', '+' ],
                    'bl_rule' => true
                ],
                [
                    'bold' => true,
                    'label' => 'Total Assets',
                    'items' => [ 'Current Assets', 'Non- Current Assets' ],
                    'operators' => [ '+', '+'],
                    'bl_rule' => true
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                                       
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'secured_short_term_borrowings' => [ 'label' => 'Secured Short-term Borrowings',   'download_tr_hide' => true,  'sub_row' => 'bank-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                                      
                ],
                'total' => [ 'label' => 'Bank Loans - Current', 'show_label' => 'Bank Loans - Current', 'main_row' => 'bank-current',  'bold' => false, 'bl_rule' => true ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'short_term_provisions' => [ 'label' => 'Short Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false,   'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'current_deferred_tax_liabilities' => [ 'label' => 'Current Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'other_current_liabilities' => [ 'label' => 'Other Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'difference_in_opening_balance' => [ 'label' => 'Difference in Opening Balance', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],                             
                                      
                ],
                'total' => [ 'label' => 'Other Current Liabilities', 'main_row' => 'other-current-liability',  'bold' => false, 'bl_rule' => true ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'label' => 'Current Liabilities',
                    'items' => [ 'Accounts Payable','Bank Loans - Current', 'Other Current Liabilities' ],
                    'operators' => [ '+', '+', '+' ],
                    'bl_rule' => true
                ],            
            ]
        ],
        [
            'data' => [
                'items' => [   
                    'secured_long_term_borrowings' => [ 'label' => 'Secured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],
                    'unsecured_long_term_borrowings' => [ 'label' => 'Unsecured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],           
                ],
                'total' => [ 'label' => 'Bank Loans - Non Current', 'main_row' => 'bank-loan-non-current',  'bold' => false, 'bl_rule' => true ]                
            ],
        ],
        [
            'data' => [
                'items' => [   
                    'long_term_provisions' => [ 'label' => 'Long Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true],                    
                    'deferred_tax_liabilities' => [ 'label' => 'Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true],                    
                    'other_non_current_liabilities' => [ 'label' => 'Other Non-Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true],                    
                ],
                'total' => [ 'label' => 'Other Non Current Liabilities', 'main_row' => 'other-non-current-liability',  'bold' => false, 'bl_rule' => true ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Non-Current Liabilities',
                    'items' => [ 'Bank Loans - Non Current', 'Other Non Current Liabilities' ],
                    'operators' => [ '+', '+' ],
                    'bl_rule' => true
                ],
                [
                    'bold' => true,
                    'label' => 'Total Liabilities',
                    'items' => [ 'Current Liabilities', 'Non-Current Liabilities' ],
                    'operators' => [ '+', '+' ],
                    'bl_rule' => true
                ],            
            ]
        ],
        [
            'data' => [
                'items' => [
                    'equity_share_capital' => [ 'label' => 'Equity Share Capital', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false, 'r_type' => 'bl', 'bl_rule' => true ],
                    'other_equity' => [ 'label' => 'Other Equity', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'reserve_surplus' => [ 'label' => 'Reserve & Surplus', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl', 'bl_rule' => true ],                    
                    'current_year_profit' => [ 'label' => 'Current Year Profit', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl', 'bl_rule' => true ],                    
                ],
                'total' => [ 'label' => 'Equity', 'main_row' => 'total-equity', 'bold' => true, 'bl_rule' => true ]                
            ],
            'final' => [
                [
                    'bold' => false,
                    'label' => 'Validation',
                    'items' => [ 'Total Assets', 'Equity', 'Total Liabilities' ],
                    'operators' => [ '+', '-', '-' ]
                ]
            ]
        ],
    ],
    'cashflow_quality_report_list' => [
        [
            'data' => [
                'items' => [                   
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable',  'bold' => false, 'pl' => false, 'r_type' => 'bl' ],            
                    'closing_stock' => [ 'label' => 'Closing Stock',  'bold' => false, 'pl' => false, 'r_type' => 'bl' ],      
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                       
                ],
            ],
        ]
    ],
    'impact_of_change_report_list' => [
        [
            'data' => [
                'items' => [    
                    'cash_and_bank_balances' => [ 'label' => 'Cash and Bank Balances',  'sub_row' => 'cash-bank', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],    
                ],
                'total' => [ 'label' => 'Cash & Bank', 'main_row' => 'cash-bank', 'hide_tr' => true, 'bold' => false ]
            ],
        ],
        [
            'data' => [
                'items' => [
                    'employee_benefit_expenses' => [ 'label' => 'Employee Benefit Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'selling_general_and_administrative_expenses' => [ 'label' => 'Selling, General and Administrative Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'other_expenses' => [ 'label' => 'Other Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
                'total' => [ 'label' => 'Overheads', 'minus_items' => [], 'show_label' => 'Overheads', 'main_row' => 'overheads',  'bold' => false ]
            ]
        ],
        [
            'data' => [
                'items' => [                 
                    'secured_short_term_borrowings' => [ 'label' => 'Secured Short-term Borrowings',   'download_tr_hide' => true,  'sub_row' => 'bank-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                                      
                ],
                'total' => [ 'label' => 'Bank Loans - Current', 'show_label' => 'Bank Loans - Current', 'hide_tr' => true, 'main_row' => 'bank-current',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [   
                    'secured_long_term_borrowings' => [ 'label' => 'Secured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'unsecured_long_term_borrowings' => [ 'label' => 'Unsecured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],           
                ],
                'total' => [ 'label' => 'Bank Loans - Non Current', 'main_row' => 'bank-loan-non-current', 'hide_tr' => true, 'bold' => false ]                
            ],
        ],
        [
             'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'pl' => false, 'r_type' => 'pl' ],
                    //'other_income' => [ 'label' => 'Other Income', 'bold' => false, 'pl' => false ],                                        
                ],
                'total' => [ 'label' => 'Sales', 'hide_tr' => true, 'bold' => true ]                
            ],
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold',  'download_tr_hide' => true, 'parent_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                          
                    'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false,  'r_type' => 'bl' ],      
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'minus_items' => [3], 'show_label' => 'COGS', 'main_row' => 'final_cogs',  'bold' => false ]                
            ]    
        ],
        [
            'data' => [
                'items' => [                   
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable',  'bold' => false, 'pl' => false, 'r_type' => 'bl' ],            
                    'closing_stock' => [ 'label' => 'Closing Stock',  'bold' => false, 'pl' => false, 'r_type' => 'bl' ],    
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                            
                ],
            ],
        ],
    ],
    'financing_report_list' => [
        [
            'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'pl' => false,'download_tr_hide' => true,  'r_type' => 'pl' ],
                ],
                'total' => [ 'label' => 'Sales', 'hide_tr' => true, 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold',  'download_tr_hide' => true, 'parent_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],     
                    'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                 
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'minus_items' => [3], 'show_label' => 'COGS', 'hide_tr' => true, 'main_row' => 'final_cogs',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Gross Profit',
                    'hide_tr' => true,
                    'items' => [ 'Sales', 'Total Direct Expenses' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'employee_benefit_expenses' => [ 'label' => 'Employee Benefit Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'selling_general_and_administrative_expenses' => [ 'label' => 'Selling, General and Administrative Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'other_expenses' => [ 'label' => 'Other Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
                'total' => [ 'label' => 'Overheads', 'minus_items' => [], 'show_label' => 'Overheads', 'main_row' => 'overheads', 'hide_tr' => true,  'bold' => false ]
            ],
          
        ],
        [
            'data' => [
                'items' => [
                    'depreciation_amortization' => [ 'label' => 'Depreciation & Amortization', 'download_tr_hide' => true,  'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Operating Profit',
                    'items' => [  'Gross Profit', 'Overheads', 'Depreciation & Amortization' ],
                    'operators' => [ '+', '-', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'interest_bank_charges' => [ 'label' => 'Interest & Bank Charges', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Profit after Interest and before Tax',
                    'items' => [ 'Operating Profit', 'Interest & Bank Charges' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'other_non_operating_income' => [ 'label' => 'Other Non Operating Income',  'download_tr_hide' => true,  'sub_row' => 'non-operating-income', 'bold' => false, 'pl' => false, 'r_type' => 'pl' ],                    
                ],
                'total' => [ 'label' => 'Total Other Non Operating Income', 'show_label' => 'Other Income', 'hide_tr' => true, 'main_row' => 'non-operating-income',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'other_non_operating_expenses' => [ 'label' => 'Other Non Operating Expenses',  'download_tr_hide' => true,  'sub_row' => 'non-operating-expense', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                
                ],
                'total' => [ 'label' => 'Total Non Operating Expenses', 'show_label' => 'Other Expenses', 'hide_tr' => true, 'main_row' => 'non-operating-expense',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Profit before Exceptional/Extraordinary Items and Tax',
                    'items' => [ 'Profit after Interest and before Tax', 'Total Other Non Operating Income', 'Total Non Operating Expenses' ],
                    'operators' => [ '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'exceptional_extraordinary_income' => [ 'label' => 'Extraordinary Income', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'pl' ],    
                    'exceptional_extraordinary_expense' => [ 'label' => 'Extraordinary Expense', 'bold' => false, 'download_tr_hide' => true, 'pl' => true, 'r_type' => 'pl' ],      
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'PBT',
                    'hide_tr' => true,
                    'items' => [ 'Profit before Exceptional/Extraordinary Items and Tax', 'Extraordinary Income', 'Extraordinary Expense' ],
                    'operators' => [ '+', '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
             
                'items' => [
                    'current_tax' => [ 'label' => 'Current Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'deferred_tax' => [ 'label' => 'Deferred Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                ],
                'total' => [ 'label' => 'Tax Paid', 'minus_items' => [], 'hide_tr' => true, 'main_row' => 'tax-paid',  'bold' => false ]
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit after Tax',
                    'hide_tr' => true,
                    'items' => [ 'PBT', 'Tax Paid' ],
                    'operators' => [ '+', '-' ],
                ],
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Dividend Paid',
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Retained Profit',
                    'items' => [ 'Profit after Tax', 'Dividend Paid' ],
                    'operators' => [ '+', '-'],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                   
                    'cash_and_bank_balances' => [ 'label' => 'Cash and Bank Balances',  'sub_row' => 'cash-bank', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                         
                ],
                'total' => [ 'label' => 'Cash & Bank', 'main_row' => 'cash-bank', 'hide_tr' => true, 'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable',  'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],            
                    'closing_stock' => [ 'label' => 'Closing Stock',  'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],                            
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'current_investments' => [ 'label' => 'Current Investments', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'other_current_assets' => [ 'label' => 'Other Current Assets', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],  
                    'short_term_loans_advances' => [ 'label' => 'Short Term Loans & Advances', 'sub_row' => 'other-current-assets', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                      
                    'branch' => [ 'label' => 'Branch', 'bold' => false,  'sub_row' => 'other-current-assets', 'download_tr_hide' => true,'pl' => false, 'r_type' => 'bl' ],                    
                    'suspense' => [ 'label' => 'Suspense', 'bold' => false, 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],    
                ],
                'total' => [ 'label' => 'Other Current Assets', 'main_row' => 'other-current-assets', 'hide_tr' => true, 'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Current Assets',
                    'items' => [ 'Cash & Bank', 'Accounts Receivable', 'Closing Stock', 'Other Current Assets' ],
                    'operators' => [ '+', '+', '+', '+' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                   
                    'fixed_assets' => [ 'label' => 'Fixed Assets', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],
                ],
            ],
        ],
        [
            'data' => [
                'items' => [  
                    'non_current_investments' => [ 'label' => 'Non-current  Investments',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'long_term_loans_advances' => [ 'label' => 'Long Term Loans & Advances',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'deferred_tax_assets' => [ 'label' => 'Deferred Tax Assets',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'other_non_current_assets' => [ 'label' => 'Other Non-Current Assets',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],       
                ],
                'total' => [ 'label' => 'Other Non Current Assets', 'main_row' => 'other-none-current-assets', 'hide_tr' => true,  'bold' => false ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'label' => 'Non- Current Assets',
                    'hide_tr' => true,
                    'items' => [ 'Fixed Assets', 'Other Non Current Assets' ],
                    'operators' => [ '+', '+' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Total Assets',
                    'hide_tr' => true,
                    'items' => [ 'Current Assets', 'Non- Current Assets' ],
                    'operators' => [ '+', '+'],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                                       
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],                    
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'secured_short_term_borrowings' => [ 'label' => 'Secured Short-term Borrowings',   'download_tr_hide' => true,  'sub_row' => 'bank-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                                      
                ],
                'total' => [ 'label' => 'Bank Loans - Current', 'show_label' => 'Bank Loans - Current', 'hide_tr' => true, 'main_row' => 'bank-current',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'short_term_provisions' => [ 'label' => 'Short Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false,   'pl' => false, 'r_type' => 'bl' ],                    
                    'current_deferred_tax_liabilities' => [ 'label' => 'Current Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'other_current_liabilities' => [ 'label' => 'Other Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'difference_in_opening_balance' => [ 'label' => 'Difference in Opening Balance', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                             
                                      
                ],
                'total' => [ 'label' => 'Other Current Liabilities', 'main_row' => 'other-current-liability', 'hide_tr' => true,  'bold' => false ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'label' => 'Current Liabilities',
                    'hide_tr' => true,
                    'items' => [ 'Accounts Payable','Bank Loans - Current', 'Other Current Liabilities' ],
                    'operators' => [ '+', '+', '+' ],
                ],            
            ]
        ],
        [
            'data' => [
                'items' => [   
                    'secured_long_term_borrowings' => [ 'label' => 'Secured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'unsecured_long_term_borrowings' => [ 'label' => 'Unsecured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],           
                ],
                'total' => [ 'label' => 'Bank Loans - Non Current', 'main_row' => 'bank-loan-non-current', 'hide_tr' => true, 'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [   
                    'long_term_provisions' => [ 'label' => 'Long Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl'],                    
                    'deferred_tax_liabilities' => [ 'label' => 'Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl'],                    
                    'other_non_current_liabilities' => [ 'label' => 'Other Non-Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl'],                    
                ],
                'total' => [ 'label' => 'Other Non Current Liabilities', 'main_row' => 'other-non-current-liability', 'hide_tr' => true,  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Non-Current Liabilities',
                    'hide_tr' => true,
                    'items' => [ 'Bank Loans - Non Current', 'Other Non Current Liabilities' ],
                    'operators' => [ '+', '+' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Total Liabilities',
                    'hide_tr' => true,
                    'items' => [ 'Current Liabilities', 'Non-Current Liabilities' ],
                    'operators' => [ '+', '+' ],
                ],            
            ]
        ],
        [
            'data' => [
                'items' => [
                    'equity_share_capital' => [ 'label' => 'Equity Share Capital', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'other_equity' => [ 'label' => 'Other Equity', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl' ],                    
                    'reserve_surplus' => [ 'label' => 'Reserve & Surplus', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl' ],                    
                    'current_year_profit' => [ 'label' => 'Current Year Profit', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl' ],                    
                ],
                'total' => [ 'label' => 'Equity', 'main_row' => 'total-equity', 'hide_tr' => true, 'bold' => true ]                
            ],
            'final' => [
                [
                    'bold' => false,
                    'hide_tr' => true,
                    'label' => 'Validation',
                    'items' => [ 'Total Assets', 'Equity', 'Total Liabilities' ],
                    'operators' => [ '+', '-', '-' ]
                ]
            ]
        ],
    ],
    'capex_report_list' => [
        [
            'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'pl' => false, 'download_tr_hide' => true, 'r_type' => 'pl' ],
                ],
                'total' => [ 'label' => 'Sales', 'hide_tr' => true, 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold',  'download_tr_hide' => true, 'parent_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],   
                    'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                   
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'minus_items' => [3], 'show_label' => 'COGS',  'hide_tr' => true, 'main_row' => 'final_cogs',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Gross Profit',
                    'hide_tr' => true, 
                    'items' => [ 'Sales', 'Total Direct Expenses' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'employee_benefit_expenses' => [ 'label' => 'Employee Benefit Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'selling_general_and_administrative_expenses' => [ 'label' => 'Selling, General and Administrative Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'other_expenses' => [ 'label' => 'Other Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
                'total' => [ 'label' => 'Overheads', 'minus_items' => [], 'show_label' => 'Overheads', 'main_row' => 'overheads',   'hide_tr' => true,  'bold' => false ]
            ],        
        ],
        [
            'data' => [
                'items' => [
                    'depreciation_amortization' => [ 'label' => 'Depreciation & Amortization', 'download_tr_hide' => true,  'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Operating Profit',
                     'hide_tr' => true, 
                    'items' => [  'Gross Profit', 'Overheads', 'Depreciation & Amortization' ],
                    'operators' => [ '+', '-', '-' ],
                ]                
            ]
        ],

        [
            'data' => [
                'items' => [
                    'interest_bank_charges' => [ 'label' => 'Interest & Bank Charges', 'bold' => false, 'download_tr_hide' => true, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Profit after Interest and before Tax',
                    'items' => [ 'Operating Profit', 'Interest & Bank Charges' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'other_non_operating_income' => [ 'label' => 'Other Non Operating Income',  'download_tr_hide' => true,  'sub_row' => 'non-operating-income', 'bold' => false, 'pl' => false, 'r_type' => 'pl' ],                    
                ],
                'total' => [ 'label' => 'Total Other Non Operating Income', 'hide_tr' => true, 'show_label' => 'Other Income', 'main_row' => 'non-operating-income',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'other_non_operating_expenses' => [ 'label' => 'Other Non Operating Expenses',  'download_tr_hide' => true,  'sub_row' => 'non-operating-expense', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                
                ],
                'total' => [ 'label' => 'Total Non Operating Expenses', 'hide_tr' => true, 'show_label' => 'Other Expenses', 'main_row' => 'non-operating-expense',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Profit before Exceptional/Extraordinary Items and Tax',
                    'items' => [ 'Profit after Interest and before Tax', 'Total Other Non Operating Income', 'Total Non Operating Expenses' ],
                    'operators' => [ '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'exceptional_extraordinary_income' => [ 'label' => 'Extraordinary Income', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'pl' ],    
                    'exceptional_extraordinary_expense' => [ 'label' => 'Extraordinary Expense', 'bold' => false, 'download_tr_hide' => true, 'pl' => true, 'r_type' => 'pl' ],      
                ],
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'PBT',
                    'hide_tr' => true,
                    'items' => [ 'Profit before Exceptional/Extraordinary Items and Tax', 'Extraordinary Income', 'Extraordinary Expense' ],
                    'operators' => [ '+', '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
             
                'items' => [
                    'current_tax' => [ 'label' => 'Current Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'deferred_tax' => [ 'label' => 'Deferred Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                ],
                'total' => [ 'label' => 'Tax Paid', 'minus_items' => [], 'main_row' => 'tax-paid', 'hide_tr' => true,  'bold' => false ]
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Profit after Tax',
                    'hide_tr' => true,
                    'items' => [ 'PBT', 'Tax Paid' ],
                    'operators' => [ '+', '-' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Dividend Paid',
                    'items' => [],
                    'direct' => true,
                    'hide_tr' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => true,
                    'label' => 'Retained Profit',
                    'hide_tr' => true,
                    'items' => [ 'Profit after Tax', 'Dividend Paid' ],
                    'operators' => [ '+', '-'],
                ]                
            ]
        ],
        
        [
            'data' => [
                'items' => [                   
                    'cash_and_bank_balances' => [ 'label' => 'Cash and Bank Balances',  'sub_row' => 'cash-bank', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                         
                ],
                'total' => [ 'label' => 'Cash & Bank', 'main_row' => 'cash-bank',  'hide_tr' => true,  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable', 'download_tr_hide' => true,  'bold' => false, 'pl' => false, 'r_type' => 'bl' ],            
                    'closing_stock' => [ 'label' => 'Closing Stock',  'bold' => false,  'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],                            
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'current_investments' => [ 'label' => 'Current Investments', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'other_current_assets' => [ 'label' => 'Other Current Assets', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'short_term_loans_advances' => [ 'label' => 'Short Term Loans & Advances', 'sub_row' => 'other-current-assets', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'branch' => [ 'label' => 'Branch', 'bold' => false,  'sub_row' => 'other-current-assets', 'download_tr_hide' => true,'pl' => false, 'r_type' => 'bl' ],                    
                    'suspense' => [ 'label' => 'Suspense', 'bold' => false, 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],    
                ],
                'total' => [ 'label' => 'Other Current Assets', 'main_row' => 'other-current-assets',  'hide_tr' => true,   'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                     'hide_tr' => true, 
                    'label' => 'Current Assets',
                    'items' => [ 'Cash & Bank', 'Accounts Receivable', 'Closing Stock', 'Other Current Assets' ],
                    'operators' => [ '+', '+', '+', '+' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                   
                    'fixed_assets' => [ 'label' => 'Fixed Assets', 'bold' => false,  'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],
                ],
            ],
        ],
        [
            'data' => [
                'items' => [  
                    'non_current_investments' => [ 'label' => 'Non-current  Investments',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'long_term_loans_advances' => [ 'label' => 'Long Term Loans & Advances',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'deferred_tax_assets' => [ 'label' => 'Deferred Tax Assets',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'other_non_current_assets' => [ 'label' => 'Other Non-Current Assets',  'sub_row' => 'other-none-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],       
                ],
                'total' => [ 'label' => 'Other Non Current Assets', 'main_row' => 'other-none-current-assets',  'hide_tr' => true,   'bold' => false ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'label' => 'Non- Current Assets',
                     'hide_tr' => true, 
                    'items' => [ 'Fixed Assets', 'Other Non Current Assets' ],
                    'operators' => [ '+', '+' ],
                ],
                [
                    'bold' => true,
                    'label' => 'Total Assets',
                     'hide_tr' => true, 
                    'items' => [ 'Current Assets', 'Non- Current Assets' ],
                    'operators' => [ '+', '+'],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                                       
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'pl' => false, 'download_tr_hide' => true, 'r_type' => 'bl' ],                    
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'secured_short_term_borrowings' => [ 'label' => 'Secured Short-term Borrowings',   'download_tr_hide' => true,  'sub_row' => 'bank-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                                      
                ],
                'total' => [ 'label' => 'Bank Loans - Current', 'show_label' => 'Bank Loans - Current', 'main_row' => 'bank-current',  'hide_tr' => true,   'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'short_term_provisions' => [ 'label' => 'Short Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false,   'pl' => false, 'r_type' => 'bl' ],                    
                    'current_deferred_tax_liabilities' => [ 'label' => 'Current Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'other_current_liabilities' => [ 'label' => 'Other Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'difference_in_opening_balance' => [ 'label' => 'Difference in Opening Balance', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                             
                                      
                ],
                'total' => [ 'label' => 'Other Current Liabilities', 'main_row' => 'other-current-liability',  'hide_tr' => true,  'bold' => false ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'label' => 'Current Liabilities',
                     'hide_tr' => true, 
                    'items' => [ 'Accounts Payable','Bank Loans - Current', 'Other Current Liabilities' ],
                    'operators' => [ '+', '+', '+' ],
                ],            
            ]
        ],
        [
            'data' => [
                'items' => [   
                    'secured_long_term_borrowings' => [ 'label' => 'Secured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'unsecured_long_term_borrowings' => [ 'label' => 'Unsecured Long-term Borrowings', 'download_tr_hide' => true,  'sub_row' => 'bank-loan-non-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],           
                ],
                'total' => [ 'label' => 'Bank Loans - Non Current', 'main_row' => 'bank-loan-non-current',  'hide_tr' => true,  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [   
                    'long_term_provisions' => [ 'label' => 'Long Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl'],                    
                    'deferred_tax_liabilities' => [ 'label' => 'Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl'],                    
                    'other_non_current_liabilities' => [ 'label' => 'Other Non-Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-non-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl'],                    
                ],
                'total' => [ 'label' => 'Other Non Current Liabilities', 'main_row' => 'other-non-current-liability',  'hide_tr' => true,   'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'label' => 'Non-Current Liabilities',
                     'hide_tr' => true, 
                    'items' => [ 'Bank Loans - Non Current', 'Other Non Current Liabilities' ],
                    'operators' => [ '+', '+' ],
                ],
                       
            ]
        ],
        [
            'data' => [
                'items' => [
                    'equity_share_capital' => [ 'label' => 'Equity Share Capital', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'other_equity' => [ 'label' => 'Other Equity', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl' ],                    
                    'reserve_surplus' => [ 'label' => 'Reserve & Surplus', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl' ],                    
                    'current_year_profit' => [ 'label' => 'Current Year Profit', 'download_tr_hide' => true,  'sub_row' => 'total-equity', 'bold' => false, 'pl' => false , 'r_type' => 'bl' ],                    
                ],
                'total' => [ 'label' => 'Equity', 'main_row' => 'total-equity',  'hide_tr' => true,  'bold' => true ]                
            ],
            
        ],
    ],

    'cash_management_report_list' => [
        [
            'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'pl' => false,  'download_tr_hide' => true, 'r_type' => 'pl' ],
                ],
                'total' => [ 'label' => 'Sales', 'hide_tr' => true, 'bold' => true ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold',  'download_tr_hide' => true, 'parent_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],     
                    'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                 
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'hide_tr' => true, 'minus_items' => [3], 'show_label' => 'COGS', 'main_row' => 'final_cogs',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Gross Profit',
                    'items' => [ 'Sales', 'Total Direct Expenses' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [                   
                    'cash_and_bank_balances' => [ 'label' => 'Cash and Bank Balances',  'sub_row' => 'cash-bank', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                         
                ],
                'total' => [ 'label' => 'Cash & Bank', 'main_row' => 'cash-bank', 'hide_tr' => true,  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'accounts_receivable' => [ 'label' => 'Accounts Receivable',  'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],            
                    'closing_stock' => [ 'label' => 'Closing Stock',  'bold' => false, 'pl' => false, 'download_tr_hide' => true, 'r_type' => 'bl' ],                            
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                   
                    'current_investments' => [ 'label' => 'Current Investments', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],
                    'other_current_assets' => [ 'label' => 'Other Current Assets', 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],   
                    'short_term_loans_advances' => [ 'label' => 'Short Term Loans & Advances', 'sub_row' => 'other-current-assets', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                     
                    'branch' => [ 'label' => 'Branch', 'bold' => false,  'sub_row' => 'other-current-assets', 'download_tr_hide' => true,'pl' => false, 'r_type' => 'bl' ],                    
                    'suspense' => [ 'label' => 'Suspense', 'bold' => false, 'sub_row' => 'other-current-assets', 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],    
                ],
                'total' => [ 'label' => 'Other Current Assets', 'main_row' => 'other-current-assets', 'hide_tr' => true,  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Current Assets',
                    'items' => [ 'Cash & Bank', 'Accounts Receivable', 'Closing Stock', 'Other Current Assets' ],
                    'operators' => [ '+', '+', '+', '+' ],
                ]                
            ]
        ],
       
        [
            'data' => [
                'items' => [                                       
                    'accounts_payable' => [ 'label' => 'Accounts Payable', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'bl' ],                    
                ],
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'secured_short_term_borrowings' => [ 'label' => 'Secured Short-term Borrowings',   'download_tr_hide' => true,  'sub_row' => 'bank-current', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                                                        
                ],
                'total' => [ 'label' => 'Bank Loans - Current', 'show_label' => 'Bank Loans - Current', 'hide_tr' => true, 'main_row' => 'bank-current',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'short_term_provisions' => [ 'label' => 'Short Term Provisions', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false,   'pl' => false, 'r_type' => 'bl' ],                    
                    'current_deferred_tax_liabilities' => [ 'label' => 'Current Deferred Tax Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'other_current_liabilities' => [ 'label' => 'Other Current Liabilities', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                    
                    'difference_in_opening_balance' => [ 'label' => 'Difference in Opening Balance', 'download_tr_hide' => true,  'sub_row' => 'other-current-liability', 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                             
                                      
                ],
                'total' => [ 'label' => 'Other Current Liabilities', 'main_row' => 'other-current-liability', 'hide_tr' => true,  'bold' => false ]                
            ],

            'final' => [
                [
                    'bold' => true,
                    'hide_tr' => true,
                    'label' => 'Current Liabilities',
                    'items' => [ 'Accounts Payable','Bank Loans - Current', 'Other Current Liabilities' ],
                    'operators' => [ '+', '+', '+' ],
                ],            
            ]
        ],     
    ],
    'power_profit_report_list' => [
        [
            'data' => [
                'items' => [
                    'sales' => [ 'label' => 'Sales', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'pl' ],
                ],
                'total' => [ 'label' => 'Revenue',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => false,
                    'label' => 'Revenue Growth %',
                    'items' => [],
                    'decimal' => true,
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
            ]
        ],
        [
            'data' => [
                'items' => [
                    'opening_stock' => [ 'label' => 'Opening Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                      
                    'cost_of_goods_sold' => [ 'label' => 'COGS', 'new_label' => 'Cost of Goods Sold',  'download_tr_hide' => true, 'parent_tr_hide' => true,  'sub_row' => 'final_cogs', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'direct_manufacturing_expenses' => [ 'label' => 'Direct/Manufacturing Expenses', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],       
                     'closing_stock' => [ 'label' => 'Closing Stock', 'sub_row' => 'final_cogs', 'download_tr_hide' => true, 'bold' => false, 'pl' => false, 'r_type' => 'bl' ],                   
                ],
                'total' => [ 'label' => 'Total Direct Expenses', 'minus_items' => [3], 'show_label' => 'COGS', 'main_row' => 'final_cogs',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => false,
                    'label' => 'COGS Growth %',
                    'items' => [],
                    'decimal' => true,
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => false,
                    'label' => 'Gross Margin',
                    'items' => [ 'Revenue', 'Total Direct Expenses' ],
                    'operators' => [ '+', '-' ],
                ],
                [
                    'bold' => false,
                    'label' => 'Gross Margin %',
                    'decimal' => true,
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],               
            ]
        ],
        [
            'data' => [
                'items' => [
                    'employee_benefit_expenses' => [ 'label' => 'Employee Benefit Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'selling_general_and_administrative_expenses' => [ 'label' => 'Selling, General and Administrative Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                    'other_expenses' => [ 'label' => 'Other Expenses', 'sub_row' => 'overheads', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                                        
                ],
                'total' => [ 'label' => 'Overheads', 'minus_items' => [], 'show_label' => 'Overheads', 'main_row' => 'overheads',  'hide_tr' => true, 'bold' => false ]
            ],
        ],
        [
            'data' => [
                'items' => [
                    'depreciation_amortization' => [ 'label' => 'Depreciation & Amortization',  'bold' => false, 'pl' => true,  'download_tr_hide' => true, 'r_type' => 'pl' ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => false,
                    'label' => 'Overheads including depreciation',
                    'items' => [  'Overheads', 'Depreciation & Amortization' ],
                    'operators' => [ '+', '+' ],
                ],
                [
                    'bold' => false,
                    'label' => 'Overheads %',
                    'decimal' => true,
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => false,
                    'label' => 'Overheads Growth %',
                    'items' => [],
                    'direct' => true,
                    'decimal' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => false,
                    'label' => 'Operating Profit',
                    'items' => [  'Gross Margin', 'Overheads including depreciation' ],
                    'operators' => [ '+', '-', ],
                ],
                [
                    'bold' => false,
                    'label' => 'Operating Profit %',
                    'decimal' => true,
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => false,
                    'label' => 'EBITDA',
                    'items' => [  'Operating Profit', 'Depreciation & Amortization' ],
                    'operators' => [ '+', '+', ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'interest_bank_charges' => [ 'label' => 'Interest & Bank Charges', 'bold' => false, 'download_tr_hide' => true,  'pl' => true, 'r_type' => 'pl' ],                                        
                ],
            ],
            'final' => [
                [
                    'bold' => false,
                    'hide_tr' => true,
                    'label' => 'Profit after Interest and before Tax',
                    'items' => [ 'Operating Profit', 'Interest & Bank Charges' ],
                    'operators' => [ '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'other_non_operating_income' => [ 'label' => 'Other Non Operating Income',  'download_tr_hide' => true,  'sub_row' => 'non-operating-income', 'bold' => false, 'pl' => false, 'r_type' => 'pl' ],                    
                ],
                'total' => [ 'label' => 'Total Other Non Operating Income', 'show_label' => 'Other Income', 'hide_tr' => true,  'main_row' => 'non-operating-income',  'bold' => false ]                
            ],
        ],
        [
            'data' => [
                'items' => [                 
                    'other_non_operating_expenses' => [ 'label' => 'Other Non Operating Expenses',  'download_tr_hide' => true,  'sub_row' => 'non-operating-expense', 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                
                ],
                'total' => [ 'label' => 'Total Non Operating Expenses', 'show_label' => 'Other Expenses', 'hide_tr' => true,  'main_row' => 'non-operating-expense',  'bold' => false ]                
            ],
            'final' => [
                [
                    'bold' => false,
                    'hide_tr' => true,
                    'label' => 'Profit before Exceptional/Extraordinary Items and Tax',
                    'items' => [ 'Profit after Interest and before Tax', 'Total Other Non Operating Income', 'Total Non Operating Expenses' ],
                    'operators' => [ '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'exceptional_extraordinary_income' => [ 'label' => 'Extraordinary Income', 'bold' => false, 'download_tr_hide' => true, 'pl' => false, 'r_type' => 'pl' ],    
                    'exceptional_extraordinary_expense' => [ 'label' => 'Extraordinary Expense', 'bold' => false, 'download_tr_hide' => true, 'pl' => true, 'r_type' => 'pl' ],      
                ],
            ],
            'final' => [
                [
                    'bold' => false,
                    'label' => 'PBT',
                    'hide_tr' => true,
                    'items' => [ 'Profit before Exceptional/Extraordinary Items and Tax', 'Extraordinary Income', 'Extraordinary Expense' ],
                    'operators' => [ '+', '+', '+', '-' ],
                ]                
            ]
        ],
        [
            'data' => [
                'items' => [
                    'current_tax' => [ 'label' => 'Current Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],
                    'deferred_tax' => [ 'label' => 'Deferred Tax', 'sub_row' => 'tax-paid', 'download_tr_hide' => true, 'bold' => false, 'pl' => true, 'r_type' => 'pl' ],                    
                ],
                'total' => [ 'label' => 'Tax Paid', 'minus_items' => [], 'main_row' => 'tax-paid', 'hide_tr' => true, 'bold' => false ]
            ],
            'final' => [
                [
                    'bold' => false,
                    'label' => 'Net Profit',
                    'items' => [ 'PBT', 'Tax Paid' ],
                    'operators' => [ '+', '-' ],
                ],
                [
                    'bold' => false,
                    'label' => 'Net Profit %',
                    'items' => [],
                    'direct' => true,
                    'decimal' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => false,
                    'label' => 'Dividend Paid',
                    'items' => [],
                    'direct' => true,
                    'hide_tr' => true,
                    'value' => 0,
                    'operators' => [],
                ],
                [
                    'bold' => false,
                    'label' => 'Retained Profit',
                    'items' => [ 'Net Profit', 'Dividend Paid' ],
                    'operators' => [ '+', '-'],
                ],
                [
                    'bold' => false,
                    'label' => 'Interest Cover',
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ], 
                [
                    'bold' => false,
                    'label' => 'Break Even Sales',
                    'items' => [],
                    'direct' => true,
                    'value' => 0,
                    'operators' => [],
                ],                
            ]
        ],
    ]
];