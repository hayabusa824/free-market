<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'home');
        $query = $request->input('query'); // 検索キーワードを取得

        if ($query) {
            // 検索クエリが存在する場合は検索結果を取得
            $items = Item::where('name', 'LIKE', '%' . $query . '%')->with('user')->get();
        } elseif ($page === 'mylist') {
            // マイリストの場合はユーザーの「いいね」したアイテムを取得
            $user = Auth::user();
            if (!$user) {
                return redirect('/login');
            }

            $items = $user->likedItems()->with('user')->get();
        } else {
            // ホーム画面の場合は自分の商品を除外して取得
            $items = Item::with('user')
                ->where('user_id', '!=', Auth::id())
                ->get();
        }

        return view('index', [
            'page' => $page,
            'items' => $items,
            'query' => $query,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // 検索キーワードを取得

        // 商品名で検索
        $items = Item::where('name', 'LIKE', '%' . $query . '%')->get();

        return view('index', compact('items', 'query'));
    }
}
