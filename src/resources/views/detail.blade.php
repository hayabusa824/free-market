
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')

<body>
    <div class="container">
        <!-- 左側：画像 -->
        <div class="image-box">
            <div class="image-box__img">
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">
            </div>
        </div>

        <!-- 右側：詳細 -->
        <div class="details">
            <div class="name">{{ $item->name }}</div>
            <div class="brand">{{ $item->brand ?? 'ブランド情報なし' }}</div>
            <div class="price">
                <span class="price-symbol">¥</span>
                {{ number_format($item->price) }}
                <span class="price-tax">（税込）</span>
            </div>

            <div class="info-img">
                @if(Auth::check())
                    <button id="like-button" data-id="{{ $item->id }}" class="like-button">
                        <img src="{{ $isLiked ? asset('img/image copy 2.png') : asset('img/image copy.png') }}" id="like-icon" class="info-img__img">
                    </button>
                @else
                    <button id="like-button" data-id="{{ $item->id }}" class="like-button">
                        <img src="{{ $isLiked ? asset('img/image copy 2.png') : asset('img/image copy.png') }}" id="like-icon" class="info-img__img">
                    </button>
                @endif
                <img src="{{ asset('img/image.png') }}" class="info-img__img">
            </div>
            <div class="info-text">
                <div class="info-text__txt" id="like-count">{{ $item->like_count }}</div>
                <div class="info-text__txt"> {{ $item->comment_count }}</div>
            </div>

            <div class="purchase-info">
                <a href="{{ route('purchase.show', ['item' => $item->id]) }}" class="button" class="purchase-info__txt">購入手続きへ</a>
            </div>

            <div class="section-1">
                <div class="section__title">商品説明</div>
                <div class="section__content">{!! nl2br(e($item->description)) !!}</div>
            </div>

            <div class="section-2">
                <div class="section__title">商品の情報</div>
                <div class="tags">
                    <div class=tag-title>カテゴリー</div>
                    @if (!empty($item->category))
                        @foreach (explode(',', trim($item->category, '[]')) as $category)
                            <div class="tag">{{ trim($category, '"') }}</div>
                        @endforeach
                    @endif
                </div>
                <div class="tag-title-2">商品の状態
                    <span class="tag-2">{{ $item->condition }}</span>
                </div>
            </div>

            <div class="section-3">
                    <div class="comment-title">コメント ({{ $item->comments->count() }})</div>


                        <div class="comment-list">
                @php
                    $previousUserId = null; // 前のコメントのユーザーIDを記録
                @endphp
                @forelse ($item->comments as $index => $comment)
                    @if ($comment->user_id !== $previousUserId)
                        <!-- 新しいユーザーの場合、アイコンと名前を表示 -->
                        <div class="card {{ $index >= 2 ? 'hidden-comment' : '' }}">
                            <div class="user-info">
                                <div class="user-icon">
                                    @if (!empty($comment->address->profile_image))
                                        <img src="{{ asset('storage/' . $comment->address->profile_image) }}" class="user-icon__img">
                                    @endif
                                </div>
                                <span class="user-name">{{ $comment->address->name ?? '匿名ユーザー' }}</span>
                            </div>
                            <div class="comment-card">
                                <div class="comment-body">
                                    <div>{{ $comment->comment }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- 同じユーザーの場合、アイコンと名前を表示せずコメントのみ -->
                        <div class="card {{ $index >= 2 ? 'hidden-comment' : '' }}">
                            <div class="comment-card">
                                <div class="comment-body">
                                    <div>{{ $comment->comment }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @php
                        $previousUserId = $comment->user_id; // 現在のコメントのユーザーIDを記録
                    @endphp
                @empty
                    <div class="comment">
                        <div>コメントはありません</div>
                    </div>
                @endforelse
            </div>

                    @if ($item->comments->count() > 2)
                        <button id="show-more-comments" class="show-more-btn">もっと見る</button>
                    @endif

                <div class="content">商品へのコメント</div>
                <form action="{{ route('comments.store', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    @error('comment')
                        <div class="form_error">
                            {{ $message }}
                        </div>
                    @enderror
                        <textarea class="content-area" name="comment"></textarea>
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button type="submit" class="submit-btn">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</body>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ボタンのクリックイベントを設定
    $('#like-button').on('click', function () {
        const itemId = $(this).data('id'); // ボタンの data-id 属性から itemId を取得
        const likeIcon = $('#like-icon'); // 画像要素を取得

        // サーバーにリクエストを送信
        $.ajax({
            type: 'POST',
            url: `/item/${itemId}/like`, // itemId を URL に含める
            success: function (response) {
                console.log('いいね成功');

                // カウントを更新
                $('#like-count').text(response.like_count);

                // 画像を切り替え
                if (response.liked) {
                    likeIcon.attr('src', "{{ asset('img/image copy 2.png') }}");
                } else {
                    likeIcon.attr('src', "{{ asset('img/image copy.png') }}");
                }
            },
            error: function (xhr) {
                console.log('エラー:', xhr.responseText);
            }
        });
    });
    // コメントの表示/非表示を切り替える
        document.getElementById('show-more-comments')?.addEventListener('click', function () {
            const hiddenComments = document.querySelectorAll('.hidden-comment');
            hiddenComments.forEach(comment => {
                comment.classList.remove('hidden-comment'); // 非表示クラスを削除
            });
            this.style.display = 'none'; // ボタンを非表示にする
        });
</script>

@endsection