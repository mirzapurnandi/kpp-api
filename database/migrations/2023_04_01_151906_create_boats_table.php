<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boats', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('pemilik');
            $table->string('alamat');
            $table->string('ukuran');
            $table->string('kapten');
            $table->integer('jumlah');
            $table->string('foto');
            $table->string('no_izin');
            $table->string('document')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', [0, 1, 2])->default(false)->comment('0 => waiting; 1 => accept; 2 => reject;');
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
        Schema::dropIfExists('boats');
    }
};
