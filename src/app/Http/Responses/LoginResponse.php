<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
  public function toResponse($request)
  {
    $user = $request->user();

    if ($user && ! $user->hasVerifiedEmail()) {
      // メール送信
      $user->sendEmailVerificationNotification();

      return redirect()->route('verification.notice');
    }
    return redirect()->intended('/mypage');
  }
}
