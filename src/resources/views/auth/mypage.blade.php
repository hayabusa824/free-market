@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<main>
    <form >
        <div class="mypage__user">
            <div class="mypage__user__img">
                @if (!empty($address) && !empty($address->profile_image))
                    <img src="{{ asset('storage/' . $address->profile_image) }}" class="mypage__user__img__photo">
                @else
                @endif
            </div>
            <div class="mypage__user__name">
                <p class="mypage__user__name__text">{{ $address->name }}</p>
            </div>
            <div class="mypage__user__edit">
                <a href="/mypage/profile" class="mypage__user__edit__link">プロフィールを編集</a>
            </div>
        </div>
        <div class="list">
            <div class="list__buy">
                <a href="/mypage?tab=sell" class="list__link {{ request()->query('tab') === 'sell' ? 'active' : '' }}">出品した商品</a>
            </div>
            <div class="list__sell">
                <a href="/mypage?tab=buy" class="list__link {{ request()->query('tab') === 'buy' ? 'active' : '' }}">購入した商品</a>
            </div>
        </div>
    </form>

        <div class="item-grid">
            @foreach($items as $item)
            <div class="item-card">
                <a href="{{ route('detail', $item->id) }}" class="item-card-link">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    <div class="item-card-name">{{ $item->name }}</div>
                </a>
            </div>
            @endforeach
        </div>
</main>


</html>
@endsection