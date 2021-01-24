<?php 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', function(){
     return redirect('/login');
});
// Authentication Routes... 
// $this->get('/', 'Auth\LoginController@showLoginForm' )->name('login');
// $this->post('/login', 'Auth\LoginController@login' );
// $this->post('/logout', 'Auth\LoginController@logout' )->name('logout'); 
// // Password Reset Routes...
// $this->get('password/reset', 'Auth\ForgotPasswordTowController@getForgotPasswordPage')->name('password.request');
// $this->post('password/email', 'Auth\ForgotPasswordTowController@checkEmailExist')->name('password.email');
// $this->get('password/reset/{token}', 'Auth\ForgotPasswordTowController@getResetPasswordPage')->name('password.reset');
// Route::POST('reset-password','Auth\ForgotPasswordTowController@resetPassword')->name('resetPassword');
// // Change Password Routes...
// $this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
// $this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Authentication Routes...
// Route::get('/', ['as' => 'login','uses' => 'Auth\LoginController@showLoginForm']);
// Route::post('login', ['as' => '','uses' => 'Auth\LoginController@login']);
// Route::post('logout', ['as' => 'logout','uses' => 'Auth\LoginController@logout']);
// // Password Reset Routes...
// Route::get('password/reset', ['as' => 'password.request','uses' => 'Auth\ForgotPasswordTowController@getForgotPasswordPage']);
// Route::post('password/email', ['as' => 'password.email','uses' => 'Auth\ForgotPasswordTowController@checkEmailExist']);
// Route::post('password/reset', ['as' => 'password.update','uses' => 'Auth\ResetPasswordController@reset']);
// Route::get('password/reset/{token}', ['as' => 'password.reset','uses' => 'Auth\ResetPasswordController@showResetForm']);



