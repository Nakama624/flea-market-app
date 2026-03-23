<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\AssessmentChat;
use App\Models\Chat;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class ChatController extends Controller
{
  public function show(AssessmentChat $assessmentChat){ // ★修正
    $user = Auth::user();

    $assessmentChat->load(['item.seller', 'seller']); // ★修正
    $item = $assessmentChat->item; // ★修正

    // ★修正: 自分に関係ある取引だけ許可
    if ($assessmentChat->seller_user_id !== $user->id && $assessmentChat->buyer_user_id !== $user->id) {
        abort(403);
    }

    // 取引中の商品をすべて取得してサイドバーに表示する
    $progressItems = AssessmentChat::where(function ($query) use ($user) {
      $query->where('seller_user_id', $user->id)
            ->orWhere('buyer_user_id', $user->id);
    })
    ->with(['item'])
    ->get();

    $chats = $assessmentChat->chats()
      ->with('sender')
      ->oldest()
      ->get();

    $shouldOpenAssessmentModal = false;

    $assessmentChat->chats()
      ->where('sender_user_id', '!=', $user->id)
      ->whereNull('read_at')
      ->update([
        'read_at' => now()
      ]);

    if ($assessmentChat->seller_user_id === $user->id) {
      $hasSentAssessment = $assessmentChat->assessments()
        ->where('from_user_id', $user->id)
        ->exists();

      $hasReceivedAssessment = $assessmentChat->assessments()
        ->where('to_user_id', $user->id)
        ->exists();

      if (!$hasSentAssessment && $hasReceivedAssessment) {
        $shouldOpenAssessmentModal = true;
      }
    }

    return view('chat', compact(
      'item',
      'user',
      'assessmentChat',
      'progressItems',
      'chats',
      'shouldOpenAssessmentModal'
    ));
  }


  public function send(MessageRequest $request, Item $item){
      $user = Auth::user();
      $message = $request->input('message');

      if ($message === '' && ! $request->hasFile('item_img')) {
          return back();
      }

      // 既存の取引チャットを取得
      $assessmentChat = AssessmentChat::where('item_id', $item->id)
          ->where(function ($query) use ($user) {
              $query->where('seller_user_id', $user->id)
                    ->orWhere('buyer_user_id', $user->id);
          })
          ->first();

      // なければ新規作成（buyerが最初に送る）
      if (! $assessmentChat) {
          $assessmentChat = AssessmentChat::create([
              'item_id' => $item->id,
              'seller_user_id' => $item->sell_user_id, // ★修正: sell_user_id ではなく seller_user_id を確認
              'buyer_user_id' => $user->id,
              'status' => '取引中',
              'last_chat_at' => now(),
          ]);
      }

      // 画像を先に保存して、作成するchatにだけ持たせる
      $fileName = null;
      if ($request->hasFile('item_img')) {
          $path = $request->file('item_img')->store('chat_images', 'public');
          $fileName = basename($path);
      }

      // createした1件に画像も一緒に保存
      $assessmentChat->chats()->create([
          'sender_user_id' => $user->id,
          'chat' => $message,
          'item_img' => $fileName,
      ]);

      $assessmentChat->update([
          'last_chat_at' => now(),
      ]);

      return back();
  }


  public function assessment(Request $request, Item $item)
  {
      $user = Auth::user();
      $score = $request->input('score');

      // 対象のAssessmentChatを取得
      $assessmentChat = AssessmentChat::where('item_id', $item->id)
          ->where(function ($query) use ($user) {
              $query->where('seller_user_id', $user->id)
                    ->orWhere('buyer_user_id', $user->id);
          })
          ->firstOrFail();

      if ($user->id === $assessmentChat->seller_user_id) {
        // assessmentChatで評価日時を更新
        $assessmentChat->seller_completed_at = now();
        $assessmentChat->status = "完了";
        // メールを送った日付を入れる

        // assessmentのカラムを作成
        $assessmentChat->assessments()->create([
          'from_user_id' => $user->id,
          'to_user_id' => $assessmentChat->buyer_user_id,
          'score' => $score
        ]);



      } elseif ($user->id === $assessmentChat->buyer_user_id) {
        $assessmentChat->buyer_completed_at = now();

        // assessmentのカラムを作成
        $assessmentChat->assessments()->create([
          'from_user_id' => $user->id,
          'to_user_id' => $assessmentChat->seller_user_id,
          'score' => $score
        ]);
      }
      $assessmentChat->save();

      return redirect("/mypage?page=sell");
  }

  public function delete(Request $request, Item $item){
    $chat = Chat::findOrFail($request->id);
    $chat->delete();

    return back();
  }

  public function update(UpdateMessageRequest $request, Item $item){
    $chat = Chat::findOrFail($request->id);

    // ★修正: 自分のメッセージだけ編集できるようにする
    if ($chat->sender_user_id !== Auth::id()) {
        return redirect("/item/{$item->id}/chat");
    }

    $chat->update([
        'chat' => $request->chat,
        'edited_at' => now(), // ★修正: 編集日時も更新
    ]);

    return redirect("/item/{$item->id}/chat");
  }
}
