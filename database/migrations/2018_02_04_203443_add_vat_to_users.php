<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatToUsers extends Migration
{
    /**
     * 
     * Let me just take a minute here to say that I fucking hate everything
     * that VAT is and stands for. The EU as a whole is a pretty neat thing,
     * but they really dropped the fucking ball on this one. Taxation need not
     * be so fucking complicated, god dammit.
     * 
     * Anyhow, back to the code.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('VAT')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('VAT');
        });
    }
}
