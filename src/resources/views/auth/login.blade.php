@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<main>
    <div class="register-box">
    <h2 class="main-title">ログイン</h2>
    <form action="/login" method="POST" class="register-form">
        @csrf
        <div class="form-group">
            <div class="form-text">
                <label  for="email">メールアドレス</label>
            </div>
            <input type="email"  name="email" value="{{ old('email') }}" class="form-input">
            @error('email')
            <div class="form_error">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-text">
                <label for="password">パスワード</label>
            </div>
            <input type="password"  name="password" value="{{ old('password') }}"  class="form-input">
            @error('password')
            <div class="form_error">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="register-button">
        <button type="submit" class="register-button__text" >ログインする</button>
        </div>

    </form>

        <div class="register-link">
            <a href="/register" class="register-link__text--link">会員登録はこちら</a>
        </div>
</main>


</html>
@endsection