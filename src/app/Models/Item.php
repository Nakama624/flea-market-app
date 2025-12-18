<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'price',
    'brand',
    'description',
    'item_img',
    'condition_id',
    'sell_user_id',
  ];

   // リレーション
  public function condition()
  {
    return $this->belongsTo(Condition::class);
  }

  public function seller(){
    return $this -> belongsTo(User::class, 'sell_user_id');
  }

  public function purchaseItem(){
      return $this->hasOne(Purchase::class, 'item_id');
  }

  public function categories(){
    return $this->belongsToMany(Category::class, 'categories_items');
  }

  public function comments(){
    return $this->hasMany(Comment::class);
  }

  public function likedUsers(){
    return $this->belongsToMany(User::class, 'likes')->withTimestamps();
}
}