Route::group(['middleware' => ['auth']], function() {
    
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('scripts','ScriptController');
    Route::resource('sheets','SheetController');
    //new index dt.....
    Route::get('/agent-sheets', 'SheetController@getAgentsSheets')->name('getAgentsSheets'); 
    Route::get('/get-agent-sheets/get-data', 'SheetController@getAgentsSheetsData')->name('getAgentsSheetsData'); 

    Route::delete('/delete-sheet', 'SheetController@destroy')->name('deleteSheet');

    Route::get('/get-all-users', 'UserController@getAllUsers');
    Route::get('/deactivateuser', 'UserController@deactivateUserAccount');

    
    Route::get('/deleted-users', 'UserController@getDeletedUsers')->name('getDeletedUsers');

    // Route::get('/{username}/{id}/sheets', 'SheetController@getSheetsByAgentId')->name('sheetsListByAgent');
    // Route::post('/{username}/{id}/sheets','SheetController@getSheetsFilterByAgentId')->name('getSheetsFilterByAgentId');
  

    Route::get('/{username}/{id}/sheets', 'SheetController@getSheetsByAgentPage')->name('getSheetsByAgentPage');
    Route::get('sheets-by-agent/{id}', 'SheetController@getSheetsByAgentData')->name('getSheetsByAgentData');
    Route::post('/{username}/{id}/sheets', 'SheetController@getSheetsByAgentFilterPage')->name('getSheetsByAgentFilterPage');
    Route::get('sheets-by-agent/{status_id}/{created_at}/{id}', 'SheetController@getSheetsByAgentFilterData')->name('getSheetsByAgentFilterData');


    Route::put('/distribute-sheet-to-closer','SheetController@distributeToCloser')->name('distributeToCloser');
    Route::put('/multi-distribute-sheets-to-closer','SheetController@multiDistributeToCloser')->name('multiDistributeToCloser');

    Route::get('/all-sheets','SheetController@getAllSheets')->name('getAllSheets');
    Route::post('/all-sheets','SheetController@getSheetsFilterAll')->name('getSheetsFilterAll');
    // All Sheets 
    Route::get('/all-sheets', 'SheetController@getAllSheetPage')->name('getAllSheetPage'); 
    Route::get('/getallsheettestpage/getdata', 'SheetController@getAllSheetData')->name('getAllSheetData');
    Route::post('/all-sheets', 'SheetController@getAllSheetFilterPage')->name('getAllSheetTestFilterPage'); 
    Route::get('/getallsheettestpagefilter/{status_id}/{created_at}', 'SheetController@getAllSheetDataFilter')
            ->name('getAllSheetDataFilter');
    // Undistributed Sheets
    Route::get('/undistributed-sheets','SheetController@getUnDistributedSheets')->name('getUnDistributedSheets'); 
    Route::get('/undistributed-sheets-dt/getdata', 'SheetController@getUnDistributedSheetsData')->name('getUnDistributedSheetsData');
    Route::post('/undistributed-sheets', 'SheetController@getUnDistributedSheetsFiltePage')->name('getUnDistributedSheetsFiltePage'); 
    Route::get('/getudSheets-filter-data/{created_at}', 'SheetController@getUnDistributedSheetsFilterData')->name('getUnDistributedSheetsFilterData');
    // Undistributed Sheets
    Route::get('/distributed-sheets','SheetController@getDistributedSheets')->name('getDistributedSheets'); 
    Route::get('/distributed-sheets/getdata', 'SheetController@getDistributedSheetsData')->name('getDistributedSheetsData');
    Route::post('/distributed-sheets', 'SheetController@getDistributedSheetsFilterPage')->name('getDistributedSheetsFilterPage'); 
    Route::get('/distributed-sheets/{type}/{created_at}', 'SheetController@getDistributedSheetsFilterData')->name('getDistributedSheetsFilterData');
    // Reserved Sheets
    Route::get('/reserved-sheets','SheetController@getReservedSheets')->name('getReservedSheets'); 
    Route::get('/reserved-sheets/getdata', 'SheetController@getReservedSheetsData')->name('getReservedSheetsData');
    Route::post('/reserved-sheets', 'SheetController@getReservedSheetsFilterPage')->name('getReservedSheetsFilterPage'); 
    Route::get('/reserved-sheets/{created_at}', 'SheetController@getReservedSheetsFilterData')->name('getReservedSheetsFilterData');
    // Reserved Sheet Final Step
    Route::get('/reserved-sheets-final-step','SheetController@getReservedSheetsFinalStep')->name('getReservedSheetsFinalStep'); 
    Route::get('/reserved-sheets-final-step/getdata', 'SheetController@getReservedSheetsFinalStepData')->name('getReservedSheetsFinalStepData');
    Route::post('/reserved-sheets-final-step', 'SheetController@getReservedSheetsFinalStepFilterPage')->name('getReservedSheetsFinalStepFilterPage'); 
    Route::get('/reserved-sheets-final-step/{reserved_at}', 'SheetController@getReservedSheetsFinalStepFilterData')->name('getReservedSheetsFinalStepFilterData');
        
    // update sheets with state
    Route::get('/reserved-sheets/update/{id}','SheetController@editReservedSheet')->name('editReservedSheet');
    // Reserved sheets confirmed list
    Route::get('/confermed-sheets','SheetController@getConfirmedList')->name('getConfirmedList'); 
    Route::get('/confermed-sheets/getdata', 'SheetController@getConfirmedListData')->name('getConfirmedListData');
    Route::post('/confermed-sheets', 'SheetController@getConfirmedListPage')->name('getConfirmedListPage'); 
    Route::get('/confermed-sheets/{created_at}', 'SheetController@getConfirmedListFilterData')->name('getConfirmedListFilterData');
    // Reserved sheets canseled list
    Route::get('/cancelled-sheets','SheetController@getCancelledList')->name('getCancelledList'); 
    Route::get('/cancelled-sheets/getdata', 'SheetController@getCancelledListData')->name('getCancelledListData');
    Route::post('/cancelled-sheets', 'SheetController@getCancelledListPage')->name('getCancelledListPage'); 
    Route::get('/cancelled-sheets/{created_at}', 'SheetController@getCancelledListFilterData')->name('getCancelledListFilterData');
    
    
    
    // Libellé (Category)

    Route::get('/sheet-category/{id}','SheetController@getSheetCategoryPage')->name('getSheetCategoryPage'); 
    Route::get('/sheet-category-data/{id}', 'SheetController@getSheetCategoryPageData')->name('getSheetCategoryPageData');

    Route::POST('/add-category', 'SheetController@addNewCategory')->name('addNewCategory');
    Route::POST('/assign-sheet-to-category', 'SheetController@assignSheetToCategory')->name('assignSheetToCategory');
    Route::POST('/assign-multi-sheet-to-category', 'SheetController@assignMultiSheetToCategory')->name('assignMultiSheetToCategory');
   
    Route::get('/check-sheet-caty/{id}','SheetController@checkSheetHasCategory'); //------ CRUD libelle ---------
    Route::get('/liste-libelles', 'CategoryController@index')->name('getCategoriesList'); 
    Route::put('/edit-libelle', 'CategoryController@edit')->name('editCategory'); 
    Route::delete('/delete-libelle/{id}', 'CategoryController@delete')->name('deleteCategory');
    // Deleted Sheets
    Route::get('/deleted-sheets','SheetController@getDeletedSheets')->name('getDeletedSheets'); 
    Route::get('/deleted-sheets/getDate', 'SheetController@getDeletedSheetsData')->name('getDeletedSheetsData');


    Route::put('/distribute-deleted-sheet-to-closer','SheetController@distributeDeletedSheetToCloser')->name('activateSheet');
    Route::put('/multi-distribute-deleted-sheets-to-closer','SheetController@multiDistributionDeletedSheetsToCloser')->name('activateMultiSheets');
    
    
    /*-----------------------------------------*/
    // Closer Sheets  

    Route::get('/closer-sheets','SheetController@getCloserSheets')->name('getCloserSheets');
    Route::get('/closer-sheets/get-data','SheetController@getCloserSheetsData')->name('getCloserSheetsData');
    Route::post('/closer-sheets','SheetController@getCloserSheetsFilterPage')->name('getCloserSheetsFilterPage');
    Route::get('/closer-sheets-filter/{status_id}/{created_at}','SheetController@getCloserSheetsFilterData')->name('getCloserSheetsFilterData');


    Route::get('/closer-sheets-nrp','SheetController@getNRPSheets')->name('getNRPSheets');
    Route::get('/closer-sheets-refus','SheetController@getRefuSheets')->name('getRefuSheets');
    Route::get('/closer-sheets-rappel','SheetController@getRappelSheets')->name('getRappelSheets');
    Route::get('/closer-sheets-by-status/{status_id}','SheetController@getCloserSheetsByStatus')->name('getCloserSheetsByStatus');


    
    

    Route::get('/update-profile', 'UserController@getProfilePage')->name('getProfilePage');
    Route::put('/update-profile/{id}', 'UserController@updateProfile')->name('updateProfile');

    Route::get('/pdf/{id}', 'SheetController@generatePdf')->name('generatePdf');
    Route::get('/pdf-view/{id}', 'SheetController@viewPdf')->name('viewPdf');
    Route::get('/calender', 'RecallController@index')->name('getCalenderPage'); 
    Route::get('/get-recalls', 'RecallController@getRecall')->name('getRecall');
    Route::POST('/create-recalls', 'RecallController@store')->name('createRecall');
    Route::PUT('/update-recall', 'RecallController@updateRecall')->name('updateRecall'); 
    Route::delete('/delete-recall/{id}', 'RecallController@deleteRecall')->name('deleteRecall');
    Route::POST('/send-email', 'SheetController@sendEmail')->name('sendEmail');

    Route::get('/emailview', 'SheetController@emailView')->name('emailView');

    Route::get('/user-infos/{id}', 'UserController@userInfos')->name('userInfoJson');


    Route::get('/infos', 'InfoController@getInfosPage')->name('getInfosPage');
    Route::post('/update-infos', 'InfoController@updateInfos')->name('updateInfos');


    Route::get('/chartjs-all-data', 'SheetController@getPieChartData');
    Route::get('/agent-statistic/{id}','SheetController@getAgentStatisticPage')->name('getAgentStatisticPage');
    
    Route::get('/agent-global-statistic','SheetController@getGlobalAgentStatisticPage')->name('getGlobalAgentStatisticPage');
    Route::get('/chart-agent-statistic/{id}', 'SheetController@getSheetsPerAgentId');
    Route::get('/chart-agent-statistic-date/{id}/{datespecific}', 'SheetController@getSheetsPerAgentIdAndDay');
    // Route::get('/chart-agent-statistic-date/{id}/{datebegin}/{dateend}', 'SheetController@getSheetsPerAgentIdAndDay');
    Route::get('/chart-all-agents-statistic', 'SheetController@getAllAgentsStatistic');
    Route::get('/chart-all-agents-statistic-date/{datebegin}/{dateend}', 'SheetController@getAllAgentsStatisticPerDay');


    Route::get('/closer-global-statistic','SheetController@getCloserGlobalStatisticPage')->name('getCloserGlobalStatisticPage');  
    Route::get('/closer-statistic/{id}','SheetController@getCloserStatisticPage')->name('getCloserStatisticPage');  
    Route::get('/chart-closer-statistic/{id}', 'SheetController@getSheetsPerCloserId');
    Route::get('/chart-closer-statistic-date/{id}/{datebegin}/{dateend}', 'SheetController@getSheetsPerCloserIdAndDate');



    Route::POST('/new-header','HeaderController@createHeader')->name('createHeader');
    Route::get('/edit-header/{id}', 'HeaderController@editHeader')->name('editHeader');     
    Route::PUT('/update-header','HeaderController@updateHeader')->name('updateHeader'); 
    Route::delete('/delete-header/{id}','HeaderController@deleteHeader')->name('deleteHeader'); 


    Route::get('/reserved-sheets/{id}/add-reservation','SheetController@addSheetReservation' )->name('addReservation');
    Route::get('/reserved-sheets/{id}/view-reservation','SheetController@viewSheetReservation' )->name('viewReservation');
    Route::get('/reserved-sheets/{id}/edit-reservation','SheetController@editReservation' )->name('editReservation');
    Route::put('/reserved-sheets/{id}/add-reservation-update','SheetController@updateSheetReservation' )->name('updateReservation');
    Route::get('/pdf-r/{id}', 'SheetController@generateSheetReservationPDF')->name('generateSheetReservationPDF');
    Route::get('/pdf-r-view/{id}', 'SheetController@viewSheetReservationPDF')->name('viewSheetReservationPDF'); 
 
    // Controle access
    Route::get('/controle-acces', 'InfoController@getSettingPage')->name('getSettingPage'); 
    Route::put('/update-controle-acces', 'InfoController@updateControleAccess')->name('updateControleAccess'); 
   


    // Get Agents and Closers scripts
    Route::get('/script-list', 'ScriptController@getAgentsAndClosersScripts')->name('getAgentsAndClosersScripts'); 
    
    


});
