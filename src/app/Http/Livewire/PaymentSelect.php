<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payment;

class PaymentSelect extends Component
{
  public $paymentId = '';

  public function updatedPaymentId($value): void{
    if ($value === '' || $value === null) {
      $this->emit('paymentSelected', '');
      return;
    }

    $label = Payment::find((int)$value)?->payment_method ?? '';
    $this->emit('paymentSelected', $label);
  }

  public function render() {
    $payments = Payment::query()->orderBy('id')->get();

    return view('livewire.payment-select', compact('payments'));
  }
}
