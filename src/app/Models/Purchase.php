<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
  use HasFactory;

  protected $fillable = [
    'item_id',
    'user_id',
    'delivery_postcode',
    'delivery_address',
    'delivery_building',
    'payment_id',
    'status',
    'paid_at',
  ];

  // リレーション
  public function user(){
    return $this->belongsTo(User::class);
  }

  public function item(){
    return $this->belongsTo(Item::class);
  }

  public function payment(){
    return $this->belongsTo(Payment::class);
  }
}
