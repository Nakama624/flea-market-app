@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="content">
  <div class="form-change">
    <a class="form-change__recommend {{ request('tab') !== 'mylist' ? 'active' : '' }}" href="/">
    おすすめ</a>
    <a class="form-change__mylist {{ request('tab') === 'mylist' ? 'active' : '' }}" href="/?tab=mylist">
    マイリスト</a>
  </div>
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