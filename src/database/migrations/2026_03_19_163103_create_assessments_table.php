<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('assessments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('assessment_chats_id')
        ->constrained()
        ->cascadeOnDelete();
      $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
      $table->tinyInteger('score');
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
    Schema::dropIfExists('assessments');
  }
}
