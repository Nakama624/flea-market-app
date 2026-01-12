<div>
  <p class="left-row__ttl">支払方法</p>

  <select
    class="payment_method__select"
    id="payment-select"
    name="payment_id"
    wire:model="paymentId"
  >
    <option value="">選択してください</option>
    @foreach ($payments as $payment)
      <option value="{{ $payment->id }}">{{ $payment->payment_method }}</option>
    @endforeach
  </select>

  <div class="form__error-payment">
    @error('payment_id')
      {{ $message }}
    @enderror
  </div>
</div>
