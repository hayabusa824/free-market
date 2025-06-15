<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{


    public function like($id)
    {
        return response()->json(['message' => 'Liked item with ID ' . $id]);
    }

        public function toggle($id)
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        // 既存の「いいね」を確認
        $like = Like::where('item_id', $id)
                    ->where(function ($query) use ($sessionId, $userId) {
                        $query->where('session_id', $sessionId)
                                ->orWhere('user_id', $userId);
                    })
                    ->first();

        if ($like) {
            // 「いいね」を削除
            $like->delete();

            // 商品のいいね数をデクリメント
            $item = Item::find($id);
            $item->decrement('like_count');

            $likeCount = $item->like_count;

            return response()->json(['liked' => false, 'like_count' => $likeCount]);
        } else {
            // 新しい「いいね」を作成
            Like::create([
                'item_id' => $id,
                'session_id' => $sessionId,
                'user_id' => $userId,
            ]);

            // 商品のいいね数をインクリメント
            $item = Item::find($id);
            $item->increment('like_count');

            $likeCount = $item->like_count;

            return response()->json(['liked' => true, 'like_count' => $likeCount]);
        }
    }

}