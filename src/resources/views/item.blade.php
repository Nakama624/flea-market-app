@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="content">

  <!-- 商品画像 -->
  <div class="item-box">
      <img id="item-box__img" src="" alt="" />
  </div>




  <div class="content-right">
    <!-- 商品名 -->
    <div class="item-name">
      <input class="item-name__input" value="" name="name" readonly />
    </div>
    <!-- ブランド名 -->
    <div class="item-brand">
      <input class="item-brand__input" value="" name="name" readonly />
    </div>
    <!-- 販売価格 -->
    <div class="item-price">
      <input class="item-price__input" value="" name="name" readonly />
    </div>

    <!-- アイコン1 -->
    <!-- アイコン2 -->
    <!-- 購入手続きへ　ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">購入手続きへ</button>
    </div>
    <h2 class="item-describe">商品説明</h2>
    <!-- カラー -->
    <div class="item-color">
      <input class="item-color__input" value="" name="name" readonly />
    </div>    



    <!-- 商品画像 -->
    <div class="form-input">
      <span class="form__label--item">商品画像</span>
      <input type="name" name="item-img" class="form__input--item-img"
    value="{{ old('item-img') }}" />
    </div>

    <h2 class="content-subtitle">商品の詳細</h2>
    <!-- カテゴリー -->
    <div class="form-input">
      <span class="form__label--item">カテゴリー</span>
      <div class="category-wrapper">
        <label class="category-item">
          <input type="radio" name="category" value="ファッション">
          ファッション
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="家電">
          家電
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="インテリア">
          インテリア
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="レディース">
          レディース
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="メンズ">
          メンズ
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="コスメ">
          コスメ
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="本">
          本
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="ゲーム">
          ゲーム
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="スポーツ">
          スポーツ
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="キッチン">
          キッチン
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="ハンドメイド">
          ハンドメイド
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="アクセサリー">
          アクセサリー
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="おもちゃ">
          おもちゃ
        </label>

        <label class="category-item">
          <input type="radio" name="category" value="ベビー・キッズ">
          ベビー・キッズ
        </label>
      </div>
    </div>
    <!-- 商品の状態 -->
    <div class="form-input">
      <span class="form__label--item">商品の状態</span>
      <select name="condition" class="form__input--item">
        <option value="良好">良好</option>
        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
        <option value="状態が悪い">状態が悪い</option>
      </select>
    </div>
    <!-- 商品名 -->
    <div class="form-input">
      <span class="form__label--item">商品名</span>
      <input type="name" name="name" class="form__input--item"/>
    </div>
    <!-- ブランド名 -->
    <div class="form-input">
      <span class="form__label--item">ブランド名</span>
      <input type="name" name="name" class="form__input--item"/>
    </div>
    <!-- 商品の説明 -->
    <div class="form-input">
      <span class="form__label--item">商品の説明</span>
      <textarea name="describe" class="form__textarea--item">{{ old('describe') }}</textarea>
    </div>
    <!-- 販売価格 -->
    <div class="form-input">
      <span class="form__label--item">販売価格</span>
      <input type="text" name="name" class="form__input--item"/>
    </div>
    <!-- ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">出品する</button>
    </div>
  </div>
</div>
@endsection