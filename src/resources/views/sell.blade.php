@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="content">
  <form action="/sell" class="sell-form" method="POST" enctype="multipart/form-data">
    @csrf
    <h1 class="content-title">商品の出品</h1>
    <!-- 商品画像 -->
    <div class="form-input">
      <span class="form__label--item">商品画像</span>
      <div class="profile-thumb">
        <img
          id="itemPreview"
          src="{{ !empty($item?->item_img) ? asset('storage/' . $item->item_img) : '' }}"
          alt="" >
        <label for="item_img" class="item-img__btn">
          画像を選択する
        </label>
              <!-- 画像が選択されたらすぐに表示 -->
      <input id="item_img" type="file" name="item_img" accept="image/*" class="profile-file" onchange="document.getElementById('itemPreview').src = window.URL.createObjectURL(this.files[0])">
      </div>
    </div>

    <h2 class="content-subtitle">商品の詳細</h2>
    <!-- カテゴリー -->
    <div class="form-input">
      <span class="form__label--item">カテゴリー</span>
      <div class="category-wrapper">
        @foreach ($categories as $category)
        <label class="category-item">
          <input type="checkbox" name="category_ids[]" value="{{ $category->id }}">
          {{ $category->category_name }}
        </label>
        @endforeach
      </div>
    </div>
    <!-- 商品の状態 -->
    <div class="form-input">
      <span class="form__label--item">商品の状態</span>
      <select name="condition_id" class="form__input--item">
        @foreach ($conditions as $condition)
        <option value="{{ $condition->id }}">
          {{ $condition->condition_name }}
        </option>
        @endforeach
      </select>
    </div>
    <!-- 商品名 -->
    <div class="form-input">
      <span class="form__label--item">商品名</span>
      <input type="text" name="name" class="form__input--item"/>
    </div>
    <!-- ブランド名 -->
    <div class="form-input">
      <span class="form__label--item">ブランド名</span>
      <input type="text" name="brand" class="form__input--item"/>
    </div>
    <!-- 商品の説明 -->
    <div class="form-input">
      <span class="form__label--item">商品の説明</span>
      <textarea name="description" class="form__textarea--item">{{ old('description') }}</textarea>
    </div>
    <!-- 販売価格 -->
    <div class="form-input">
      <span class="form__label--item">販売価格</span>
      <input type="text" name="price" class="form__input--item"/>
    </div>
    <!-- ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">出品する</button>
    </div>
  </form>
</div>
@endsection