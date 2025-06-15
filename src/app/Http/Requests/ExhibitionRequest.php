<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png',
            'category' => 'required',
            'condition' => 'required|string',
            'brand' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'price.required' => '価格を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'image.required' => '画像を入力してください。',
            'category.required' => 'カテゴリーを入力してください。',
            'condition.required' => '商品の状態を入力してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください。',
        ];
    }
}
