<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Address;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\editAddressRequest;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();

        // セッションに保存された住所を取得
        $sessionAddress = session()->get("purchase_address_{$id}");

        if ($sessionAddress) {
            // セッションの住所を仮オブジェクト化
            $address = (object) $sessionAddress;
        } else {
            // デフォルトの住所を取得
            $address = Address::where('user_id', $user->id)->first();
        }


        return view('purchase', compact('item', 'user', 'address'));
    }

    public function store(PurchaseRequest $request, $id)
    {


        $item = Item::findOrFail($id);

        if ($item->is_sold) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

        $user = Auth::user();


        // セッションに保存された住所を取得
        $sessionAddress = session()->get("purchase_address_{$id}");

        if ($sessionAddress) {
            // セッションの住所を使用
            $tel = $sessionAddress['tel'];
            $address = $sessionAddress['address'];
            $building = $sessionAddress['building'];
        } else {
            // デフォルトの住所を使用
            $default = Address::where('user_id', $user->id)->first();
            $tel = $default->tel;
            $address = $default->address;
            $building = $default->building;
        }

        Purchase::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'tel' => $tel,
            'address' => $address,
            'building' => $building,
            'payment_method' => $request->input('payment_method'),
        ]);

        // 商品は「売れた」フラグだけ変更
        $item->update([
            'is_sold' => true,
            'buyer_id' => $user->id,
        ]);

        return redirect('/');
    }

    public function editAddress($id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first();

        return view('editAddress', compact('item', 'address', 'user'));
    }

    public function updateAddress(editAddressRequest $request, $id)
    {
        $validated = $request->validated();

        $user = Auth::user();

        // セッションにも保存する場合（オプション）
        session()->put("purchase_address_{$id}", $validated);

        return redirect()->route('purchase.show', ['item' => $id]);
    }

}
