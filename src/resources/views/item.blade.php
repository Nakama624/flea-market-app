@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="content">
  <!-- 商品画像(左) -->
  <div class="left">
    <img class="item-img" src="{{ asset('storage/items/' . $item->item_img) }}" alt="商品画像" />
  </div>

  <!-- 商品詳細(右) -->
  <div class="right">
    <h1 class="item-name">{{ $item->name }}</h1>
    <p class="item-brand">{{ $item->brand }}</p>
    <p class="item-price">¥<span class="item-price__num">{{ $item->price }}</span>（税込）</p>

    <!-- アイコン -->
    <div class="icon-group">
      <div class="icon__likes">
        <form action="/items/{{ $item->id }}/like" method="POST">
          @csrf
          <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">
            @if($item->is_liked) <!-- いいね押下 -->
              <img class="icon" src="{{ asset('images/likes_pink.png') }}" alt="いいね" />
            @else <!-- いいね未押下 -->
              <img class="icon" src="{{ asset('images/likes_default.png') }}" alt="いいねデフォルト" />
            @endif
          </button>
        </form>
        <!-- カウント -->
        <p>{{ $item->liked_users_count }}</p>
      </div>
      <div class="icon__comment">
        <img class="icon" src="{{ asset('images/comment.png') }}" alt="コメント" />
        <p class="icon__count">{{ $item->comments->count() }}</p>
      </div>
    </div>

    <div class="form__button">
      <a class="form__button-submit" href="/purchase/{{ $item->id }}">購入手続きへ</a>
    </div>

    <div class="sub-content">
      <h2 class="item-describe">商品説明</h2>
      <p class="item-description">{{ $item->description }}</p>
    </div>

    <div class="sub-content">
      <h2 class="item-info">商品の情報</h2>
      <div class="item-info__group">
        <p class="item-info__title">カテゴリー</p>
        <div class="category-wrapper">
          @foreach ($item->categories as $category)
          <p class="item-categories">{{ $category->category_name }}</p>
          @endforeach
        </div>
      </div>
      <div class="item-info__group">
        <p class="item-info__title">商品の状態</p>
        <p class="item-condition">{{ $item->condition->condition_name }}</p>
      </div>
    </div>

    <div class="sub-content">
      <h3 class="item-comment">コメント({{ $item->comments->count() }})</h3>

      <!-- プロフィール画像 -->
      @foreach ($item->comments as $comment)
      <!-- コメントの数を繰り返す -->
      <div class="profile-box">
        <div class="profile-thumb">
          <img id="profilePreview" src="{{ asset('storage/' . $user->profile_img) }}" alt="" />
        </div>
        <!-- ユーザー名 -->
        <span class="profile-name">
          {{ $comment->user->name }}
        </span>
      </div>
      <!-- コメント表示 -->
      <p class="comments">{{ $comment->comment }}</p>
      @endforeach

      <!-- コメント入力 -->
      <form action="/item/{{ $item->id }}" class="form-comment" method="post">
        @csrf
        <p class="form-comment__title">商品へのコメント</p>
        <textarea class="form-comment__textarea" name="comment">{{ old('comment') }}</textarea>
        <!-- ボタン -->
        <div class="form__button">
          <button class="form__button-submit" type="submit">コメントを送信する</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection