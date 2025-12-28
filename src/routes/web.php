<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseController;

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
// 商品一覧画面（トップ画面）
Route::get('/', [ItemController::class, 'index']);

// 商品詳細画面
Route::get('/item/{item_id}/', [ItemController::class, 'detail']);

Route::get('/verify-notice', function () {
  return redirect('/auth/mail');
})->middleware('auth')->name('verification.notice');

// 認証必須
Route::middleware('auth')->group(function () {

   // メール認証案内画面
  Route::get('/auth/mail', function () {
  if (request()->user()->hasVerifiedEmail()) {
    return redirect('/mypage/profile');
  }
  return view('auth.mail');
});

  // 認証メール再送
  Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
  });

  // 「認証はこちら」からMailHog を開く（開発時のみ）
  Route::get('/dev/mailhog/open', function () {
    abort_unless(app()->environment('local'), 404);

    // 認証メールを再送(後に削除予定)
    // request()->user()->sendEmailVerificationNotification();

    // MailHog を開く
    return redirect()->away('http://localhost:8025');
  });


  // ===ログイン＆メール認証必須===
  Route::middleware('verified')->group(function () {
    // 商品購入画面
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    // 商品購入
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchaseStore']);

    // 配送住所画面
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address']);
    // 配送住所登録
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'changeAddress']);

    // 出品画面を表示
    Route::get('/sell', [ItemController::class, 'sell']);
    // 出品
    Route::post('/sell', [ItemController::class, 'itemStore']);

    // マイページ画面表示
    Route::get('/mypage', [UserController::class, 'mypage']);


    // プロフィール登録
    Route::get('/mypage/profile', [UserController::class, 'profile']);
    Route::patch('/mypage/profile', [UserController::class, 'updateProfileInfo']);

    // コメント投稿
    Route::post('/item/{item}', [ItemController::class, 'comment']);

    // いいね機能
    Route::post('/items/{item}/like', [ItemController::class, 'toggle']);

  });
});






