<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $address = $user->address ?? null;
        $tab = $request->query('tab', 'sell');

        if ($tab === 'sell') {
            $items = $user->sellingItems()->get();
        } elseif ($tab === 'buy') {
            $items = Item::where('buyer_id', $user->id)->get();
        } else {
            $items = collect(); // 空のコレクション
        }


        return view('auth.mypage', compact('user', 'items', 'tab', 'address'));
    }
}