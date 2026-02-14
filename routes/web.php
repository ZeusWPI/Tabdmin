<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CheckPaymentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IbanUsernameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('loginCallback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'admin']);

// Transactions
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions')->middleware(['auth', 'admin']);
Route::post('/transactions', [TransactionController::class, 'store'])->middleware(['auth', 'admin']);
Route::post('/transactions/sync', [CheckPaymentsController::class, '__invoke'])->middleware(['auth', 'admin']);

// BankAccounts
Route::get('/bankAccounts', [BankAccountController::class, 'index'])->name('bankAccounts')->middleware(['auth', 'admin']);
Route::get('/bankAccounts/connect/callback', [BankAccountController::class, 'callback'])->name('bankConnectCallback')->middleware(['auth', 'admin']);
Route::get('/bankAccounts/connect/{id}', [BankAccountController::class, 'connect'])->middleware(['auth', 'admin']);
Route::delete('/bankAccounts/{id}', [BankAccountController::class, 'destroy'])->middleware(['auth', 'admin']);

// IBAN-Username
Route::get('/iban-usernames', [IbanUsernameController::class, 'index'])->name('ibanUsernames')->middleware(['auth', 'admin']);
Route::post('/iban-usernames', [IbanUsernameController::class, 'store'])->middleware(['auth', 'admin']);
Route::delete('/iban-usernames/{ibanUsername}', [IbanUsernameController::class, 'destroy'])->middleware(['auth', 'admin']);
