<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Payment;
use App\Http\Livewire\PaymentSelect;
use App\Http\Livewire\PaymentDisplay;

class SelectPaymentMethodTest extends TestCase
{
  use RefreshDatabase;

  // 小計画面で変更が反映される
  public function test_selected_payment_method_is_reflected_immediately_on_subtotal(){
    // 支払い方法を用意
    $card = Payment::factory()->create(['payment_method' => 'カード払い']);
    $konbini = Payment::factory()->create(['payment_method' => 'コンビニ払い']);

    // 左：セレクトを変更したら即時イベントが飛ぶ
    $payments = Payment::query()->orderBy('id')->get();

    Livewire::test(PaymentSelect::class)
      ->assertSet('paymentId', '')
      ->set('paymentId', $konbini->id)
      ->assertEmitted('paymentSelected', 'コンビニ払い')
      ->set('paymentId', $card->id)
      ->assertEmitted('paymentSelected', 'カード払い')
      ->set('paymentId', '')
      ->assertEmitted('paymentSelected', '');

    // 右：イベントを受け取ったら表示が即時に変わる
    Livewire::test(PaymentDisplay::class)
      ->assertSee('') // 初期は空
      ->emit('paymentSelected', 'コンビニ払い')
      ->assertSee('コンビニ払い')
      ->emit('paymentSelected', 'カード払い')
      ->assertSee('カード払い')
      ->emit('paymentSelected', '')
      ->assertDontSee('カード払い')
      ->assertDontSee('コンビニ払い');
  }
}
