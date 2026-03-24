<?php

namespace App\Mail;

use App\Models\AssessmentChat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerCompletedNotification extends Mailable
{
  use Queueable, SerializesModels;

  public $chat;

  public function __construct(AssessmentChat $chat)
  {
    $this->chat = $chat;
  }

  public function build()
  {
    return $this->subject('購入者が取引を完了しました')
        ->view('emails.seller_completed');
  }
}
