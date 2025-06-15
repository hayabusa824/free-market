@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')

<div class="listing-form">
    <div class=title>商品の出品</div>

    <form action="/" method="POST" enctype="multipart/form-data" >
    @csrf

        <div class="form-group">
            <label>商品画像</label>
            <div class="form-img__img">
                @if (!empty($item->image))
                    <img src="{{ asset('storage/' . $item->image) }}"  class="form-img__photo">
                @endif
                <div class="form-img__button">
                    <label for="image" class="form-img__button-text">画像を選択する</label>
                    <input type="file" id="image" name="image" class="form-input" style="display: none;" >
                </div>
            </div>
            @error('image')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class=title-2>商品の詳細</div>
                <div class="form-group">
            <label>カテゴリー</label>
                        <div class="category-buttons">
                <button type="button" class="category-btn" data-value="ファッション">ファッション</button>
                <button type="button" class="category-btn" data-value="家電">家電</button>
                <button type="button" class="category-btn" data-value="インテリア">インテリア</button>
                <button type="button" class="category-btn" data-value="レディース">レディース</button>
                <button type="button" class="category-btn" data-value="メンズ">メンズ</button>
                <button type="button" class="category-btn" data-value="コスメ">コスメ</button>
                <button type="button" class="category-btn" data-value="本">本</button>
                <button type="button" class="category-btn" data-value="ゲーム">ゲーム</button>
                <button type="button" class="category-btn" data-value="スポーツ">スポーツ</button>
                <button type="button" class="category-btn" data-value="キッチン">キッチン</button>
                <button type="button" class="category-btn" data-value="ハンドメイド">ハンドメイド</button>
                <button type="button" class="category-btn" data-value="アクセサリー">アクセサリー</button>
                <button type="button" class="category-btn" data-value="おもちゃ">おもちゃ</button>
                <button type="button" class="category-btn" data-value="ベビー・キッズ">ベビー・キッズ</button>
            </div>
            <input type="hidden" name="category[]" id="selected-category" value="{{ json_encode(old('category', [])) }}">
            @error('category')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label>商品の状態</label>
            <select name="condition">
                <option value="" disabled selected>選択してください</option>
                <option value="良好">良好</option>
                <option value="目立つ傷や汚れなし">目立つ傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="状態が悪い">状態が悪い</option>
            </select>
            @error('condition')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class=title-2>商品名と説明</div>

        <div class="form-group">
            <label>商品名</label>
                <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label>ブランド名</label>
            <input type="text" name="brand" value="{{ old('brand') }}">
        </div>

        <div class="form-group">
            <label>商品の説明</label>
            <textarea name="description" rows="4" >{{ old('description') }}</textarea>
            @error('description')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label>販売価格</label>
            <input type="text" name="price" placeholder="¥" value="{{ old('price') }}">
            @error('price')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="submit-btn">出品する</button>
    </form>
</div>

<script>

    document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0]; // 選択されたファイルを取得
    if (file) {
        const reader = new FileReader(); // FileReader を使用して画像を読み込む
        reader.onload = function(e) {
            let previewImg = document.querySelector('.form-img__photo');

            // プレビュー用の <img> タグが存在しない場合は作成
            if (!previewImg) {
                previewImg = document.createElement('img');
                previewImg.classList.add('form-img__photo');
                document.querySelector('.form-img__img').prepend(previewImg);
            }

            // 読み込んだ画像をプレビューに表示
            previewImg.src = e.target.result;
            previewImg.style.display = 'block'; // 非表示の場合は表示

            // ボタンを非表示にする
            if (button) {
                button.style.display = 'none';
            }
        };
        reader.readAsDataURL(file); // ファイルをデータURLとして読み込む
    }
    });

        document.querySelectorAll('.category-btn').forEach(button => {
        button.addEventListener('click', function () {
            const selectedCategoriesInput = document.getElementById('selected-category');
            let selectedCategories = JSON.parse(selectedCategoriesInput.value || '[]');
    
            // カテゴリーが既に選択されている場合は削除、そうでない場合は追加
            const categoryValue = this.getAttribute('data-value');
            if (selectedCategories.includes(categoryValue)) {
                selectedCategories = selectedCategories.filter(category => category !== categoryValue);
                this.classList.remove('selected'); // 選択状態を解除
            } else {
                selectedCategories.push(categoryValue);
                this.classList.add('selected'); // 選択状態を適用
            }
    
            // 更新されたカテゴリー配列を hidden フィールドに反映
            selectedCategoriesInput.value = JSON.stringify(selectedCategories);
        });
    });

    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.querySelector('.form-img__img img');
                previewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

</script>

@endsection


