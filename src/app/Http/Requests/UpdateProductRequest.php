<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name'=>'required',
            'price'=>'required|integer|between:0,10000',
            'image' => $this->productHasImage()
                ? 'nullable|image|mimes:png,jpeg'
                : 'required|image|mimes:png,jpeg',
            'description'=>'required|max:120',
            'season_id'=>'required|exists:seasons,id',
        ];
    }

        public function messages()
    {
        return[
            'name.required'=>'商品名を入力してください',
            'price.required'=>'値段を入力してください',
            'price.integer'=>'数値で入力してください',
            'price.between'=>'0~10000円以内で入力してください',
            'image.required'=>'商品画像を登録してください',
            'image.mimes'=>'「.png」または「.jpeg」形式でアップロードしてください',
            'description.required'=>'商品説明を入力してください',
            'description.max'=>'120文字以内で入力してください',
            'season_id.required'=>'季節を選択してください',
        ];
    }

    protected function productHasImage()
    {
        $productId = $this->route('productId'); 
        $product = \App\Models\Product::find($productId);
        return $product && $product->img;
    }


}
