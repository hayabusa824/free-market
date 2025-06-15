<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{
    public function index()
    {
        return view('sell');
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        // 配列型のデータを文字列に変換（例: category）
        if (isset($validated['category']) && is_array($validated['category'])) {
        $validated['category'] = implode(',', $validated['category']);
        }

        // 画像のアップロード処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('item_images', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = auth()->id();

        Item::create($validated);

        return redirect('/');
    }
}
