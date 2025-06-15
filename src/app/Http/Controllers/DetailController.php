<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Address;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function show(Item $item)
    {
        $user = User::with('address')->findOrFail($item->user_id);

        $address = auth()->user()->address ?? null; // ログインユーザーのアドレス情報を取得

        $id = $item->id;
        $item = Item::with(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc'); // コメントを新しい順にソート
        }, 'comments.user'])->findOrFail($id); // 商品とコメントを取得
        $sessionId = Session::getId();

        $isLiked = Like::where('item_id', $id)
                        ->where('session_id', $sessionId)
                        ->exists();

        $likeCount = Like::where('item_id', $id)->count();

        return view('detail', compact('item', 'isLiked', 'likeCount', 'user', 'address'));
    }

    public function store(Request $request)
    {
        $item = Item::find($request->item_id);
        $item->increment('like_count');
        return response()->json(['success' => true]);
    }
}
