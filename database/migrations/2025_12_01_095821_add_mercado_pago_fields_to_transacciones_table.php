<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            // Agregar campos para Mercado Pago
            $table->string('mercado_pago_preference_id')->nullable()->after('numero_transaccion');
            $table->string('mercado_pago_payment_id')->nullable()->after('mercado_pago_preference_id');
            $table->string('mercado_pago_status')->nullable()->after('mercado_pago_payment_id');
            
            // Agregar índice para búsquedas rápidas
            $table->index('mercado_pago_preference_id');
            $table->index('mercado_pago_payment_id');
        });
        
        // Actualizar el enum de metodo_pago para incluir mercado_pago
        // Nota: MySQL requiere recrear la columna para modificar ENUM
        DB::statement("ALTER TABLE transacciones MODIFY COLUMN metodo_pago ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital', 'mercado_pago') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropIndex(['mercado_pago_preference_id']);
            $table->dropIndex(['mercado_pago_payment_id']);
            $table->dropColumn(['mercado_pago_preference_id', 'mercado_pago_payment_id', 'mercado_pago_status']);
        });
        
        // Revertir el enum
        DB::statement("ALTER TABLE transacciones MODIFY COLUMN metodo_pago ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital') NOT NULL");
    }
};
