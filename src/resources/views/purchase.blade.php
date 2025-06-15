@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

<main class="main">
    <div class="container">
        <section class="product-info">
            <div class="product-image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="product-image__img">
            </div>
            <div class="product-details">
                <div class="detail">{{ $item->name }}</div>
                <p class="price"><span class="price-symbol">¥</span> {{ number_format($item->price) }}</p>
            </div>
        </section>

        <form action="{{ route('purchase.store', ['item' => $item->id]) }}" method="POST">
            @csrf
        <section class="payment-method">
            <div class="select">支払い方法</div>
            <select id="payment-method-select" name="payment_method"  class="payment-method__select">
                <option selected disabled>選択してください</option>
                <option value="コンビニ払い">コンビニ払い</option>
                <option value="カード払い">カード払い</option>
            </select>
            @error('payment_method')
                    <div class="form_error">
                        {{ $message }}
                    </div>
            @enderror
        </section>

        <section class="shipping-address">
            <div class="select">
                <div class="select__title">配送先</div>
                <a href="{{ route('purchase.editAddress', ['item' => $item->id]) }}" class="change-link">変更する</a>
            </div>
            <div class="shipping-address__details">
            @if ($address)
                <p>〒 {{ $address->tel ?? '未設定' }}<br>{{ $address->address ?? '未設定' }} {{ $address->building ?? '' }}</p>
            @endif
            </div>
            @error('address')
                <div class="form_error">
                    {{ $message }}
                </div>
            @enderror
        </section>
    </div>

    <div class="container-2">
        <table class="summary">
            <tr class="summary-box">
                <td class="box-1">商品代金</td>
                <td class="box-2"><span class="price-symbol">¥</span> {{ number_format($item->price) }}</td>
            </tr>
            <tr class="summary-box">
                <td class="box-1">支払い方法</td>
                <td class="box-2" id="selected-payment-method"></td>
            </tr>
        </table>


            <button type="submit" class="purchase-button">購入する</button>
        </form>
    </div>
</main>

<script>
    // 支払い方法の選択をリアルタイムで反映
    document.getElementById('payment-method-select').addEventListener('change', function () {
        const selectedMethod = this.value;
        document.getElementById('selected-payment-method').textContent = selectedMethod;
    });
</script>

@endsection