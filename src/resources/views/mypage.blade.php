@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="content">
  <!-- プロフィール画像 -->
  <div class="profile-box">
    <div class="profile-thumb">
      @if ($user->profile_img)
        <img id="profilePreview" src="{{ asset('storage/' . $user->profile_img) }}" alt="" />
      @else
        <img id="profilePreview" src="" alt="" />
      @endif
    </div>
    <!-- ユーザー名 -->
    <span class="profile-name">
      {{ $user->name }}
    </span>
    <a class="profile-btn" href="/mypage/profile">マイページを編集</a>
  </div>
  <!-- タブ -->
  <div class="form-change">
    <!-- 出品 -->
    <a class="form-change__sell {{ request('page') === 'sell' ? 'active' : '' }}" href="/mypage?page=sell">
      出品した商品
    </a>
    <!-- 購入 -->
    <a class="form-change__buy {{ request('page') === 'buy' ? 'active' : '' }}" href="/mypage?page=buy">
      購入した商品
    </a>
  </div>
  <!-- 商品 -->
  <div class="item-group">
    <!-- 出品商品の場合 -->
    @foreach ($items as $item)
    <div class="item-group__row">
      <!-- 商品画像 -->
      <a href="/item/{{ $item->id }}" class="item-group__img">
        <img class="item-group__img-inner" src="{{ asset('storage/items/' . $item->item_img) }}" alt="商品画像" />
      </a>
      <!-- 商品名 -->
      <div class="item-group__name-sold">
        <p class="item-group__name">{{ $item->name }}</p>
        @if($item->purchase)
          <p class="item-group__sold">SOLD</p>
        @endif
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection