<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'postcode',
    'address',
    'building',
    'profile_img',
    'email_verified_at',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  // リレーション
  public function sellItems(){
    return $this->hasMany(Item::class, 'sell_user_id');
  }

  public function comments(){
    return $this->hasMany(Comment::class);
  }

  public function likedItems(){
    return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id')
      ->withTimestamps();
  }

  public function purchases(){
    return $this->hasMany(Purchase::class, 'user_id');
  }

  // 以下、protest用追加
    // -----------------
  // Chat（送信した）
  // -----------------
  public function sentChats(){
    return $this->hasMany(Chat::class, 'sender_user_id');
  }

  // -----------------
  // AssessmentChat（売り手として）
  // -----------------
  public function sellingAssessmentChats() {
    return $this->hasMany(AssessmentChat::class, 'seller_user_id');
  }

  // -----------------
  // AssessmentChat（買い手として）
  // -----------------
  public function buyingAssessmentChats(){
    return $this->hasMany(AssessmentChat::class, 'buyer_user_id');
  }

  // -----------------
  // Assessment（評価した）
  // -----------------
  public function givenAssessments(){
    return $this->hasMany(Assessment::class, 'from_user_id');
  }

  // -----------------
  // Assessment（評価された）
  // -----------------
  public function receivedAssessments(){
    return $this->hasMany(Assessment::class, 'to_user_id');
  }
}

