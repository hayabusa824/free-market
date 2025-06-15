<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


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


Route::get('/register', [AuthController::class, 'index']);
Route::post('/register', [AuthController::class, 'store'])->name('register');
Route::post('/', [LoginController::class, 'store']);
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item}', [DetailController::class, 'show'])->name('detail');
Route::get('/search', [ItemController::class, 'search'])->name('search');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/?page=mylist', [ItemController::class, 'index']);

    Route::get('/mypage/profile', [AddressController::class, 'index']);
    Route::post('/mypage/profile/store', [AddressController::class, 'store'])->name('profile.store');
    Route::post('/mypage/profile/update', [AddressController::class, 'update'])->name('profile.update');

    Route::get('/mypage', [MypageController::class, 'index'])->name('auth.mypage');
    Route::get('/mypage/?tab=buy', [MypageController::class, 'index']);
    Route::get('/mypage/?tab=sell', [MypageController::class, 'index']);

    Route::get('/sell', [SellController::class, 'index']);
    Route::post('/', [SellController::class, 'store']);

    Route::post('/item/{item}/like', [LikeController::class, 'toggle']);
    Route::post('/item/{item}/comment', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.editAddress');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');
});



Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // ユーザーのemail_verified_atを更新

    return redirect('/mypage/profile'); // 認証後のリダイレクト先（任意）
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証リンクを再送信しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

