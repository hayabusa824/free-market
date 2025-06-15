@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

    <main>
        <div class="main-title">
            <p>プロフィール設定</p>
        </div>
        <div class="profile-box">
            <form action="{{ $address ? route('profile.update') : route('profile.store') }}" method="POST" class="address-form" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="form-img">
                        <div class="form-img__img">
                            @if (!empty($address->profile_image))
                                <img src="{{ asset('storage/' . $address->profile_image) }}"  class="form-img__photo">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}"  class="form-img__photo">
                            @endif
                        </div>
                        <div class="form-img__button">
                            <label for="profile_image" class="form-img__button-text">画像を選択する</label>
                            <input type="file" id="profile_image" name="profile_image" class="form-input" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-text">
                        <label for="name">ユーザー名</label>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $address->name ?? '') }}" class="form-input">
                    @error('name')
                    <div class="form_error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-text">
                        <label for="tel">郵便番号</label>
                    </div>
                    <input type="text" name="tel" value="{{ old('tel', $address->tel ?? '') }}" class="form-input">
                    @error('tel')
                    <div class="form_error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-text">
                        <label for="address">住所</label>
                    </div>
                    <input type="text" name="address" value="{{ old('address', $address->address ?? '') }}" class="form-input">
                    @error('address')
                    <div class="form_error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-text">
                        <label for="building">建物名</label>
                    </div>
                    <input type="text" name="building" value="{{ old('building', $address->building ?? '') }}" class="form-input">
                </div>

                <div class="register-button">
                    <button type="submit" class="register-button__text">更新する</button>
                </div>
            </form>
        </div>
    </main>


    </html>


    <script>
                document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.querySelector('.form-img__img img'); // セレクタを修正
                    previewImg.src = e.target.result; // ファイルを読み込んだ結果をimgタグにセット
                }
                reader.readAsDataURL(file);
            }
        });
</script>
@endsection