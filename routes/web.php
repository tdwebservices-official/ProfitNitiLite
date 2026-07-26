<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
Route::group(['namespace' => 'App\Http\Controllers'], function() {   
    


    Route::get('/test-mailss', function () {
        try {
            Mail::raw('Test Email', function ($message) {
                $message->to('viviw47772@epaynine.com') // Replace with your email
                        ->subject('Laravel Mail Test');
            });
    
            return response()->json([
                'success' => true,
                'message' => 'Mail sent successfully.'
            ]);
    
        } catch (\Exception $e) {
    
            Log::error('Test mail failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Mail sending failed.',
                'error' => $e->getMessage(), // Remove this in production
            ], 500);
        }
    });
    
    Route::get('/', 'DashboardController@index')->name('home.index');
    Route::group(['middleware' => ['guest']], function() {
       
       Route::get('/login', 'LoginController@show')->name('login.show');
       Route::post('/login', 'LoginController@login')->name('login.perform');
       
        // Register
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');
    
        // Forgot Password
        Route::get('/forgot-password', 'ForgotPasswordController@show')->name('forgot-password.show');
        Route::post('/forgot-password', 'ForgotPasswordController@sendNewPassword')->name('forgot-password.send');
    });
    Route::group(['middleware' => ['web','auth', 'permission404']], function() {
       Route::group(['prefix' => 'users'], function() {
        Route::get('/', 'UsersController@index')->name('users.index');
        Route::get('/create', 'UsersController@create')->name('users.create');
        Route::post('/create', 'UsersController@store')->name('users.store');
        Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
        Route::post('/{user}/update', 'UsersController@update')->name('users.update');  

     });

      Route::get('/help', 'DashboardController@help')->name('help.perform');
       
       Route::group(['prefix' => 'kpi-records'], function() {       
        Route::get('/', 'KPIDataControlller@index')->name('kpi-records.index');
        Route::get('/create', 'KPIDataControlller@create')->name('kpi-record.create');
        Route::get('/quick-create', 'KPIDataControlller@quickcreate')->name('kpi-record.create');

     });


       Route::group(['prefix' => 'reports'], function() {               
        Route::get('/pl-reports', 'ReportController@plReports')->name('reports.pl-report');
        Route::get('/bl-reports', 'ReportController@blReports')->name('reports.bl-report');
        Route::get('/fns-reports', 'ReportController@fnsReports')->name('reports.fns-report');
        Route::get('/cashflow-reports', 'ReportController@cashflowReports')->name('reports.cashflow-report');
        Route::get('/profit-power-reports', 'ReportController@profitPowerReports')->name('reports.profit-power-report');
        Route::get('/cash-mng-reports', 'ReportController@cashMngReports')->name('reports.cash-mng-report');
        Route::get('/capex-reports', 'ReportController@CapexReports')->name('reports.capex-report');
        Route::get('/financing-reports', 'ReportController@FinancingReports')->name('reports.financing-report');
        Route::get('/bs-category-reports', 'ReportController@BSCategoryReports')->name('reports.bs-category-report');
        Route::get('/cashflow-quality-reports', 'ReportController@CashFlowQualityReports')->name('reports.cashflow-quality-report');
        Route::get('/impact-of-change-reports', 'ReportController@ImpactOfChangeReports')->name('reports.impact-of-change-report');
     });


        // Route::group(['prefix' => 'settings'], function() {       
        //   Route::get('/branding', 'OptionsController@bradingIndex')->name('branding.index');        
        // });

        Route::group(['prefix' => 'me'], function() {       
          Route::get('/edit-profile', 'UsersController@editProfile')->name('user.edit-profile');       
          Route::post('/updateEditProfile', 'UsersController@updateEditProfile')->name('user.update-edit-profile');   
          Route::get('/change-password', 'UsersController@changePassword')->name('user.change-password');        
          Route::post('/updateChangePassword', 'UsersController@updateChangePassword')->name('user.update-change-password');        
        });


       Route::resource('roles', RolesController::class);
       Route::resource('permissions', PermissionsController::class);
    });
    Route::group(['middleware' => ['auth']], function() {       
        
       Route::get('/logout', 'LoginController@logout')->name('logout.perform');
    });
});