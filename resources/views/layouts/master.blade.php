<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Financial Risk Management System</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/bootstrap.min.css')}}">
  
  <link rel="stylesheet" href="{{asset('assets/css/lib/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/lib/dropzone/dropzone.min.css')}}">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/apexcharts.css')}}">
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/dataTables.min.css')}}">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/editor-katex.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/lib/editor.atom-one-dark.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/lib/editor.quill.snow.css')}}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/flatpickr.min.css')}}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/full-calendar.css')}}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/jquery-jvectormap-2.0.5.css')}}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/magnific-popup.css')}}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/slick.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/lib/jquery-ui.css')}}">
  
  <link rel="stylesheet" href="{{asset('assets/css/lib/select2.min.css')}}">
  
  <link rel="stylesheet" href="{{asset('assets/css/lib/sweetalert2.min.css')}}">
  <!-- prism css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/prism.css')}}">
  <!-- file upload css -->
  <link rel="stylesheet" href="{{asset('assets/css/lib/file-upload.css')}}">
  
  <link rel="stylesheet" href="{{asset('assets/css/lib/audioplayer.css')}}">
  <!-- main css -->
  <link rel="stylesheet" href="{{asset('assets/css/style.css?ver=1.0.1')}}">
  
   @yield('css')
  <script type="text/javascript">
    var site_url = "<?php echo URL::to('');?>";

    @auth
    var user_id = "{{auth()->user()->id}}";
    @endauth
  </script>
</head>
  <body>
