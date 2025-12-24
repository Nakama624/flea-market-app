<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
// use App\Models\User;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
  public function index(Request $request){
    $tab = $request->query('tab');

    // おすすめタブ
    if ($tab === null) {
      $query = Item::query();

      // ログイン時：自分が出品したitemは表示なし
      // ログアウト時：すべての商品を表示
      if (Auth::check()) {
        $query->where('sell_user_id', '!=', Auth::id());
      }

      $items = $query->get();

    // マイリストタブ
    }elseif ($tab === 'mylist'){
      // 未認証の場合は空表示
      if (!Auth::check()) {
        return view('index', [
            'items' => collect(),
            'tab'   => 'mypage',
        ]);
      }

      $user = Auth::user();
      $items = $user->likedItems()
        ->with('purchaseItem')
        ->get();
    }

    // SOLDも表示
    $soldItemIds = Purchase::pluck('item_id')->toArray();

    return view('index', compact('items', 'soldItemIds'));
  }


  // 商品詳細
  public function detail($item_id){
    $userId = Auth::id();

    $item = Item::query()
      ->withCount('likedUsers') // いいねカウント
      ->when($userId, function ($q) use ($userId) {
        $q->withExists([
          'likedUsers as is_liked' => fn ($qq) => $qq->where('users.id', $userId),
        ]);
      }, function ($q) {
        // 未ログイン
        $q->selectRaw('false as is_liked');
      })
      ->findOrFail($item_id);

    $user = Auth::user();

    return view('item', compact('item', 'user'));
  }

  public function toggle(Item $item){
    $user = Auth::user();

    // いいね済みなら解除、未いいねなら追加
    $user->likedItems()->toggle($item->id);

    return back();
  }


  // 出品画面表示
  public function sell(){
    // カテゴリ、コンディションを取得
    $conditions = Condition::all();
    $categories = Category::all();

    return view('sell', [
      'conditions' => $conditions,
      'categories' => $categories,
    ]);
  }


  // 出品
  public function itemStore(ExhibitionRequest $request){
    // カテゴリ、コンディションを取得
    $conditions = Condition::all();
    $categories = Category::all();
    // 出品者を取得
    $user = Auth::user();


    // 入力値
    $data = $request->only(['condition_id', 'name', 'brand', 'description', 'price']);

    // 画像
    if ($request->hasFile('item_img')) {
      $path = $request->file('item_img')->store('items', 'public');
      $data['item_img'] = basename($path);
    }
    // 出品者
    $data['sell_user_id'] = $user->id;

    // カテゴリ（複数値）
    $categoryIds = $request->input('category_ids', []);

    // 保存
    $item = Item::create($data);
    $item->categories()->sync($categoryIds);

    return redirect('/');
  }

  // コメント投稿
  public function comment(CommentRequest $request, Item $item){
    $user = Auth::user();

    $data = $request->only(['comment']);
    $data['item_id'] = $item->id;
    $data['user_id'] = $user->id;

    // 保存後画面をリロード
    Comment::create($data);
    $item->load('comments.user');

    return redirect('/item/' . $item->id);
  }
}
