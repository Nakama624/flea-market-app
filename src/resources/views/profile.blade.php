@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="content">
  <form action="/mypage/profile" class="profile-form" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <h1 class="content-title">プロフィール設定</h1>
    <!-- プロフィール画像 -->
    <div class="form-input profile-box">
      <div class="profile-thumb">
        @if ($user->profile_img)
          <img id="profilePreview" src="{{ asset('storage/profiles/' . $user->profile_img) }}" alt="" />
        @else
          <img id="profilePreview" src="" alt="" />
        @endif
      </div>
      <label for="profile_img" class="profile-btn">
        画像を選択する
      </label>
      <!-- 画像が選択されたらすぐに表示 -->
      <input id="profile_img" type="file" name="profile_img" accept="image/*" class="profile-file" onchange="document.getElementById('profilePreview').src = window.URL.createObjectURL(this.files[0])">
    </div>
    <div class="form__error">
      @error('profile_img')
        {{ $message }}
      @enderror
    </div>
    <!-- ユーザー名 -->
    <div class="form-input">
      <span class="form__label--item">ユーザー名</span>
      <input type="text" name="name" class="form__input--item" value="{{ old('name', $user->name) }}" />
      <div class="form__error">
        @error('name')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 郵便番号 -->
    <div class="form-input">
      <span class="form__label--item">郵便番号</span>
      <input type="text" name="postcode" class="form__input--item" value="{{ old('postcode', $user->postcode) }}" />
      <div class="form__error">
        @error('postcode')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 住所 -->
    <div class="form-input">
      <span class="form__label--item">住所</span>
      <input type="text" name="address" class="form__input--item" value="{{ old('address', $user->address) }}" />
      <div class="form__error">
        @error('address')
          {{ $message }}
        @enderror
      </div>
    </div>
    <!-- 建物 -->
    <div class="form-input">
      <span class="form__label--item">建物</span>
      <input type="text" name="building" class="form__input--item" value="{{ old('building', $user->building) }}" />
    </div>
    <!-- ボタン -->
    <div class="form__button">
      <button class="form__button-submit" type="submit">更新する</button>
    </div>
  </form>
</div>
@endsection