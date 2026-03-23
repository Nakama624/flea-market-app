<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */

  // フリーマーケットなので必ず出品者がいる前提。
  // 出品商品に出品会員を紐づけるため会員を事前に作成。
  public function run()
  {
    $param = [
      'name' => 'test1',
      'email' => 'test1@gmail.com',
      'password' => Hash::make('password'),
      'postcode' => '111-1111',
      'address' => '東京都品川区11',
      'building' => 'テストビルディング1',
      'email_verified_at' => Carbon::now(),
    ];
    DB::table('users')->insert($param);

    $param = [
      'name' => 'test2',
      'email' => 'test2@gmail.com',
      'password' => Hash::make('password'),
      'postcode' => '222-2222',
      'address' => '東京都品川区22',
      'building' => 'テストビルディング2',
      'email_verified_at' => Carbon::now(),
    ];
    DB::table('users')->insert($param);

    $param = [
      'name' => 'test3',
      'email' => 'test3@gmail.com',
      'password' => Hash::make('password'),
      'postcode' => '333-3333',
      'address' => '東京都品川区33',
      'building' => 'テストビルディング3',
      'email_verified_at' => Carbon::now(),
    ];
    DB::table('users')->insert($param);
  }
}
