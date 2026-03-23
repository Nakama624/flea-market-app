<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  use HasFactory;

  protected $fillable = [
    'assessment_chats_id',
    'sender_user_id',
    'item_img',
    'chat',
    'read_at',
    'edited_at',
  ];

  public function assessmentChat(){
    return $this->belongsTo(AssessmentChat::class, 'assessment_chats_id');
  }

  public function sender(){
    return $this->belongsTo(User::class, 'sender_user_id');
  }
}
