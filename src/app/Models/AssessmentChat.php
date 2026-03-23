<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentChat extends Model
{
  use HasFactory;

  protected $fillable = [
    'item_id',
    'seller_user_id',
    'buyer_user_id',
    'seller_completed_at',
    'buyer_completed_at',
    'mail_sent_at',
    'status',
    'last_chat_at',
  ];

  public function assessments(){
    return $this->hasMany(Assessment::class, 'assessment_chats_id');
  }

  public function chats(){
    return $this->hasMany(Chat::class, 'assessment_chats_id');
  }

  public function seller(){
    return $this->belongsTo(User::class, 'seller_user_id');
  }

  public function buyer(){
    return $this->belongsTo(User::class, 'buyer_user_id');
  }

  public function item(){
    return $this->belongsTo(Item::class);
  }

}
