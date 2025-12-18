<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class UserController extends Controller
{
  // profile設定画面を表示
  public function profile(){
    $user = Auth::user();
    return view('profile', compact('user'));
  }

  // 初回ログイン時はprofileの情報を追加
  public function updateProfileInfo(Request $request){
    $user = Auth::user();

    $data = $request->only(['name', 'postcode', 'address', 'building']);

    // 画像が選択された場合だけ保存
    if ($request->hasFile('profile_img')) {
        $path = $request->file('profile_img')->store('profiles', 'public');
        $data['profile_img'] = $path;
    }

    $user->update($data);
    return redirect('/mypage');
  }


  // マイページを表示
  public function mypage(Request $request){
    $user = Auth::user();
    $page = $request->query('page');

    $items = collect();
    $soldItemIds = [];

    // 出品
    if ($page === 'sell') {

      $items = $user->sellItems()
        ->with(['seller', 'purchaseItem'])
        ->get();

    // 購入品
    }elseif ($page === 'buy'){

      $boughtItemIds = Purchase::where('user_id', $user->id)
        ->pluck('item_id')
        ->toArray();

      $items = Item::whereIn('id', $boughtItemIds)
        ->with(['seller', 'purchaseItem'])
        ->get();

      // SOLD
      $soldItemIds = $boughtItemIds;

    }else{
      $items = Item::all();
      // いったんすべて表示★
      $soldItemIds = Purchase::pluck('item_id')->toArray();
    }
    return view('mypage', compact('user', 'items', 'soldItemIds'));
  }
}