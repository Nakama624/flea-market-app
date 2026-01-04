<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
  public function toResponse($request)
  {
    // 新規登録後はメール認証画面へ
    return redirect('/auth/mail');
  }
}
