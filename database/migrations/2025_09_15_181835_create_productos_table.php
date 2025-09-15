<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->text('descripcion');
            $table->decimal('precio', 8, 2);
            $table->decimal('precio_descuento', 8, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->enum('estado', ['disponible', 'stock_bajo', 'agotado'])->default('disponible');
            $table->boolean('activo')->default(true);
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->boolean('destacado')->default(false);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
