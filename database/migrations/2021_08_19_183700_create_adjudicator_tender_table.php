<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjudicatorTenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjudicator_tender', function (Blueprint $table) {
            $table->id();

            $table->foreignId('adjudicator_id')->constrained()->onDelete('cascade'); // adjudicantes
            $table->foreignId('tender_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('adjudicator_tender');
    }
}
