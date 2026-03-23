<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\AssessmentChat;
use App\Models\Assessment;

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

    // ★取引評価の平均を取得
    $assessmentChatIds = AssessmentChat::where(function ($query) use ($user) {
        $query->where('seller_user_id', $user->id)
              ->orWhere('buyer_user_id', $user->id);
    })->pluck('id');

    $averageScore = Assessment::whereIn('assessment_chats_id', $assessmentChatIds)
        ->where('to_user_id', $user->id)
        ->avg('score');

    $averageScore = round($averageScore ?? 0); // ★四捨五入

    $items = collect();
    $soldItemIds = [];
    $progressItemCount = 0;

    $assessmentChats = AssessmentChat::where(function ($query) use ($user) {
        $query->where('seller_user_id', $user->id)
          ->orWhere('buyer_user_id', $user->id);
      })
      ->with(['item', 'chats'])
      ->get();

    // 取引中の商品をカウント
    $progressItemCount = $assessmentChats
      ->where('status', '取引中')
      ->count();


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

    // 取引中の商品(メッセージのやり取りがある商品が表示される)
    }elseif ($page === 'progress'){

      $items = $assessmentChats->map(function ($chat) use ($user) {
        // 未読件数を取得
        $unreadCount = $chat->chats
          ->where('sender_user_id', '!=', $user->id) // 自分以外のメッセージをカウント
          ->whereNull('read_at')
          ->count();

        // itemに追加
        $chat->item->unread_count = $unreadCount;
        $chat->item->assessment_chat_id = $chat->id;

        return $chat->item;
      });

    }else{
      // SOLD
      $soldItemIds = $items->pluck('id')->toArray();

      return redirect('/mypage?page=sell');
    }

    return view('mypage', compact('user', 'items', 'soldItemIds', 'averageScore', 'progressItemCount'));
  }
}