<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
  // 商品購入画面表示
  public function purchase($item_id){
    $item = Item::findOrFail($item_id);
    $user = Auth::user();
    $payments = Payment::all();

    return view('purchase', compact('item','user', 'payments'));
  }

  // 住所変更画面表示
  public function address($item_id){
    $item = Item::findOrFail($item_id);
    $user = Auth::user();

    return view('address', compact('item', 'user'));
  }

  // 住所変更確定
  public function changeAddress(AddressRequest $request, $item_id){

    return redirect('/purchase/' . $item_id)
    ->withInput($request->only([
      'delivery_postcode',
      'delivery_address',
      'delivery_building',
    ]));
}

  // 商品購入確定 → Stripe決済
  public function purchaseStore(PurchaseRequest $request, $item_id)
  {
    $item = Item::findOrFail($item_id);
    $user = Auth::user();

    // purchases に保存するデータ（テーブル定義そのまま）
    $purchase = Purchase::create([
      'item_id'           => $item->id,
      'user_id'           => $user->id,
      'delivery_postcode' => $request->delivery_postcode,
      'delivery_address'  => $request->delivery_address,
      'delivery_building' => $request->delivery_building,
      'payment_id'        => $request->payment_id,
      'status'            => 'pending',
    ]);

    // Stripe APIキー設定
    Stripe::setApiKey(config('services.stripe.secret'));

    // 支払方法（1=コンビニ / 2=カード）
    $paymentMethodTypes = ((int)$purchase->payment_id === 1)
      ? ['konbini']
      : ['card'];

    // Stripe Checkout Session
    $session = CheckoutSession::create([
      'mode' => 'payment',
      'payment_method_types' => $paymentMethodTypes,

      'line_items' => [[
        'quantity' => 1,
        'price_data' => [
          'currency' => 'jpy',
          'unit_amount' => (int)$item->price,
          'product_data' => [
            'name' => $item->name,
          ],
        ],
      ]],

      // Webhookなし
      'success_url' => url('/purchase/stripe/success?purchase_id=' . $purchase->id),
      'cancel_url'  => url('/purchase/stripe/cancel?item_id=' . $item->id),
    ]);

    return redirect()->away($session->url);
  }

  // Stripe 決済成功
  public function stripeSuccess(Request $request)
  {
    $purchaseId = $request->query('purchase_id');

    $purchase = Purchase::where('id', $purchaseId)
      ->where('user_id', Auth::id())
      ->firstOrFail();

    // stripe→Laravelへ戻ってくるとすぐに支払済みへ
    if ($purchase->status !== 'paid') {
      $purchase->status = 'paid';
      $purchase->paid_at = now();
      $purchase->save();
    }

    return redirect('/');
  }

  // Stripe キャンセル
  public function stripeCancel(Request $request)
  {
    return redirect('/purchase/' . $request->query('item_id'))
      ->with('message', '支払いをキャンセルしました');
  }
}