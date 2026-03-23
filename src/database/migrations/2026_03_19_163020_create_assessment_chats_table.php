<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentChatsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('assessment_chats', function (Blueprint $table) {
      $table->id();
      $table->foreignId('item_id')->constrained()->cascadeOnDelete();
      $table->foreignId('seller_user_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('buyer_user_id')->constrained('users')->cascadeOnDelete();
      $table->timestamp('seller_completed_at')->nullable();
      $table->timestamp('buyer_completed_at')->nullable();
      $table->timestamp('mail_sent_at')->nullable();
      $table->string('status');
      $table->timestamp('last_chat_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('assessment_chats');
  }
}
