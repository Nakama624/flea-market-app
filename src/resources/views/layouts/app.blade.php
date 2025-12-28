<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flea Market</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
  @yield('css')

</head>
<body>
  <header class="header">
    <!-- ロゴ -->
    <div class="header-left">
      <a href="/">
        <img src="{{ asset('images/COACHTECH.png') }}" alt="COACHTECH" class="header-left__logo">
      </a>
    </div>
    <!-- "ログイン"または"会員登録"画面の場合は、検索＆ボタンは表示しない -->
    @if (request()->path() == 'login' || request()->path() == 'register')
      <div class="header__btn">
      </div>
    @else
      <!-- 検索 -->
      <div class="header-center">
        <form method="GET" action="/">
          <input type="hidden" name="tab" value="mylist">
          <input class="search__input" type="text" name="keyword" placeholder="なにをお探しですか?" value="{{ $keyword ?? '' }}">
        </form>
      </div>
      <!-- ボタン群 -->
      <div class="header-right">
        @if (Auth::check())
          <div class="header-nav__item">
            <form action="/logout" method="post">
              @csrf
              <button class="header-nav__button">ログアウト</button>
            </form>
          </div>
        @else
          <div class="header-nav__item">
            <a href="/login" class="header-nav__button">ログイン</a>
          </div>
        @endif
        <div class="header-nav__item">
          <a class="header-nav__button" href="/mypage?page=sell">マイページ</a>
        </div>
        <div class="header-nav__item">
          <a href="/sell" class="header__btn--sell">出品</a>
        </div>
      </div>
    @endif
  </header>
  <main>
    @yield('content')
  </main>
</body>
</html>
