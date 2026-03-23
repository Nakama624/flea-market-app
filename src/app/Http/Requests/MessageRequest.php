<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
  public function rules(){
    return [
      'item_img' => [
        'image',
        'mimes:jpeg,png',
      ],
      'message' => 'required|max:400',
    ];
  }

  public function messages(){
    return [
      'item_img.image' => '「.png」または「.jpeg」形式でアップロードしてください',
      'item_img.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
      'message.required' => '本文を入力してください',
      'message.max' => '本文は400文字以内で入力してください',
    ];
  }

}
