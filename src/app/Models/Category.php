<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  protected $fillable = [
    'category_name',
  ];

  // リレーション
  public function items(){
    return $this->belongsToMany(Item::class, 'categories_items', 'category_id', 'item_id');
  }
}
