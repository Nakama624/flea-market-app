<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class MailAuthTest extends TestCase
{
  use RefreshDatabase;

  /**
   * 会員登録後、認証メールが送信される
   */
  public function test_register_sends_verification_email(): void{
    Notification::fake();

    $response = $this->post('/register', [
      'name' => 'テストユーザー',
      'email' => 'test@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->firstOrFail();

    // 認証メール(VerifyEmail通知)が送られていること
    Notification::assertSentTo($user, VerifyEmail::class);

    // Fortify標準だと登録後はメール認証誘導へ飛ぶことが多い（実装により変わるので必要なら調整）
    $response->assertStatus(302);
  }

  // 認証誘導画面で「認証はこちらから」ボタンを押下できる（画面にリンクが存在する）
  public function test_verification_notice_has_link_button(): void{
    $user = User::factory()->create([
      'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->get('/email/verify');

    $response->assertStatus(200);

    // 文言・リンク（あなたのBladeに合わせてチェック）
    $response->assertSee('認証はこちらから', false);
    $response->assertSee('href="/dev/mailhog/open"', false);
  }

  /**
   * メール認証を完了すると、プロフィール設定画面に遷移する
   */
  public function test_email_verification_redirects_to_profile_page(): void{
    $user = User::factory()->create([
      'email_verified_at' => null,
      'email' => 'verifyme@example.com',
    ]);

    // 署名付きURL（verification.verify ルートが前提）
    $url = URL::temporarySignedRoute(
      'verification.verify',
      now()->addMinutes(60),
      [
        'id' => $user->id,
        'hash' => sha1($user->email),
      ]
    );

    $response = $this->actingAs($user)->get($url);

    // 認証が付いたこと（email_verified_atが埋まる）
    $this->assertNotNull($user->fresh()->email_verified_at);

    // 認証後の遷移先（あなたの要件：プロフィール設定画面）
    $response->assertRedirect('/mypage/profile');
  }
}
