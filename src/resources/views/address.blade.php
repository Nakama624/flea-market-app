@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="content">
  <form action="/purchase/address/{{ $item->id }}"  class="login-form" method="post">
    @csrf
    <h1 class="content-title">住所の変更</h1>
    <!-- 郵便番号 -->
    <div class="form-input">
      <span class="form__label--item">郵便番号</span>
      <input type="text" name="delivery_postcode" class="form__input--item"
    value="{{ old('delivery_postcode') }}" />
      <div class="form__error">
      @error('delivery_postcode')
        {{ $message }}
      @enderror
    </div>
    </div>
    <!-- 住所 -->
    <div class="form-input">
      <span class="form__label--item">住所</span>
      <input type="text" name="delivery_address" class="form__input--item" value="{{ old('delivery_address') }}" />
      <div class="form__error">
        @error('delivery_address')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 建物 -->
    <div class="form-input">
      <span class="form__label--item">建物</span>
      <input type="text" name="delivery_building" class="form__input--item" value="{{ old('delivery_building') }}" />
    </div>
    <!-- ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">変更する</button>
    </div>
  </form>
</div>
@endsection