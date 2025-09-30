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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('estado_pago', ['pendiente', 'pagado', 'fallido'])->default('pendiente')->after('estado');
            $table->enum('metodo_pago_preferido', ['efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital'])->default('efectivo')->after('estado_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['estado_pago', 'metodo_pago_preferido']);
        });
    }
};
