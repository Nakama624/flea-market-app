<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
      'payment_id' => 'required',
      'delivery_postcode' => 'required',
      'delivery_address' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'payment_id.required' => '支払方法を選択してください',
      'delivery_postcode.required' => '郵便番号の指定がありません',
      'delivery_address.required' => '住所の指定がありません',
    ];
  }
}
