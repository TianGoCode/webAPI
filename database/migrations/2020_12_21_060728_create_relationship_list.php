<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationship_list', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id1');
            $table->foreign('user_id1')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_id2');
            $table->foreign('user_id2')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_reqesting')->nullable();
            $table->boolean('is_friends')->nullable();
            $table->boolean('is_blocked')->nullable();
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
        Schema::dropIfExists('relationship_list');
    }
}
