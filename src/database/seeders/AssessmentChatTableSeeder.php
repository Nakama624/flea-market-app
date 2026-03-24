<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AssessmentChatTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $param = [
      'item_id' => '1',
      'seller_user_id' => '1',
      'buyer_user_id' => '2',
      'seller_completed_at' => null,
      'buyer_completed_at' => null,
      'mail_sent_at' => null,
      'status' => '取引中',
      'last_chat_at' => Carbon::now(),
    ];
    DB::table('assessment_chats')->insert($param);
    $param = [
      'item_id' => '7',
      'seller_user_id' => '2',
      'buyer_user_id' => '1',
      'seller_completed_at' => null,
      'buyer_completed_at' => null,
      'mail_sent_at' => null,
      'status' => '取引中',
      'last_chat_at' => Carbon::now(),
    ];
    DB::table('assessment_chats')->insert($param);
  }
}
