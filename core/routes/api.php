<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Exttransfer;
use App\Models\Merchant;
use App\Models\User;
use App\Http\Controllers\Api\PixController;
use App\Http\Controllers\Api\PixoutController;
use App\Http\Controllers\Api\CreditcardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\StatesController;
use App\Http\Controllers\Api\BoletoController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\BancoOriginalController;

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

Route::post('auth', [UserController::class, "login"]);
Route::get('cep/search/{cep}', [AddressController::class, "getAddressByCep"]);

Route::group(['middleware' => 'auth:api'], function(){
    
    
    // States
    Route::get('invoices', [InvoiceController::class, 'queryInvoices']);
    Route::get('invoice/{invoice_id}', [InvoiceController::class, 'invoice']);
    Route::get('invoice/ref/{ref_id}', [InvoiceController::class, 'ref']);
    
    // Credit card
    Route::post('creditcard/create', [CreditcardController::class, 'create']);
    
    // Boleto
    Route::post('boleto/create', [BoletoController::class, 'create']);
    
    // Pix
    Route::post('pix/create', [PixController::class, 'create']);
    Route::get('pix/invoice/{ref}', [PixController::class, 'invoice']);
    Route::get('pix/txid/{txid}', [PixController::class, 'txid']);
    Route::post('pixout/create', [PixoutController::class, 'create']);
    Route::get('pixout/withdraw/{txid}', [PixoutController::class, 'getWithdraw']);
    Route::get('pixout/list', [PixoutController::class, 'listWithdrawals']);
    
    
    // Customer
    Route::post('customer', [CustomerController::class, 'create']);
    Route::get('customer/{custome_id}', [CustomerController::class, 'getCustomer']);
    Route::get('customers', [CustomerController::class, 'queryCustomers']);
    
    // Country
    Route::get('country/all', [CountryController::class, 'all']);
    Route::get('country/{country_id}', [CountryController::class, 'getCountry']);
    Route::get('country/iso/{iso}', [CountryController::class, 'iso']);
    Route::get('country/iso3/{iso3}', [CountryController::class, 'iso3']);
    
    // States
    Route::get('state/all', [StatesController::class, 'all']);
    Route::get('state/{state_id}', [StatesController::class, 'getState']);
    Route::get('state/uf/{uf}', [StatesController::class, 'uf']);
    
    // Original Bank Routes
    Route::post('originalbank/account/register', [BancoOriginalController::class, 'register']);

    
    
});