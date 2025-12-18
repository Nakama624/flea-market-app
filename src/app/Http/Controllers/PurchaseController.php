<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
  // 商品購入画面表示
  public function purchase($item_id){
    $item = Item::find($item_id);
    $user = Auth::user();
    $payments = Payment::all();

    return view('purchase', compact('item','user', 'payments'));
  }

  // 住所変更画面表示
  public function address($item_id){
    $item = Item::find($item_id);
    $user = Auth::user();

    return view('address', compact('item', 'user'));
  }

  // 住所変更確定
  public function changeAddress(Request $request, $item_id){

    $address = $request->only(['delivery_postcode', 'delivery_address', 'delivery_building']);

    $payments = Payment::all();

    return redirect('/purchase/' . $item_id)
    ->withInput($request->only([
      'delivery_postcode',
      'delivery_address',
      'delivery_building',
    ]));
}

  // 商品購入確定
  public function purchaseStore(Request $request, $item_id){
    $item = Item::find($item_id);
    $user = Auth::user();

    $purchaseData = $request->only([
      'delivery_postcode',
      'delivery_address',
      'delivery_building',
      'payment_id'
    ]);

    $purchaseData['item_id'] = $item->id;
    $purchaseData['user_id'] = $user->id;

    $purchase = Purchase::create($purchaseData);

    return redirect('mypage');
  }

}