<aside class="sidebar">
  <button type="button" class="sidebar-close-btn">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="/" class="sidebar-logo">
      <img src="{{asset('assets/images/logo.png')}}" alt="site logo" class="light-logo">
      <img src="{{asset('assets/images/logo-light.png')}}" alt="site logo" class="dark-logo">
      <img src="{{asset('assets/images/logo-icon.png')}}" alt="site logo" class="logo-icon">
    </a>
  </div>

  <?php 
  $menu_list = array(
    'menu_0' => array(
      'link' => route('home.index'),
      'key' => 'dashboard',
      'permissions' => 'home.index',
      'label' => 'Dashboard',
      'icon' => '<iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>',
      'top_menu' => true,
      'sub_menu' => array(),
    ),
    'menu_11' => array(
      'link' => "javascript:void(0)",
      'label' => 'Branch',
      'key' => 'branch',
      'permissions' => 'menu.branch',
      'icon' => '<i class="ri-git-branch-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('branch'),
          'label' => 'Branch List',
          'key' => 'branch-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'branch.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('branch/create'),
          'label' => 'Add Branch',
          'key' => 'branch-add',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'branch.create',
          'sub_menu' => array()
        ),
        array(
          'link' => url('branch/import-export'),
          'label' => 'Import/Export',
          'key' => 'branch-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'branch.import-export',
          'sub_menu' => array()
        )        
      ),
    ),
    'menu_12' => array(
      'link' => "javascript:void(0)",
      'label' => 'Customer',
      'key' => 'customer',
      'permissions' => 'menu.customer',
      'icon' => '<i class="ri-user-2-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('customer'),
          'label' => 'Customer List',
          'key' => 'customer-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'customer.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('customer/create'),
          'label' => 'Add Customer',
          'key' => 'customer-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'customer.create',
          'sub_menu' => array()
        ),    
        array(
          'link' => url('customer/import-export'),
          'label' => 'Import/Export',
          'key' => 'customer-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'customer.import-export',
          'sub_menu' => array()
        ) 
      ),
    ),
    'menu_13' => array(
      'link' => "javascript:void(0)",
      'label' => 'Vendor',
      'key' => 'vendor',
      'permissions' => 'menu.vendor',
      'icon' => '<i class="ri-store-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('vendors'),
          'label' => 'Vendor List',
          'key' => 'vendor-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'vendor.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('vendors/create'),
          'label' => 'Add Vendor',
          'key' => 'vendor-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'vendor.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('vendors/import-export'),
          'label' => 'Import/Export',
          'key' => 'vendor-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'vendor.import-export',
          'sub_menu' => array()
        ) 
      ),
    ),
    'menu_14' => array(
      'link' => "javascript:void(0)",
      'label' => 'Inventory',
      'key' => 'inventory',
      'permissions' => 'menu.inventory',
      'icon' => '<i class="ri-contacts-book-upload-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
          array(
              'link' => url('inventory/dashboard'),
              'key' => 'inventory-dashboard',
              'permissions' => 'inventory-dashboard',
              'label' => 'Dashboard',
              'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
              'top_menu' => true,
              'sub_menu' => array(),
         ),
        array(
          'link' => url('inventory'),
          'label' => 'Inventory List',
          'key' => 'inventory-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'inventory.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('inventory/create'),
          'label' => 'Add Inventory',
          'key' => 'inventory-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'inventory.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('inventory/import-export'),
          'label' => 'Import/Export',
          'key' => 'inventory-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'inventory.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_15' => array(
      'link' => "javascript:void(0)",
      'label' => 'Sales Representative',
      'key' => 'salesrepresentative',
      'permissions' => 'menu.salesrepresentative',
      'icon' => '<i class="ri-user-shared-2-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('salesrepresentative'),
          'label' => 'Sales Representative List',
          'key' => 'salesrepresentative-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'salesrepresentative.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('salesrepresentative/create'),
          'label' => 'Add Sales Representative',
          'key' => 'salesrepresentative-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'salesrepresentative.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('salesrepresentative/import-export'),
          'label' => 'Import/Export',
          'key' => 'salesrepresentative-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'salesrepresentative.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_16' => array(
      'link' => "javascript:void(0)",
      'label' => 'Cost Center',
      'key' => 'costcenter',
      'permissions' => 'menu.costcenter',
      'icon' => '<i class="ri-money-dollar-circle-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('costcenter'),
          'label' => 'Cost Center List',
          'key' => 'costcenter-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'costcenter.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('costcenter/create'),
          'label' => 'Add Cost Center',
          'key' => 'costcenter-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'costcenter.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('costcenter/import-export'),
          'label' => 'Import/Export',
          'key' => 'costcenter-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'costcenter.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_1' => array(
      'link' => "javascript:void(0)",
      'label' => 'Accounting',
      'key' => 'accounting',
      'permissions' => 'menu.accounting',
      'icon' => '<i class="ri-funds-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('chartaccount'),
          'label' => 'Chart of Accounts',
          'key' => 'chart-of-account-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'chartaccount.list',
          'sub_menu' => array()
        ),
        array(
          'link' => url('chartaccount/create'),
          'label' => 'Add Chart of Accounts',
          'key' => 'chartaccount-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'chartaccount.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('chartaccount/import-export'),
          'label' => 'Import/Export',
          'key' => 'chartaccount-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'chartaccount.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_222' => array(
      'link' => "javascript:void(0)",
      'label' => 'KPI Record',
      'key' => 'kpi_record',
      'permissions' => 'menu.kpi-records',
      'icon' => '<i class="ri-scales-3-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('kpi-records'),
          'label' => 'KPI Records',
          'key' => 'kpi-records-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'kpi-records.list',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_111' => array(
      'link' => "javascript:void(0)",
      'label' => 'Trial Balance',
      'key' => 'trial_balance',
      'permissions' => 'menu.trialbalance',
      'icon' => '<i class="ri-scales-3-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('trialbalance'),
          'label' => 'Trial Balances',
          'key' => 'chart-of-account-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'trialbalance.list',
          'sub_menu' => array()
        ),
        array(
          'link' => url('trialbalance/import-export'),
          'label' => 'Import/Export',
          'key' => 'trialbalance-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'trialbalance.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_23' => array(
      'link' => "javascript:void(0)",
      'label' => 'Purchase Register',
      'key' => 'purchaseregister',
      'permissions' => 'menu.purchaseregister',
      'icon' => '<i class="ri-briefcase-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
           array(
              'link' => url('purchaseregister/dashboard'),
              'key' => 'purchaseregister-dashboard',
              'permissions' => 'purchaseregister-dashboard',
              'label' => 'Dashboard',
              'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
              'top_menu' => true,
              'sub_menu' => array(),
         ),
        array(
          'link' => url('purchaseregister'),
          'label' => 'Purchase Register List',
          'key' => 'purchase-register-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'purchaseregister.list',
          'sub_menu' => array()
        ),
        array(
          'link' => url('purchaseregister/create'),
          'label' => 'Add Purchase Register',
          'key' => 'purchaseregister-create',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'purchaseregister.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('purchaseregister/import-export'),
          'label' => 'Import/Export',
          'key' => 'purchaseregister-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'purchaseregister.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_33' => array(
      'link' => "javascript:void(0)",
      'label' => 'Sales Register',
      'key' => 'salesregister',
      'permissions' => 'menu.salesregister',
      'icon' => '<i class="ri-cash-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
         array(
              'link' => url('salesregister/dashboard'),
              'key' => 'salesregister-dashboard',
              'permissions' => 'salesregister-dashboard',
              'label' => 'Dashboard',
              'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
              'top_menu' => true,
              'sub_menu' => array(),
         ),
        array(
          'link' => url('salesregister'),
          'label' => 'Sales Register List',
          'key' => 'sales-register-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'salesregister.list',
          'sub_menu' => array()
        ),
        array(
          'link' => url('salesregister/create'),
          'label' => 'Add Sales Register',
          'key' => 'salesregister-create',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'salesregister.create',
          'sub_menu' => array()
        ),  
        array(
          'link' => url('salesregister/import-export'),
          'label' => 'Import/Export',
          'key' => 'salesregister-import-export',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'salesregister.import-export',
          'sub_menu' => array()
        )
      ),
    ),
    'menu_1163' => array(
      'link' => "javascript:void(0)",
      'label' => 'Reports',
      'key' => 'reports',
      'permissions' => 'menu.reports',
      'icon' => '<i class="ri-file-chart-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('reports/ai-reports'),
          'label' => 'AI Report',
          'key' => 'ai-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.ai-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/bl-reports'),
          'label' => 'Balance Sheet Report',
          'key' => 'bl-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.bl-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/pl-reports'),
          'label' => 'Profit & Loss Report',
          'key' => 'pl-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.pl-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/fns-reports'),
          'label' => 'Financial Summary Report',
          'key' => 'fns-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.fns-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/cashflow-reports'),
          'label' => 'Cash Flow Report',
          'key' => 'cashflow-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.cashflow-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/profit-power-reports'),
          'label' => 'Profit Power Report',
          'key' => 'profit-power-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.profit-power-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/cash-mng-reports'),
          'label' => 'Cash Management Report',
          'key' => 'cash-mng-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.cash-mng-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/capex-reports'),
          'label' => 'Capex Report',
          'key' => 'capex-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.capex-report',
          'sub_menu' => array()
        ),
         array(
          'link' => url('reports/financing-reports'),
          'label' => 'Financing Report',
          'key' => 'financing-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.financing-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/impact-of-change-reports'),
          'label' => 'Impact Of Change Report',
          'key' => 'impact-of-change-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.impact-of-change-report',
          'sub_menu' => array()
        ),
        
        array(
          'link' => url('reports/bs-category-reports'),
          'label' => 'Business Health Report',
          'key' => 'bs-category-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.bs-category-report',
          'sub_menu' => array()
        ),
        array(
          'link' => url('reports/cashflow-quality-reports'),
          'label' => 'CashFlow Quality Report',
          'key' => 'cashflow-quality-reports',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'reports.cashflow-quality-report',
          'sub_menu' => array()
        ),
        
        
      ),
    ),
    'menu_2' => array(
      'link' => "javascript:void(0)",
      'label' => 'Permission',
      'key' => 'permissions',
      'permissions' => 'menu.permissions',
      'icon' => '<i class="ri-hammer-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('permissions'),
          'label' => 'Permissions List',
          'key' => 'permissions-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'permissions.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('permissions/create'),
          'label' => 'Add Permission',
          'key' => 'permissions-create',
          'icon' => '<i class="ri-circle-fill circle-icon text-info-main w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'permissions.create',
          'sub_menu' => array()
        ),  
      ),
    ),
    'menu_3' => array(
      'link' => "javascript:void(0)",
      'label' => 'Roles',
      'key' => 'roles',
      'permissions' => 'menu.roles',
      'icon' => '<i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('roles'),
          'label' => 'Roles List',
          'key' => 'role-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'roles.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('roles/create'),
          'label' => 'Add Role',
          'key' => 'role-create',
          'icon' => '<i class="ri-circle-fill circle-icon text-info-main w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'roles.create',
          'sub_menu' => array()
        ),  
      ),
    ),
    'menu_1165' => array(
    'link' => "javascript:void(0)",
    'label' => 'Calculate',
    'key' => 'calculate',
    'permissions' => 'menu.calculate',
   'icon' => '<i class=ri-calculator-fill text-xl me-14 d-flex w-auto"></i>',
    'top_menu' => true,
    'sub_menu' => array(
        array(
            'link' => url('financial-calculator'), 
            'label' => 'Financial Calculator',
            'key' => 'financial-calculator',
            'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
            'top_menu' => true,
            'permissions' => 'financialcalculator.list',
            'sub_menu' => array()
        ),
        array(
          'link' => url('calculate/cashflow-forecasting-calc'),
          'label' => 'Cashflow Forecasting Calculator',
          'key' => 'cashflow-forecasting-calc',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'calc.cashflow-forecasting-calc',
          'sub_menu' => array()
        ),
    ),
  ),
    'menu_4' => array(
      'link' => "javascript:void(0)",
      'label' => 'Users',
      'key' => 'users',
      'permissions' => 'menu.users',
      'icon' => '<iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('users'),
          'label' => 'Users List',
          'key' => 'user-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'users.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('users/create'),
          'label' => 'Add User',
          'key' => 'user-add',
          'icon' => '<i class="ri-circle-fill circle-icon text-info-main w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'users.create',
          'sub_menu' => array()
        ),  
      ),
    ),
     'menu_settings' => array(
      'link' => "javascript:void(0)",
      'label' => 'Settings',
      'key' => 'settings',
      'permissions' => 'menu.settings',
      'icon' => '<i class="ri-settings-2-line"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('settings/branding'),
          'label' => 'Branding',
          'key' => 'branding-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'branding.index',
          'sub_menu' => array()
        ),        
      ),
    ),
    'menu_notes' => array(
      'link' => "javascript:void(0)",
      'label' => 'Notes',
      'key' => 'note',
      'permissions' => 'menu.note',
      'icon' => '<i class="ri-file-edit-fill text-xl me-14 d-flex w-auto"></i>',
      'top_menu' => true,
      'sub_menu' => array(
        array(
          'link' => url('note'),
          'label' => 'Note List',
          'key' => 'note-list',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'note.index',
          'sub_menu' => array()
        ),
        array(
          'link' => url('note/create'),
          'label' => 'Add Note',
          'key' => 'note-add',
          'icon' => '<i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>',
          'top_menu' => true,
          'permissions' => 'note.create',
          'sub_menu' => array()
        )        
      ),
    ),
  );
  ?>

  <div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">

      <?php 
      $menu_item_selected = isset($_COOKIE['menu_item_selected']) ? $_COOKIE['menu_item_selected'] : array();

          if( $menu_item_selected ){
            $menu_item_selected = json_decode( $menu_item_selected );                       
          }

      if( $menu_list  ){
        foreach ( $menu_list  as $key => $menu_data ) { 

          if( in_array( $menu_data['permissions'], $authUserData ) || $menu_data['permissions'] == 'default'  ){
            $current_item = '';
            $main_active = '';
            if( $menu_item_selected ){
              foreach( $menu_item_selected as $menu_item ){
                if( $menu_item->link_text == $menu_data['label'] && $menu_item->submenu == 1 ){
                  $current_item = ' kt-menu__item--open';                     
                }
                if( $menu_item->link_text == $menu_data['label']  ){                      
                  $main_active = ' active-page';
                }
              }
            }
            ?>


            <li class="hgmain-menu__item hg-menu__item  <?php echo ($menu_data['sub_menu']) ? ' dropdown' : ''; echo $current_item; ?>" aria-haspopup="true" >    


              <a href="<?php echo $menu_data['link']; ?>" class="hg-menu__link <?php echo ($menu_data['sub_menu']) ? ' hg-menu__toggle' : ''; echo $main_active; ?>">
                <?php echo $menu_data['icon']; ?>
                <span class="hg-menu__link-text"><?php echo $menu_data['label']; ?></span>
              </a>              
              <?php
              if( $menu_data['sub_menu'] ){
                ?>
                <ul class="sidebar-submenu">
          
                    <?php 
                    foreach( $menu_data['sub_menu'] as $sub_menu ){
                      if(  in_array( $sub_menu['permissions'], $authUserData ) || $sub_menu['permissions'] == 'default'  ){
                        $sub_item = '';
                        $sub_active = '';
                        if( $menu_item_selected ){
                          foreach( $menu_item_selected as $menu_item ){
                            if( $menu_item->link_text == $sub_menu['label'] && $menu_item->submenu == 1 ){
                              $sub_item = ' hg-menu__item--open';                                   
                            }
                            if( $menu_item->link_text == $sub_menu['label'] ){
                              $sub_active = ' active-page';
                            }
                          }
                        }
                        if( $sub_menu['sub_menu'] ){

                          ?>
                          <li class="hg-menu__item hg-menu__item--submenu <?php echo $sub_item; ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                            <a href="<?php echo $sub_menu['link']; ?>" class="hg-menu__link hg-menu__toggle <?php echo $sub_active; ?>">
                              <?php echo $sub_menu['icon']; ?>
                              <span class="hg-menu__link-text"><?php echo $sub_menu['label']; ?></span>                              
                            </a>
                            
                              <ul class="sidebar-submenu">
                                <?php 
                                foreach( $sub_menu['sub_menu'] as $sub_menu2 ){

                                  if(  in_array( $sub_menu2['permissions'], $authUserData ) || $sub_menu2['permissions'] == 'default'  ){

                                    $sub_item2 = '';
                                    $sub2_active = '';
                                    if( $menu_item_selected ){
                                      foreach( $menu_item_selected as $menu_item ){
                                        if( $menu_item->link_text == $sub_menu2['label'] && $menu_item->submenu == 1 ){
                                          $sub_item2 = 'hg-menu__item--open';                                             
                                        }                                           
                                        if( $menu_item->link_text == $sub_menu2['label'] ){
                                          $sub_item2 = 'hg-menu__item--open';
                                          $sub2_active = ' active';
                                        }
                                      }
                                    }

                                    if( $sub_menu2['sub_menu'] ){
                                      ?>
                                      <li class="hg-menu__item hg-menu__item--submenu <?php echo $sub_item2; ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                        <a href="<?php echo $sub_menu2['link']; ?>" class="hg-menu__link <?php echo $sub_active; ?>">
                                          <?php echo $sub_menu2['icon']; ?>
                                          <span class="hg-menu__link-text"><?php echo $sub_menu2['label']; ?></span>                                          
                                        </a>
                                      </li>
                                      <?php
                                    }else{
                                      ?>
                                      <li class="hg-menu__item " aria-haspopup="true">
                                        <a href="<?php echo $sub_menu2['link']; ?>" class="hg-menu__link  <?php echo $sub2_active; ?>">
                                          <?php echo $sub_menu2['icon']; ?>
                                          <span class="hg-menu__link-text"><?php echo $sub_menu2['label']; ?></span>
                                        </a>
                                      </li>
                                      <?php
                                    }
                                  }
                                }
                                ?>
                              </ul>
                           
                          </li>
                          <?php
                        }else{
                          ?>
                          <li class="hg-menu__item " aria-haspopup="true">
                            <a href="<?php echo $sub_menu['link']; ?>" class="kt-menu__link <?php echo $sub_active; ?>">
                              <?php echo $sub_menu['icon']; ?>
                              <span class="hg-menu__link-text"><?php echo $sub_menu['label']; ?></span>
                            </a>
                          </li>
                          <?php
                        }
                      }
                    }
                    ?>
                  </ul>

                <?php
              }
              ?>
            </li>
            <?php
          }
        }
      }
      ?>

     
    </ul>
  </div>
