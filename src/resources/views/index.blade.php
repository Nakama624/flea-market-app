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
    @foreach ($items as $item)
    <div class="item-group__row">
      <!-- 商品画像 -->
      <a href="/item/{{ $item->id }}" class="item-group__img">
        <img class="item-group__img-inner" src="{{ asset('storage/items/' . $item->item_img) }}" alt="商品画像" />
      </a>
      <!-- 商品名 -->
      <div class="item-group__name-sold">
        <p class="item-group__name">{{ $item->name }}</p>
        @if(in_array($item->id, $soldItemIds))
          <p class="item-group__sold">Sold</p>
        @endif
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection