<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $param = [
      'assessment_chats_id' => '1',
      'sender_user_id' => '2',
      'item_img' => null,
      'chat' => 'テストメッセージ',
      'read_at' => null,
      'edited_at' => null,
    ];
    DB::table('chats')->insert($param);
    $param = [
      'assessment_chats_id' => '2',
      'sender_user_id' => '1',
      'item_img' => null,
      'chat' => 'テストメッセージ',
      'read_at' => null,
      'edited_at' => null,
    ];
    DB::table('chats')->insert($param);
  }
}