</aside>

<main class="dashboard-main">
  <div class="navbar-header">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" placeholder="Search">
          <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
        </form>
      </div>
    </div>
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
        
        <div class="dropdown">
          <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
            @php
              $profileImage = auth()->user()->image ?? null;
              $userId = auth()->id();
              $imagePath = $profileImage && file_exists(public_path("assets/images/users/{$userId}/{$profileImage}"))
                  ? url("public/assets/images/users/{$userId}/" . rawurlencode($profileImage))
                  : asset('assets/images/user.png'); // fallback to default image
           @endphp

          <img src="{{ $imagePath }}" alt="User Image" class="w-40-px h-40-px object-fit-cover rounded-circle">

          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm">
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ isset(auth()->user()->name) ? auth()->user()->name : '' }}</h6>
              </div>
              <button type="button" class="hover-text-danger">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon> 
              </button>
            </div>
            <ul class="to-top-list">             
              <li>
                <a href="{{ route('user.edit-profile') }}" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="javascript:void(0)"> 
                <iconify-icon icon="lucide:edit" class="icon text-xl"></iconify-icon>  Edit Profile</a>
              </li>
              <li>
                <a href="{{ route('user.change-password') }}" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="javascript:void(0)"> 
                <iconify-icon icon="lucide:key" class="icon text-xl"></iconify-icon>  Change My Password</a>
              </li>
              <li>
                <a href="{{ route('logout.perform') }}" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="javascript:void(0)"> 
                <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>  Log Out</a>
              </li>
            </ul>
          </div>
        </div><!-- Profile dropdown end -->
      </div>
    </div>
  </div>
