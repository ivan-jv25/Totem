<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_producto');
            $table->string('nombre');
            $table->integer('precio_venta');
            $table->integer('precio_venta_neto');
            $table->string('codigo');
            $table->string('codigo_barra')->nullable();
            $table->integer('id_familia');
            $table->string('tipo');
            $table->string('tipo2');
            $table->string('exento');
            $table->string('imagen')->nullable();
            $table->integer('stock');
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
        Schema::dropIfExists('productos');
    }
}
