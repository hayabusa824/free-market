<?php
namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest; // バリデーション用リクエスト
use App\Http\Requests\ProfileRequest; // バリデーション用リクエスト
use App\Models\Address;
use Illuminate\Support\Facades\Storage;

class AddressController extends Controller
{
    public function index()
    {

        // メール認証が完了していない場合はリダイレクト
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('error', 'メール認証が完了していません。認証を行ってください。');
        }

        $user = auth()->user();
        $address = $user->address; // リレーションを使用してアドレス情報を取得

        return view('auth.profile', compact('user', 'address'));
    }


    public function store(AddressRequest $request)
    {
        $user = auth()->user();

        $validated = $request->validated();

        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        // データを保存
        $user->address()->create($validated);

        return redirect('/');
    }

    public function update(AddressRequest $request)
    {
        $user = auth()->user();

        $validated = $request->validated();

        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            // 古い画像を削除
            if ($user->address && $user->address->profile_image) {
                Storage::disk('public')->delete($user->address->profile_image);
            }

            // 新しい画像を保存
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        // データを更新または作成
        $user->address()->updateOrCreate(
            ['user_id' => $user->id], // 条件: user_id が一致するレコード
            $validated // 更新するデータ
        );

        return redirect('/mypage');
    }

}