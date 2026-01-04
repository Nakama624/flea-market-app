<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
      'profile_img' => [
        'image',
        'mimes:jpeg,png',
      ],
      'name' => 'required|max:20',
      'postcode' => [
        'required',
        'regex:/^\d{3}-\d{4}$/',
      ],
      'address' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'profile_img.mimes' => '拡張子は.jpegまたは.pngから選択してください',
      'name.required' => 'ユーザー名を入力してください',
      'name.max' => '20文字以内で入力してください',
      'postcode.required' => '郵便番号を入力してください',
      'postcode.regex' => 'ハイフンありの8桁で入力してください',
      'address.required' => '住所を入力してください',
    ];
  }
}
