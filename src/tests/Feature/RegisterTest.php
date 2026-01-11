<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
  use RefreshDatabase;

  private function validRegisterData(array $overrides = []): array
  {
    return array_merge([
      'name' => 'テスト',
      'email' => 'test@example.com',
      'password' => 'password123',              // 8文字以上
      'password_confirmation' => 'password123', // 一致
    ], $overrides);
  }

  private function assertFirstErrorMessage(string $field, string $expected): void
  {
    $this->assertEquals($expected, session('errors')->first($field));
  }

  /** 名前 */
  public function test_register_name_required_message_is_shown()
  {
    $response = $this->post('/register', $this->validRegisterData([
      'name' => '',
    ]));

    $response->assertSessionHasErrors(['name']);
    $this->assertFirstErrorMessage('name', 'お名前を入力してください');
  }

  /** メール */
  public function test_register_email_required_message_is_shown()
  {
    $response = $this->post('/register', $this->validRegisterData([
      'email' => '',
    ]));

    $response->assertSessionHasErrors(['email']);
    $this->assertFirstErrorMessage('email', 'メールアドレスを入力してください');
  }

  /** パスワード未入力 */
  public function test_register_password_required_message_is_shown()
  {
    $response = $this->post('/register', $this->validRegisterData([
      'password' => '',
      'password_confirmation' => '',
    ]));

    $response->assertSessionHasErrors(['password']);
    $this->assertFirstErrorMessage('password', 'パスワードを入力してください');
  }

  /** パスワード7文字以下 */
  public function test_register_password_min_8_message_is_shown()
  {
    $response = $this->post('/register', $this->validRegisterData([
      'password' => '1234567',
      'password_confirmation' => '1234567',
    ]));

    $response->assertSessionHasErrors(['password']);
    $this->assertFirstErrorMessage('password', 'パスワードは8文字以上で入力してください');
  }

  /** 確認用パスワード不一致 */
  public function test_register_password_confirmation_mismatch_message_is_shown()
  {
    $response = $this->post('/register', $this->validRegisterData([
      'password' => 'password123',
      'password_confirmation' => 'password999',
    ]));

    $response->assertSessionHasErrors(['password']);
    $this->assertFirstErrorMessage('password', 'パスワードと一致しません');
  }

  /** 登録されプロフィール設定へ遷移 */
  public function test_register_success_creates_user_and_redirects_to_profile()
  {
    $data = $this->validRegisterData([
      'email' => 'ok@example.com',
    ]);

    $response = $this->post('/register', $data);

    // 会員情報が登録される
    $this->assertDatabaseHas('users', [
      'name' => $data['name'],
      'email' => $data['email'],
    ]);

    // メール認証誘導画面に遷移
    $response->assertRedirect('/auth/mail');
  }
}