<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

          <div class="header__search">
            <div>
              <form action="{{ route('search') }}" method="GET">
                @csrf
                <input type="hidden" name="page" value="{{ request()->query('page', 'home') }}">
                <input type="text" name="query" class='search' placeholder="なにをお探しですか?" value="{{ request()->input('query') }}">
              </form>
            </div>
          </div>

          <nav class="nav">
            @auth
              <div class="nav-item">
                <form action="/logout" method="POST">
            @csrf
                  <button class="nav-item__button" >ログアウト</button>
                </form>
              </div>
            @else
              <div class="nav-item">
                <a href="/login" class="nav-item__button">ログイン</a>
              </div>
                @endauth
              <div class="nav-item-2">
                <a href="/mypage" class="nav-item__button">マイページ</a>
              </div>
              <div class="nav-item-3">
                <a href="/sell" class="nav-item__button2">出品</a>
              </div>
          </nav>
        </div>
    </header>

    <main>
    @yield('content')
    </main>
</body>

</html>
