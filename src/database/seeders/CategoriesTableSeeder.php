<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $param = [
      'category_name' => 'ファッション',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => '家電',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'インテリア',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'レディース',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'メンズ',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'コスメ',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => '本',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'ゲーム',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'スポーツ',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'キッチン',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'ハンドメイド',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'アクセサリー',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'おもちゃ',
    ];
    DB::table('categories')->insert($param);
    $param = [
      'category_name' => 'ベビー・キッズ',
    ];
    DB::table('categories')->insert($param);
  }
}
