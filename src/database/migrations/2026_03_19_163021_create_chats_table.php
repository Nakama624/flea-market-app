<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('chats', function (Blueprint $table) {
      $table->id();
      $table->foreignId('assessment_chats_id')->constrained()->cascadeOnDelete();
      $table->foreignId('sender_user_id')->constrained('users')->cascadeOnDelete();
      $table->string('item_img')->nullable();
      $table->text('chat');
      $table->timestamp('read_at')->nullable();
      $table->timestamp('edited_at')->nullable();
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
    Schema::dropIfExists('chats');
  }
}
