<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportedTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imported_tenders', function (Blueprint $table) {
            $table->id();

            // imported record fields
            $table->string('field_id_contrato')->unique();
            $table->string('field_n_anuncio')->nullable()->default(null);
            $table->string('field_tipo_contrato')->nullable()->default(null);
            $table->string('field_tipo_procedimento')->nullable()->default(null);
            $table->string('field_objecto_contrato')->nullable()->default(null);
            $table->string('field_adjudicantes')->nullable()->default(null);
            $table->string('field_data_publicacao')->nullable()->default(null);
            $table->date('field_data_celebracao_contrato')->nullable()->default(null);
            $table->string('field_preco_contratual')->nullable()->default(null);
            $table->string('field_cpv')->nullable()->default(null);
            $table->string('field_prazo_execucao')->nullable()->default(null);
            $table->string('field_local_execucao')->nullable()->default(null);
            $table->string('field_fundamentacao')->nullable()->default(null);

            $table->foreignId('winning_company_id')->constrained(); // adjudicatarios

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
        Schema::dropIfExists('imported_tenders');
    }
}
