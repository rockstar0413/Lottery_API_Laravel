<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Web\DepositController;
use App\Http\Controllers\Web\TransferController;
use App\Http\Controllers\Web\WithdrawController;
use App\Http\Controllers\Web\AccountController;
/* Admin controllers. */
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SystemSetting\SystemParametersController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/deposit', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'users', 'middleware' => 'CORS'], function ($router) {
    Route::post('/register', [UserController::class, 'register'])->name('register.user');
    Route::post('/login', [UserController::class, 'login'])->name('login.user');
    Route::get('/view-profile', [UserController::class, 'viewProfile'])->name('profile.user');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout.user');
});
/* Sports routes */
Route::group(['prefix' => 'sport', 'middleware' => 'CORS'], function ($router){
    Route::resource('/get_data', SportController::class);
    Route::post('/get_item_date', [SportController::class, 'get_item_date']);
    Route::post('/bet_ft', [SportController::class, 'betFt']);
    Route::post('/get_betting_records', [SportController::class, 'get_betting_records'])->name('sport.get_betting_records');
});

Route::post('/get_item_date', [SportController::class, 'get_item_date']);// Test_API

Route::group(['prefix' => 'result', 'middleware' => 'CORS'], function ($router){
    Route::post('/get_result_ft', [ResultController::class, 'getResultFt'])->name('result.getResultFt');
});
/* Admin routes. */
Route::group(['prefix'=>'admin', 'middleware'=>'CORS'], function ($router) {
    /* Authentication */
    Route::post('/login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('admin.auth.register');

    Route::group(['prefix'=>'system-setting'], function ($router) {
        /* System Setting */
        // System Parameters
        // Website URL
        Route::post('/system-parameters/get-urls', [SystemParametersController::class, 'get_urls'])->name('admin.system-setting.system-parameters.get-urls');
        Route::post('/system-parameters/set-urls', [SystemParametersController::class, 'set_urls'])->name('admin.system-setting.system-parameters.set-urls');

        //Turn on/off services
        Route::post('/system-parameters/get-turnservices', [SystemParametersController::class, 'get_turnservices'])->name('admin.system-setting.system-parameters.get-turnservices');
        Route::post('/system-parameters/set-turnservices', [SystemParametersController::class, 'set_turnservices'])->name('admin.system-setting.system-parameters.set-turnservices');

        //HomePage Notification
        Route::post('/system-parameters/get-homenotifications', [SystemParametersController::class, 'get_homenotifications'])->name('admin.system-setting.system-parameters.get-homenotifications');
        Route::post('/system-parameters/set-homenotifications', [SystemParametersController::class, 'set_homenotifications'])->name('admin.system-setting.system-parameters.set-homenotifications');

    });
});

Route::group(['prefix' => 'deposit', 'middleware' => 'CORS'], function ($router) {
    Route::get('/getBank', [DepositController::class, 'getBank'])->name('web.deposit.getBank');
    Route::post('/addMoney', [DepositController::class, 'addMoney'])->name('web.deposit.addMoney');
});
Route::group(['prefix' => 'transfer', 'middleware' => 'CORS'], function ($router) {
    Route::get('/getSysConfig', [TransferController::class, 'getSysConfig'])->name('web.transfer.getSysConfig');
    Route::post('/transferMoney', [TransferController::class, 'transferMoney'])->name('web.transfer.transferMoney');
});
Route::group(['prefix' => 'withdraw', 'middleware' => 'CORS'], function ($router) {
    Route::get('/get-transaction-history', [WithdrawController::class, 'getTransactionHistory'])->name('web.withdraw.getTransactionHistory');
    Route::post('/quick-withdraw', [WithdrawController::class, 'quickWithdraw'])->name('web.withdraw.quickWithdraw');
});
Route::group(['prefix' => 'account', 'middleware' => 'CORS'], function ($router) {
    Route::get('/get-bank-list', [AccountController::class, 'getUserBankAccounts'])->name('web.account.getUserBankAccounts');
    Route::get('/get-crypto-list', [AccountController::class, 'getUserCryptoAccounts'])->name('web.account.getUserCryptoAccounts');
    Route::post('/add-bank-account', [AccountController::class, 'addBankAccount'])->name('web.account.addBankAccount');
    Route::post('/add-crypto-account', [AccountController::class, 'addCryptoAccount'])->name('web.account.addCryptoAccount');
    Route::post('/edit-bank-account', [AccountController::class, 'editBankAccount'])->name('web.account.editBankAccount');
    Route::post('/edit-crypto-account', [AccountController::class, 'editCryptoAccount'])->name('web.account.editCryptoAccount');
    Route::delete('/delete-bank-account', [AccountController::class, 'deleteBankAccount'])->name('web.account.deleteBankAccount');
    Route::delete('/delete-crypto-account', [AccountController::class, 'deleteCryptoAccount'])->name('web.account.deleteCryptoAccount');
});

