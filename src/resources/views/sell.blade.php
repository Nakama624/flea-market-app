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
      <label class="form__label--item">商品画像</label>
      <div class="profile-thumb">
        @if (!empty($item?->item_img))
          <img id="itemPreview" src="{{ !empty($item?->item_img) ? asset('storage/' . $item->item_img) : '' }}" alt="商品画像" >
        @else
          <img id="itemPreview" src="" alt="" style="display:none;">
        @endif
        <label for="item_img" class="item-img__btn">
          画像を選択する
        </label>
        <!-- 画像が選択されたらすぐに表示 -->
        <input id="item_img" type="file" name="item_img" accept="image/*" class="profile-file" 
          onchange="
            const img = document.getElementById('itemPreview');
            img.src = window.URL.createObjectURL(this.files[0]);
            img.style.display = 'block';">
      </div>
      <div class="form__error">
        @error('item_img')
          {{ $message }}
        @enderror
      </div>
    </div>

    <h2 class="content-subtitle">商品の詳細</h2>
    <!-- カテゴリー -->
    <div class="form-input">
      <span class="form__label--item">カテゴリー</span>
      <div class="category-wrapper">
        @foreach ($categories as $category)
          <label class="category-item">
            <input
              type="checkbox"
              name="category_ids[]"
              value="{{ $category->id }}"
              {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
            {{ $category->category_name }}
          </label>
        @endforeach
      </div>
      <div class="form__error">
        @error('category_ids')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 商品の状態 -->
    <div class="form-input">
      <label for="condition_id" class="form__label--item">商品の状態</label>
      <select id="condition_id" name="condition_id" class="form__input--item">
        @foreach ($conditions as $condition)
        <option
          value="{{ $condition->id }}"
          {{ (string)old('condition_id') === (string)$condition->id ? 'selected' : '' }}>
          {{ $condition->condition_name }}
        </option>
        @endforeach
      </select>
      <div class="form__error">
        @error('condition_id')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 商品名 -->
    <div class="form-input">
      <label for="name" class="form__label--item">商品名</label>
      <input id="name" type="text" name="name" class="form__input--item" value="{{ old('name') }}"/>
      <div class="form__error">
        @error('name')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- ブランド名 -->
    <div class="form-input">
      <label for="brand" class="form__label--item">ブランド名</label>
      <input id="brand" type="text" name="brand" class="form__input--item" value="{{ old('brand') }}"/>
    </div>
    <!-- 商品の説明 -->
    <div class="form-input">
      <label for="description" class="form__label--item">商品の説明</label>
      <textarea id="description" name="description" class="form__textarea--item">{{ old('description') }}</textarea>
      <div class="form__error">
        @error('description')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 販売価格 -->
    <div class="form-input">
      <label for="price" class="form__label--item">販売価格</label>
      <div class="price-input">
        <span class="yen">￥</span>
        <input id="price" type="text" name="price" class="form__input--price" value="{{ old('price') }}"/>
      </div>
      <div class="form__error">
        @error('price')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">出品する</button>
    </div>
  </form>
</div>
@endsection