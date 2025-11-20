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
        Schema::table('detalle_pedidos', function (Blueprint $table) {
            // Eliminar la foreign key existente
            $table->dropForeign(['producto_id']);
        });

        Schema::table('detalle_pedidos', function (Blueprint $table) {
            // Hacer la columna nullable
            $table->unsignedBigInteger('producto_id')->nullable()->change();

            // Recrear foreign key que permite NULL
            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_pedidos', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
        });

        Schema::table('detalle_pedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_id')->nullable(false)->change();

            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('restrict');
        });
    }
};
