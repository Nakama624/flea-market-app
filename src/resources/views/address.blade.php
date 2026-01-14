@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="content">
  <form action="/purchase/address/{{ $item->id }}"  class="address-form" method="post">
    @csrf
    <h1 class="address-form__title">住所の変更</h1>
    <!-- 郵便番号 -->
    <div class="address-form__group">
      <label for="delivery_postcode" class="address-form__label">郵便番号</label>
      <input id="delivery_postcode" type="text" name="delivery_postcode" class="address-form__input"
        value="{{ old('delivery_postcode') }}" />
      <div class="address-form__error">
        @error('delivery_postcode')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 住所 -->
    <div class="address-form__group">
      <label for="delivery_address" class="address-form__label">住所</label>
      <input id="delivery_address" type="text" name="delivery_address" class="address-form__input" value="{{ old('delivery_address') }}" />
      <div class="address-form__error">
        @error('delivery_address')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 建物 -->
    <div class="address-form__group">
      <label for="delivery_building" class="address-form__label">建物名</label>
      <input id="delivery_building" type="text" name="delivery_building" class="address-form__input" value="{{ old('delivery_building') }}" />
    </div>
    <!-- ボタン -->
    <div class="address-form__actions">
      <button class="address-form__submit" type="submit">変更する</button>
    </div>
  </form>
</div>
@endsection
