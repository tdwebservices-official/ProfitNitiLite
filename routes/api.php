<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KPIDataControlller;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['web','auth', 'permission404']], function() {
    Route::post('userLists', [UsersController::class, 'userLists'])->name('users.list');
    Route::post('deleteUser', [UsersController::class, 'deleteUser'])->name('users.delete');
     Route::post('bulkAssignRole', [UsersController::class, 'bulkAssignRole'])->name('users.assign');
    Route::post('permissionLists', [PermissionsController::class, 'permissionLists'])->name('permission.list');
    Route::post('deletePermission', [PermissionsController::class, 'deletePermission'])->name('permissions.delete');
    Route::post('roleLists', [RolesController::class, 'roleLists'])->name('role.list');
    Route::post('deleteRole', [RolesController::class, 'deleteRole'])->name('role.delete');

   
    
    Route::post('AddOrUpdateBranding', [OptionsController::class, 'AddOrUpdateBranding'])->name('branding.addOrupdate');
    Route::post('UploadMedia', [OptionsController::class, 'UploadMedia'])->name('attachments.add-update');
    Route::post('RemoveMedia', [OptionsController::class, 'RemoveMedia'])->name('attachments.remove');

    Route::post('deleteKPIRecord', [KPIDataControlller::class, 'deleteKPIRecord'])->name('kpi-record.delete');
    Route::post('updateKPIRecord', [KPIDataControlller::class, 'updateKPIRecord'])->name('kpi-record.update');
    Route::post('storeKPIRecord', [KPIDataControlller::class, 'storeKPIRecord'])->name('kpi-record.store');
    Route::post('loadKPIRecord', [KPIDataControlller::class, 'loadKPIRecord'])->name('kpi-record.store');
    Route::post('storeQuickKPIRecord', [KPIDataControlller::class, 'storeQuickKPIRecord'])->name('kpi-record.store');
    Route::post('kpiRecordLists', [KPIDataControlller::class, 'kpiRecordLists'])->name('kpi-record.list');  
    
    Route::post('getPLReport', [ReportController::class, 'getPLReport'])->name('reports.pl-report');
    Route::post('getBLReport', [ReportController::class, 'getBLReport'])->name('reports.bl-report');
    Route::post('getBLReportView', [ReportController::class, 'getBLReportView'])->name('reports.bl-report');
    Route::post('getPLReportView ', [ReportController::class, 'getPLReportView'])->name('reports.pl-report');
    Route::post('getFNSReport', [ReportController::class, 'getFNSReport'])->name('reports.fns-report');
    Route::post('getFNSReportView', [ReportController::class, 'getFNSReportView'])->name('reports.fns-report');
    Route::post('getCashflowReport', [ReportController::class, 'getCashflowReport'])->name('reports.cashflow-report');
    Route::post('getProfitPowerReport', [ReportController::class, 'getProfitPowerReport'])->name('reports.profit-power-report');
    Route::post('getCashMngReport', [ReportController::class, 'getCashMngReport'])->name('reports.cash-mng-report');
    Route::post('getCapexReport', [ReportController::class, 'getCapexReport'])->name('reports.capex-report');
    Route::post('getFinancingReport', [ReportController::class, 'getFinancingReport'])->name('reports.financing-report');
    Route::post('getImpactOfChangeReport', [ReportController::class, 'getImpactOfChangeReport'])->name('reports.impact-of-change-report');

    Route::post('getCashFlowQualityReport', [ReportController::class, 'getCashFlowQualityReport'])->name('reports.cashflow-quality-report');
    Route::post('getBSCategoryReport', [ReportController::class, 'getBSCategoryReport'])->name('reports.bs-category-report');
    Route::post('getDashboardReports', [DashboardController::class, 'getDashboardReports'])->name('dashboard.report');
    Route::post('generateDashboardDataWithDeepSeek', [DashboardController::class, 'generateDashboardDataWithDeepSeek'])->name('dashboard.deepseekAPI');

      /*  Route::post('/calculate/multi-product', 'FinancialCalculatorController@calculateMultiProduct')->name('financial-calculator.multi-product');
        Route::post('/calculate/seasonal', 'FinancialCalculatorController@calculateSeasonal')->name('financial-calculator.seasonal');
        Route::post('/calculate/growth', 'FinancialCalculatorController@calculateGrowth')->name('financial-calculator.growth');
        Route::post('/export/pdf', 'FinancialCalculatorController@exportPdf')->name('financial-calculator.export-pdf');
        Route::get('/templates/business', 'FinancialCalculatorController@getBusinessTemplates')->name('financial-calculator.templates');*/
    
    
});