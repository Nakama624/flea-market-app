<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
  use HasFactory;

  protected $fillable = [
    'assessment_chats_id',
    'from_user_id',
    'to_user_id',
    'score',
  ];

  public function assessmentChat(){
    return $this->belongsTo(AssessmentChat::class, 'assessment_chats_id');
  }
  
  public function fromUser(){
    return $this->belongsTo(User::class, 'from_user_id');
  }

  public function toUser(){
    return $this->belongsTo(User::class, 'to_user_id');
  }
}
