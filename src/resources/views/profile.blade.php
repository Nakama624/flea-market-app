@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="content">

  <!-- プロフィール画像 -->
  <div class="profile-box">
    <div class="profile-thumb">
      <img id="profilePreview" src="" alt="" />
    </div>
    <label for="profile_image" class="profile-name">
      ユーザー名
    </label>
    <label for="profile_image" class="profile-btn">
      画像を選択する
    </label>
    <input id="profile_image" type="file" name="profile_image" accept="image/*" class="profile-file">
  </div>
  <!-- タブ -->
  <div class="form-change">
    <a class="form-change__sell {{ request('tab') !== 'mylist' ? 'active' : '' }}" href="/mypage?page=sale">
    出品した商品</a>
    <a class="form-change__buy {{ request('tab') === 'mylist' ? 'active' : '' }}" href="/mypage?page=buy">
    購入した商品</a>
  </div>
  <!-- 商品 -->
  <div class="item-group">
    <div class="item-group__row">
      <!-- <img id="profilePreview" src="" alt="商品画像"> -->
      <input type="text" class="item-group__img" value="商品画像">
      <input type="text" class="item-group__name" value="商品名">
    </div>
    <div class="item-group__row">
      <!-- <img id="profilePreview" src="" alt="商品画像"> -->
      <input type="text" class="item-group__img" value="商品画像">
      <input type="text" class="item-group__name" value="商品名">
    </div>
    <div class="item-group__row">
      <!-- <img id="profilePreview" src="" alt="商品画像"> -->
      <input type="text" class="item-group__img" value="商品画像">
      <input type="text" class="item-group__name" value="商品名">
    </div>
    <div class="item-group__row">
      <!-- <img id="profilePreview" src="" alt="商品画像"> -->
      <input type="text" class="item-group__img" value="商品画像">
      <input type="text" class="item-group__name" value="商品名">
    </div>
  </div>

</div>
@endsection