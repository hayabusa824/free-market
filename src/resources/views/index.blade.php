
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<main>
    <div class="list">
        <div class="list__home">
            <a href="/" class="list__link {{ request()->fullUrl() === url('/') ? 'active' : '' }}">おすすめ</a>
        </div>
        <div class="list__my-list">
            <a href="/?page=mylist" class="list__link {{ request()->query('page') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
    </div>


    <div class="item-grid">
        @forelse($items as $item)
            <div class="item-card">
                <a href="{{ route('detail', $item->id) }}" class="item-card-link">
                    <div class="image-wrapper">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
                        @if ($item->is_sold)
                            <img src="{{ asset('img/image copy 4.png') }}" alt="SOLD OUT" class="sold-out-badge">
                        @endif
                    </div>
                    <div class="item-card-name">{{ $item->name }}</div>
                </a>
            </div>
        @empty
            <p>該当する商品が見つかりませんでした。</p>
        @endforelse
    </div>
</main>

@endsection