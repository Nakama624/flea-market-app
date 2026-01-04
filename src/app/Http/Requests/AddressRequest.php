<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
      'delivery_postcode' => [
        'required',
        'regex:/^\d{3}-\d{4}$/',
      ],
      'delivery_address' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'delivery_postcode.required' => '郵便番号を入力してください',
      'delivery_postcode.regex' => 'ハイフンありの8桁で入力してください',
      'delivery_address.required' => '住所を入力してください',
    ];
  }
}
