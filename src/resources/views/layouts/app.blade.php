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
    <div class="header__inner">
      <a class="header__logo" href="/">XXXXX</a>
    </div>
  </header>
  <main>
    @yield('content')
  </maim>
</body>
</html>
