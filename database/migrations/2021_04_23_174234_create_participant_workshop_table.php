<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantWorkshopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_workshop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->references('id')->on('participants')->constrained()->onDelete('cascade');
            $table->foreignId('workshop_id')->references('id')->on('workshops')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('participant_workshop');
    }
}
