@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form class="content" action="/purchase/{{ $item->id }}" method="post">
  @csrf
  <!-- 商品画像(左) -->
  <div class="left">
    <!-- 一段目 -->
    <div class="left-row">
      <div class="item-img__inner">
        <img class="item-img" src="{{ asset('storage/items/' . $item->item_img) }}" alt="商品画像" />
      </div>
      <div class="left-row__name-price">
        <h1 class="item-name">{{ $item->name }}</h1>
        <p class="item-price">￥ {{ number_format($item->price) }}</p>
      </div>
    </div>
    <!-- 二段目 -->
    <div class="left-row2">
      <!-- livewire/payment-select.blade.phpで支払方法セレクトボックスとエラーを設定 -->
      @livewire('payment-select', ['payments' => $payments])
    </div>
    <!-- 三段目 -->
    <div class="left-row3">
      <div class="left-row__ttl-btn">
        <p class="left-row__ttl">配送先</p>
        <a class="left-row__btn" href="/purchase/address/{{ $item->id }}">変更する</a>
      </div>
      <div class="left-row__delivery-group">
        <p class="delivery__postcode">〒{{ old('delivery_postcode', $user->postcode) }}</p>
        <p class="delivery__address-building">
          {{ old('delivery_address', $user->address) }}
          {{ old('delivery_building', $user->building) }}
        </p>
        <!-- 送信用 -->
          <input type="hidden" name="delivery_postcode" value="{{ old('delivery_postcode', $user->postcode) }}">
          <input type="hidden" name="delivery_address" value="{{ old('delivery_address', $user->address) }}">
          <input type="hidden" name="delivery_building" value="{{ old('delivery_building', $user->building) }}">
      </div>
      <div class="form__error">
        @foreach (array_slice($errors->all(), 1) as $error)
          <li>{{ $error }}</li>
        @endforeach
      </div>
    </div>
  </div>

  <!-- 商品詳細(右) -->
  <div class="right">
    <table class="payment-table">
      <tr class="payment-table__row">
        <td class="payment-table__price">商品代金</td>
        <td class="payment-table__price">￥ {{ number_format($item->price) }}</td>
      </tr>
      <tr class="payment-table__row">
        <td class="payment-table__price">支払い方法</td>
        <td class="payment-table__price">
          <!-- livewire/payment-display.blade.phpで支払方法表示を連動 -->
          @livewire('payment-display')
        </td>
      </tr>
    </table>
    <!-- ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">購入する</button>
    </div>
  </div>
</form>
@endsection