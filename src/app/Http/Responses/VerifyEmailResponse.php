<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
  public function toResponse($request)
  {
    // メール認証完了後のプロフィール設定へ
    return redirect('/mypage/profile');
  }
}
