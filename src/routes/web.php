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

// 認証あり
Route::middleware('auth')->group(function () {

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






