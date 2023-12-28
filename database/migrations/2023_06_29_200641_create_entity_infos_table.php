<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('company_tax_id');
            $table->string('legal_address');
            $table->string('postal_address');
            $table->string('kpp');
            $table->string('checking_account');
            $table->string('bic');
            $table->string('correspondent_account');
            $table->string('bank_name');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
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
        Schema::dropIfExists('entity_infos');
    }
}
