<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();

            // imported record fields
            $table->string('contract_id')->unique();
            $table->string('ad_number')->nullable()->default(null);
            $table->string('tender_type')->nullable()->default(null);
            $table->string('contract_target')->nullable()->default(null);
            $table->date('publication_date')->nullable()->default(null);
            $table->date('contract_signing_date')->nullable()->default(null);
            $table->string('contract_price')->nullable()->default(null);
            $table->string('execution_time')->nullable()->default(null);
            $table->string('legal_bases')->nullable()->default(null);

            $table->timestamp('read_at')->nullable()->default(null);

            $table->foreignId('uploaded_file_id')->constrained();

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
        Schema::dropIfExists('tenders');
    }
}
