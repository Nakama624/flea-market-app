<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
  // profile設定画面を表示
  public function profile(){
    $user = Auth::user();
    return view('profile', compact('user'));
  }

  // 初回ログイン時はprofileの情報を追加
  public function updateProfileInfo(ProfileRequest $request){
    $user = Auth::user();

    $data = $request->only(['name', 'postcode', 'address', 'building']);

    // 画像が選択された場合だけ保存
    if ($request->hasFile('profile_img')) {
      $path = $request->file('profile_img')->store('profiles', 'public');
      $data['profile_img'] = basename($path);
    }

    $user->update($data);
    return redirect('/mypage?page=sell');
  }


  // マイページを表示
  public function mypage(Request $request){
    $user = Auth::user();
    $page = $request->query('page', 'sell');

    $items = collect();
    $soldItemIds = [];

    // 出品
    if ($page === 'sell') {

      $items = $user->sellItems()
        ->with(['seller', 'purchaseItem'])
        ->get();

    // 購入品
    }elseif ($page === 'buy'){

      $items = $user->purchases()
        ->with('item.seller', 'item.purchaseItem')
        ->get()
        ->pluck('item');

    }else{

      // SOLD
      $soldItemIds = $items->pluck('id')->toArray();

      return redirect('/mypage?page=sell');

    }

    return view('mypage', compact('user', 'items', 'soldItemIds'));
  }
}