<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ItemsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $param = [
      'name' => '腕時計',
      'price' => '15000',
      'brand' => 'Rolax',
      'description' => 'スタイリッシュなデザインのメンズ腕時計',
      'item_img' => 'd_Armani+Mens+Clock.jpg',
      'condition_id' => '1',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'HDD',
      'price' => '5000',
      'brand' => '西芝',
      'description' => '高速で信頼性の高いハードディスク',
      'item_img' => 'd_HDD+Hard+Disk.jpg',
      'condition_id' => '2',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => '玉ねぎ3束',
      'price' => '300',
      'brand' => 'なし',
      'description' => '新鮮な玉ねぎ3束のセット',
      'item_img' => 'd_iLoveIMG+d.jpg',
      'condition_id' => '3',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => '革靴',
      'price' => '4000',
      'brand' => '',
      'description' => 'クラシックなデザインの革靴',
      'item_img' => 'd_Leather+Shoes+Product+Photo.jpg',
      'condition_id' => '4',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'ノートPC',
      'price' => '45000',
      'brand' => '',
      'description' => '高性能なノートパソコン',
      'item_img' => 'd_Living+Room+Laptop.jpg',
      'condition_id' => '1',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'マイク',
      'price' => '8000',
      'brand' => 'なし',
      'description' => '高音質のレコーディング用マイク',
      'item_img' => 'd_Music+Mic+4632231.jpg',
      'condition_id' => '2',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'ショルダーバッグ',
      'price' => '3500',
      'brand' => '',
      'description' => 'おしゃれなショルダーバッグ',
      'item_img' => 'd_Purse+fashion+pocket.jpg',
      'condition_id' => '3',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'タンブラー',
      'price' => '500',
      'brand' => 'なし',
      'description' => '使いやすいタンブラー',
      'item_img' => 'd_Tumbler+souvenir.jpg',
      'condition_id' => '4',
      'sell_user_id' => '2',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'コーヒーミル',
      'price' => '4000',
      'brand' => 'Starbacks',
      'description' => '使いやすいタンブラー',
      'item_img' => 'd_Waitress+with+Coffee+Grinder.jpg',
      'condition_id' => '1',
      'sell_user_id' => '1',
    ];
    DB::table('items')->insert($param);
    $param = [
      'name' => 'メイクセット',
      'price' => '2500',
      'brand' => '',
      'description' => '便利なメイクアップセット',
      'item_img' => 'd_makeupset.jpg',
      'condition_id' => '2',
      'sell_user_id' => '2',
    ];
    DB::table('items')->insert($param);
  }
}