</div> 
  @yield('content')
  <footer class="d-footer">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <p class="mb-0">© 2025 All Rights Reserved.</p>
    </div>
    <div class="col-auto">
    </div>
  </div>
</footer>
</main>
  <!-- jQuery library js -->
  <script src="{{asset('assets/js/lib/jquery-3.7.1.min.js')}}"></script>
  <!-- Bootstrap js -->
  <script src="{{asset('assets/js/lib/jquery.validate.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/jquery.form.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/moment.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/daterangepicker.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/dropzone/dropzone.min.js')}}"></script>
  <!-- Apex Chart js -->
  <script src="{{asset('assets/js/lib/apexcharts.min.js')}}"></script>
  <!-- Data Table js -->
  <script src="{{asset('assets/js/lib/dataTables.min.js')}}"></script>
  <!-- Iconify Font js -->
  <script src="{{asset('assets/js/lib/iconify-icon.min.js')}}"></script>
  <!-- jQuery UI js -->
  <script src="{{asset('assets/js/lib/jquery-ui.min.js')}}"></script>
  <!-- Vector Map js -->
  <script src="{{asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
  <!-- Popup js -->
  <script src="{{asset('assets/js/lib/magnifc-popup.min.js')}}"></script>
  <!-- Slick Slider js -->
  <script src="{{asset('assets/js/lib/slick.min.js')}}"></script>
  
  <script src="{{asset('assets/js/lib/select2.min.js')}}"></script>

  <script src="{{asset('assets/js/lib/sweetalert2.js')}}"></script>        
  
  <!-- prism js -->
  <script src="{{asset('assets/js/lib/prism.js')}}"></script>
  <!-- file upload js -->
  <script src="{{asset('assets/js/lib/file-upload.js')}}"></script>
  <!-- audioplayer -->
  <script src="{{asset('assets/js/lib/audioplayer.js')}}"></script>
  
  <!-- main js -->
  <script src="{{asset('assets/js/app.js?ver=1.2')}}"></script>

  <script src="{{asset('assets/js/comman-script.js?ver='.time())}}"></script>
  @yield('scripts')
</body>
</html>