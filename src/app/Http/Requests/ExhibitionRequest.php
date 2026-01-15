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
      'item_img' => [
        'required',
        'image',
        'mimes:jpeg,png',
      ],
      'name' => 'required',
      'description' => 'required|max:255',
      'category_ids' => 'required',
      'condition_id' => 'required',
      'price' => [
        'required',
        'numeric',
        'min:0',
      ],
    ];
  }

  public function messages()
  {
    return [
      'item_img.required' => '画像を選択してください',
      'item_img.image' => '拡張子はjpegまたはpngを選択してください',
      'item_img.mimes' => '拡張子は.jpegまたは.pngから選択してください',
      'name.required' => '商品名を入力してください',
      'description.required' => '商品の説明を入力してください',
      'description.max' => '255文字以内で入力してください',
      'category_ids.required' => '商品カテゴリを選択してください',
      'condition_id.required' => '商品の状態を入力してください',
      'price.required' => '商品価格を入力してください',
      'price.numeric' => '数値で入力してください',
      'price.min' => '0円以上で入力してください',
    ];
  }
}
