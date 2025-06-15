<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>フリーマーケット</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
          <div class="header__link">
            <a  href="/">
            <img src="{{ asset('img/logo copy.svg') }}"  class="header__link--img"/>
          </a>
        </div>
    </header>

    <main>
    @yield('content')
    </main>
</body>

</html>