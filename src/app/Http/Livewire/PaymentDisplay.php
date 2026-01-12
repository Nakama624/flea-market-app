<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaymentDisplay extends Component
{
  public $label = '';

  protected $listeners = [
    'paymentSelected' => 'setLabel',
  ];

  public function setLabel(string $label): void{
    $this->label = $label;
  }

  public function render(){
    return view('livewire.payment-display');
  }
}
