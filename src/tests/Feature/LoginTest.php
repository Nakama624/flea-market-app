<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
  // メールアドレス
  public function test_email_is_required_on_login(){
    $response = $this->post('/login', [
      'email' => '',
      'password' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);

    $this->assertEquals(
      'メールアドレスを入力してください',
      session('errors')->first('email')
    );
  }
  // パスワード
  public function test_password_is_required_on_login(){
    $response = $this->post('/login', [
      'email' => 'test@example.com',
      'password' => '',
    ]);

    $response->assertSessionHasErrors(['password']);

    $this->assertEquals(
      'パスワードを入力してください',
      session('errors')->first('password')
    );
  }

  public function test_login_fails_with_unregistered_credentials_shows_message(){
    // 登録なしのユーザーでログイン
    $response = $this->from('/login')->post('/login', [
      'email' => 'notfound@example.com',
      'password' => 'wrongpassword',
    ]);

    // ログイン失敗なので未認証
    $this->assertGuest();

    // emailについているメッセージを参照
    $response->assertSessionHasErrors(['email']);

    // メッセージの一致確認
    $this->assertEquals(
      'ログイン情報が登録されていません',
      session('errors')->first('email')
    );

    // ログイン画面に戻る
    $response->assertRedirect('/login');
  }

}
